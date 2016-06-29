<?php
/**
 * Class CreateCommand
 * @package Seidelmann\DevUtils\Commands\Git\Release
 * @author Sebastian Seidelmann <sebastian.seidelmann@googlemail.com>
 */

namespace Seidelmann\DevUtils\Commands\Git\Changelog;

use Seidelmann\DevUtils\Command;
use Seidelmann\DevUtils\Model\Version;
use Symfony\Component\Console\Input\InputArgument;
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
            ->setName('git:changelog:create')
            ->setDescription('Create the changelog')
            ->addArgument('release', InputArgument::REQUIRED, 'The release version number');
    }

    /**
     * Executes the command.
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $version = new Version($input->getArgument('release'));
        $last    = $this->getGitHelper()->getLastTag();

        $this->getChangelogHelper()->update($last, $version);
    }

}