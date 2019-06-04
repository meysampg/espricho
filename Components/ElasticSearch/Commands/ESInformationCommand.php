<?php

namespace Espricho\Components\ElasticSearch\Commands;

use GuzzleHttp\Psr7\Response;
use Espricho\Components\Helpers\Str;
use Espricho\Components\Console\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Output\OutputInterface;

use function sys;
use function sprintf;
use function explode;
use function service;
use function array_pop;
use function json_decode;
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

        foreach ($servers as $server) {
            $res = service('http-client')->get($server);

            $table->setHeaders(['Server', $server]);

            $rows = $this->getRows($res);
            array_pop($rows);
            $table->setRows($rows);

            $table->render();
        }

        return 0;
    }

    /**
     * Generate rows
     *
     * @param Response $response
     *
     * @return array
     */
    private function getRows(Response $response): array
    {
        $es = json_decode($response->getBody()->getContents(), true);

        return $this->arrayToRow($es);
    }

    /**
     * Convert a given set of ES information to tables rows
     *
     * @param array $info
     *
     * @return array
     */
    private function arrayToRow(array $info): array
    {
        $result = [];

        foreach ($info as $key => $value) {
            if ($key == 'version') {
                $result = array_merge($result, $this->arrayToRow($value));
                continue;
            }

            $result[] = [Str::snakeToTitle($key), $value];
            $result[] = new TableSeparator();
        }

        return $result;
    }
}
