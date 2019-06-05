<?php

namespace Espricho\Components\ElasticSearch\Commands;

use Exception;
use Espricho\Components\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Espricho\Components\Contracts\SearchEngineInterface;

use function sys;
use function implode;
use function service;
use function array_map;
use function json_decode;

/**
 * Class CreateESMappingCommand generate mappings based on the configurations. For
 * using this command please put the mapping under the mappings section of the
 * elasticsearch configurations files.
 *
 * @package Espricho\Components\ElasticSearch\Commands
 */
class CreateESMappingCommand extends Command
{
    /**
     * @inheritDoc
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('es:mapping:generate')
             ->setDescription('Create ElasticSearch mapping based on the configuration')
             ->addOption(
                  'index',
                  'i',
                  InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                  'Name of index to create/update mapping. If no index has been selected, all indexes will be selected'
             )
        ;
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $mappings = $this->getMappings($input, $output, sys()->getConfig('elasticsearch.mappings'));

        foreach ($mappings as $index => $mapping) {
            if ($this->indexExists($index)) {
                $action  = "updat";
                $mapping = $this->generateUpdateMapping($index, $mapping);
                $result  = $this->putMapping($mapping);
            } else {
                $action  = "creat";
                $mapping = $this->generateCreateMapping($index, $mapping);
                $result  = $this->createMapping($mapping);
            }

            if (isset($result['error'])) {
                $error = json_decode($result['error'], true);
                if (isset($error['error']['root_cause'])) {
                    $error = current($error['error']['root_cause'])['reason'];
                }

                $output->writeln("Error on <info>{$action}ing</info> of mapping <error>$index</error>: $error");
                continue;
            } else {
                $output->writeln("Mapping of <info>$index</info> {$action}ed.");
            }
        }
    }

    /**
     * Check an index exists or not
     *
     * @param string $index
     *
     * @return bool
     */
    protected function indexExists(string $index): bool
    {
        return service(SearchEngineInterface::class)->indices()->exists(['index' => $index]);
    }

    /**
     * Create a mapping
     *
     * @param array $mapping
     *
     * @return array
     */
    protected function createMapping(array $mapping)
    {
        try {
            $resp = service(SearchEngineInterface::class)->indices()->create($mapping);

            return $resp;
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    /**
     * Update a mapping
     *
     * @param array $mapping
     *
     * @return array
     */
    protected function putMapping(array $mapping)
    {
        try {
            $resp = service(SearchEngineInterface::class)->indices()->putMapping($mapping);

            return $resp;
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    /**
     * Generate mappings based on the input request
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @param array           $configMappings
     *
     * @return array
     */
    protected function getMappings(InputInterface $input, OutputInterface $output, array $configMappings): array
    {
        $mappings = [];

        if ($input->getOption('index')) {
            $indexes          = $input->getOption('index');
            $formattedIndexes = implode(
                 ',',
                 array_map(
                      function ($i) {
                          return " <info>$i</info>";
                      },
                      $indexes
                 )
            );
            $output->writeln("Patch mappings of$formattedIndexes indexes...");

            foreach ($indexes as $index) {
                if (isset($configMappings[$index])) {
                    $mappings[$index] = $configMappings[$index];
                } else {
                    $output->writeln("Mapping for index <error>$index</error> is not defined. skipping...");
                    continue;
                }
            }
        } else {
            $output->writeln('Patch mappings for <info>all indexes</info>...');
            $mappings = $configMappings;
        }

        return $mappings;
    }

    /**
     * Generate a new mapping
     *
     * @param string $index
     * @param array  $mapping
     *
     * @return array
     */
    protected function generateCreateMapping(string $index, array $mapping): array
    {
        return [
             'index' => $index,
             'body'  => [
                  'mappings' => $this->generateNestedMapping($mapping),
             ],
        ];
    }

    /**
     * Generate an updated mapping
     *
     * @param string $index
     * @param array  $mapping
     *
     * @return array
     */
    protected function generateUpdateMapping(string $index, array $mapping): array
    {
        return [
             'index' => $index,
             'body'  => $this->generateNestedMapping($mapping),
        ];
    }

    /**
     * Generate a nested mapping
     *
     * @param array $mapping
     * @param bool  $nestedCall
     *
     * @return array
     */
    protected function generateNestedMapping(array $mapping, bool $nestedCall = false): array
    {
        $final = [];

        foreach ($mapping as $item => $value) {
            if ($nestedCall && $item == 'type' && $value == 'nested') {
                continue;
            }

            if (isset($value['type']) && $value['type'] == 'nested') {
                $value         = $this->generateNestedMapping($value, true);
                $value['type'] = 'nested';
            }

            $final[$item] = $value;
        }

        return [
             'properties' => $final,
        ];
    }
}
