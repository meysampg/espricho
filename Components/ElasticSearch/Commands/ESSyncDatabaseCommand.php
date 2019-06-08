<?php

namespace Espricho\Components\ElasticSearch\Commands;

use Exception;
use Espricho\Components\Console\Command;
use Espricho\Components\Contracts\SearchInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use function sprintf;
use function service;
use function class_exists;

class ESSyncDatabaseCommand extends Command
{
    /**
     * @inheritDoc
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('es:sync-db')
             ->setDescription('Put all models from DB into engine')
             ->addArgument(
                  'model',
                  InputArgument::REQUIRED | InputArgument::IS_ARRAY,
                  'Model names (in FQN format) which data must be fetched from them'
             )
        ;
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $models = $input->getArgument('model');

        foreach ($models as $model) {
            try {
                $number = $this->sync($model);
                $output->writeln(sprintf('<info>%d</info> record from <info>%s</info> model synced.', $number, $model));
            } catch (Exception $e) {
                $output->writeln(sprintf('Syncing of <error>%s</error> failed, because %s', $model, $e->getMessage()));
            }
        }

        return 0;
    }

    /**
     * Sync a model with the mapping
     *
     * @param string $model
     *
     * @return int
     * @throws Exception
     */
    private function sync(string $model): int
    {
        if (!class_exists($model)) {
            throw new Exception(sprintf('model %s not exists.', $model));
        }

        $c      = 0;
        $em     = $this->getHelper('entityManager')->getEntityManager();
        $models = $em->getRepository($model)->findAll();

        foreach ($models as $model) {
            if (service(SearchInterface::class)->updateIndexFor($model)) {
                $c++;
            }
        }

        return $c;
    }

}
