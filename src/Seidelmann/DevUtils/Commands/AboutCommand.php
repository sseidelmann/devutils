<?php
/**
 * Class AboutCommand
 * @package Seidelmann\DevUtils\Commands
 * @author Sebastian Seidelmann <sebastian.seidelmann@googlemail.com>
 */

namespace Seidelmann\DevUtils\Commands;

use Seidelmann\DevUtils\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

/**
 * Class AboutCommand
 * @package Seidelmann\DevUtils\Commands
 * @author Sebastian Seidelmann <sebastian.seidelmann@googlemail.com>
 */
class AboutCommand extends Command
{
    /**
     * Configures the current command.
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('about')
            ->setDescription('Shows the help');
    }

    /**
     * Executes the command.
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $commandHelp = <<<COMMAND_HELP

<info>%s</info> version <comment>%s</comment>

All available commands:
 <info>Git</info>
   <comment>git:release:create</comment>               Creates a new release
   <comment>git:util:removetag</comment>               Helper for deleting tags
 <info>Phar</info>
   <comment>phar:bitbucket:create</comment>            Creates a PHAR for bitbucket repositories

COMMAND_HELP;

        $output->writeln(sprintf(
            $commandHelp,
            $this->getApplication()->getName(),
            $this->getApplication()->getVersion()
        ));
    }

}