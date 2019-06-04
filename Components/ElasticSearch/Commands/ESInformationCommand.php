<?php

namespace Espricho\Components\ElasticSearch\Commands;

use Espricho\Components\Helpers\Str;
use Espricho\Components\Console\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Output\OutputInterface;
use Espricho\Components\Contracts\SearchEngineInterface;

use function sys;
use function service;
use function array_pop;
use function array_merge;

/**
 * Class ESInformationCommand show information of a
 *
 * @package Espricho\Components\ElasticSearch\Commands
 */
class ESInformationCommand extends Command
{
    /**
     * @inheritDoc
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('es:info')
             ->setDescription('Show information of running ElasticSearch service')
        ;
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $servers = Str::split(sys()->getConfig('elasticsearch.servers', ''));
        if (!$servers) {
            $output->writeln('There is no servers defined with <info>ELASTICSEARCH_SERVERS</info> on the .env file.');

            return 1;
        }

        $table = new Table($output);

        $client = service(SearchEngineInterface::class);

        $rows = $this->getRows($client->info([]));
        array_pop($rows);
        $table->setRows($rows);

        $table->render();

        return 0;
    }

    /**
     * Generate rows
     *
     * @param array $info
     *
     * @return array
     */
    private function getRows(array $info): array
    {
        $result = [];

        foreach ($info as $key => $value) {
            if ($key == 'version') {
                $result = array_merge($result, $this->getRows($value));
                continue;
            }

            $result[] = [Str::snakeToTitle($key), $value];
            $result[] = new TableSeparator();
        }

        return $result;
    }
}
