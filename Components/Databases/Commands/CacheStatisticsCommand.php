<?php

namespace Espricho\Components\Databases\Commands;

use Espricho\Components\Console\Command;
use function get_class;
use ReflectionClass;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Espricho\Components\Contracts\DoctrineCacheInterface;

use function sprintf;
use function service;

/**
 * Class CacheStatisticsCommand shows cache server information
 *
 * @package Espricho\Components\Databases\Commands
 */
class CacheStatisticsCommand extends Command
{
    /**
     * @inheritDoc
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('orm:cache:statistics')
             ->setDescription('Show the statistics of the ORM second level caching system')
        ;
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $table  = new Table($output);
        $cache  = service(DoctrineCacheInterface::class);
        $refl   = new ReflectionClass($cache);
        $stats  = $cache->getStats();
        $driver = $refl->getShortName();

        if (!$stats) {
            $output->writeln(sprintf('Currently there is no stats for <info>%s</info> driver.', $driver));
        }

        $table->setHeaders(['Metric', 'Hits']);

        foreach ($stats as $key => $value) {
            $table->addRow([$key, $value]);
        }

        $output->writeln(sprintf("Currently system uses <info>%s</info> as cache driver.", $driver));
        $table->render();

        return 0;
    }
}
