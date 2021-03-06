<?php
/**
 * Class CreateCommand
 * @package Seidelmann\DevUtils\Commands\Git\Release
 * @author Sebastian Seidelmann <sebastian.seidelmann@googlemail.com>
 */

namespace Seidelmann\DevUtils\Commands\Git\Release;

use Seidelmann\DevUtils\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

/**
 * Class CreateCommand
 * @package Seidelmann\DevUtils\Commands\Git\Release
 * @author Sebastian Seidelmann <sebastian.seidelmann@googlemail.com>
 */
class CreateCommand extends Command
{
    /**
     * Configures the current command.
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('git:release:create')
            ->setDescription('Creates a new release.');
    }

    /**
     * Executes the command.
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getGitHelper()->createFullRelease();
    }

}