<?php

namespace Espricho\Components\Auth\Commands;

use Exception;
use Espricho\Components\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use function service;
use function sprintf;
use function boolval;
use function filter_var;

/**
 * Class CreateUserCommand provides command to create a user
 *
 * @package Espricho\Components\Auth\Commands
 */
class CreateUserCommand extends Command
{
    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setName('auth:create-user')
             ->setDescription('Create a new user')
             ->addArgument(
                  'username',
                  InputArgument::REQUIRED,
                  'Username of the user'
             )
             ->addArgument(
                  'password',
                  InputArgument::REQUIRED,
                  'Password of the user'
             )
             ->addOption(
                  'email',
                  'e',
                  InputOption::VALUE_REQUIRED,
                  'Email of the user',
                  null
             )
             ->addOption(
                  'is-admin',
                  'a',
                  InputOption::VALUE_NONE,
                  'Set account as an admin'
             )
        ;
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($input->hasOption('email') && !filter_var($input->getOption('email'), FILTER_VALIDATE_EMAIL)) {
            $output->writeln(sprintf('<error>%s</error> is not a valid email.', $input->getOption('email')));

            return 1;
        }

        $data    = [
             'username' => $input->getArgument('username'),
             'password' => $input->getArgument('password'),
             'email'    => $input->getOption('email'),
        ];
        $isAdmin = boolval($input->getOption('is-admin'));

        try {
            service('auth')->register($data, $isAdmin);
            $output->writeln(sprintf('User <info>%s</info> created successfully.', $data['username']));

            return 0;
        } catch (Exception $e) {
            $output->writeln(
                 sprintf(
                      'Could not create user <error>%s</error>. %s',
                      $data['username'],
                      $e->getMessage()
                 )
            );
        }

        return 1;
    }
}
