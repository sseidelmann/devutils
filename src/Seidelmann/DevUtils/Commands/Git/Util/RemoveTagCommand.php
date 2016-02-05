<?php
/**
 * Class RemoveTagCommand
 * @package Seidelmann\DevUtils\Commands\Git\Util
 * @author Sebastian Seidelmann <sebastian.seidelmann@googlemail.com>
 */

namespace Seidelmann\DevUtils\Commands\Git\Util;

use Seidelmann\DevUtils\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

/**
 * Class RemoveTagCommand
 * @package Seidelmann\DevUtils\Commands\Git\Util
 * @author Sebastian Seidelmann <sebastian.seidelmann@googlemail.com>
 */
class RemoveTagCommand extends Command
{
    /**
     * Configures the current command.
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('git:util:removetag')
            ->setDescription('Removes some git tags.');
    }

    /**
     * Executes the command.
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!$this->getGitHelper()->available()) {
            throw new \Exception('Is not a git project.');
        }

        $git = $this->getGitHelper();

        do {
            $tags = $git->getTags();
            foreach ($tags as $id => $tagName) {
                $output->writeln(sprintf('[%s] %s', $id, $tagName));
            }

            /* @var $question \Symfony\Component\Console\Helper\QuestionHelper */
            $question = $this->getHelper('question');


            $tagQuestion = new Question('Which branch should deleted? ');
            $tagQuestion->setValidator(function ($answer) use ($tags) {
                if (!isset($tags[$answer])) {
                    throw new \Exception($answer . ' is not a valid value');
                }
            });
            $tagQuestion->setMaxAttempts(3);
            $answer = $question->ask($input, $output, $tagQuestion);


            $git->deleteTag($tags[$answer]);

            $deleteOtherTagQuestion = new Question('Delete another tag/branch? [y/n]: ');
            $deleteOtherTag         = $question->ask($input, $output, $deleteOtherTagQuestion);
        } while (strtolower(substr($deleteOtherTag, 0, 1)) == 'y');
    }

}