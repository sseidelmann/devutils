<?php
/**
 * Class CreateCommand
 * @package Seidelmann\DevUtils\Commands\Phar\Bitbucket
 * @author Sebastian Seidelmann <sebastian.seidelmann@googlemail.com>
 */

namespace Seidelmann\DevUtils\Commands\Phar\Bitbucket;

use Seidelmann\DevUtils\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

/**
 * Class CreateCommand
 * @package Seidelmann\DevUtils\Commands\Phar\Bitbucket
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
            ->setName('phar:bitbucket:create')
            ->setDescription('Creates a new phar.');
    }

    /**
     * Executes the command.
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $git = $this->getGitHelper();

        $git->createFullRelease(array($this, 'additionalReleaseAction'));
    }

    public function additionalReleaseAction($tag)
    {
        /* @var $box \Seidelmann\DevUtils\Helper\BoxHelper */
        $box = $this->getHelperSet()->get('box');

        $box->ensureDirectory('build');
        $path = $box->build('build', false);
    }

}