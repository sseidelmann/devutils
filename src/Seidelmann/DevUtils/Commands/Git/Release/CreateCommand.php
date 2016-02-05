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
        $output->writeln('create new release');


        if (!$this->getGitHelper()->available()) {
            throw new \Exception('Is not a git project.');
        }

        $git = $this->getGitHelper();


        $output->writeln('Update the project ...');

        $git->fetch()
            ->co('master')
            ->pull()
            ->co('develop')
            ->pull();


        /* @var $question \Symfony\Component\Console\Helper\QuestionHelper */
        $question = $this->getHelper('question');
        $tag      = $question->ask(
            $input,
            $output,
            new Question(sprintf('Please enter the new tag version (last: %s): ', $git->getLastTag()))
        );


        $git->flowReleaseStart();

        $output->writeln('Create the changelog ...');
        $changelog     = $this->getChangelogHelper();
        $changelogFile = $changelog->getFilePath(true);

        $changelog->create($tag, $git->getChangelogLines());

        $git
            ->add($changelogFile)
            ->commit(
                $changelogFile,
                sprintf('[TASK][RELEASE %s] ADDED the current release in changelog file', $tag)
            );


        $git->flowReleaseFinish($tag);

        $git
            ->push('master')
            ->push('develop')
            ->pushTags();
    }

}