<?php

namespace Espricho\Components\Auth\Commands;

use Exception;
use Espricho\Models\User;
use Espricho\Components\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use function sprintf;

/**
 * Class CreateUserCommand provides command to mark user as admin
 *
 * @package Espricho\Components\Auth\Commands
 */
class AdminUserCommand extends Command
{
    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setName('auth:admin-user')
             ->setDescription('Make or remove a user from admin role')
             ->addOption(
                  'username',
                  'u',
                  InputOption::VALUE_REQUIRED,
                  'Username of the user'
             )
             ->addOption(
                  'id',
                  'i',
                  InputOption::VALUE_REQUIRED,
                  'ID of the user'
             )
             ->addOption(
                  'add',
                  'a',
                  InputOption::VALUE_NONE,
                  'Set account as an admin'
             )
             ->addOption(
                  'remove',
                  'r',
                  InputOption::VALUE_NONE,
                  'Remove admin role from account'
             )
        ;
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!$input->getOption('username') && !$input->getOption('id')) {
            $output->writeln('One of <info>id</info> or <info>username</info> options are required.');

            return 1;
        }

        if (!$input->getOption('add') && !$input->getOption('remove')) {
            $output->writeln('One of <info>add</info> or <info>remove</info> options are required.');

            return 1;
        }

        if ($input->getOption('add') && $input->getOption('remove')) {
            $output->writeln('Just one of <info>add</info> or <info>remove</info> options is allowed.');

            return 1;
        }

        if ($input->getOption('id')) {
            $cond = ['id' => $input->getOption('id')];
        } else {
            $cond = ['username' => $input->getOption('username')];
        }

        try {
            $em   = $this->getHelper('entityManager')->getEntityManager();
            $repo = $em->getRepository(User::class);
            $user = $repo->findOneBy($cond);
            if (!$user) {
                $output->writeln('<error>User not found.</error>');

                return 1;
            }

            $user->setIsAdmin($input->getOption('add'));
            $em->persist($user);
            $em->flush();

            if ($input->getOption('add')) {
                $output->writeln(sprintf('<info>%s</info> marked as admin.', $user->getUsername()));
            } else {
                $output->writeln(sprintf('<info>%s</info> removed from being admin.', $user->getUsername()));
            }

            return 0;
        } catch (Exception $e) {
            $output->writeln(
                 sprintf(
                      '<error>Error:</error> %s',
                      $e->getMessage()
                 )
            );
        }

        return 1;
    }
}
