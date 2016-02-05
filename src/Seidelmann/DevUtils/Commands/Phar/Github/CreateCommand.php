<?php
/**
 * Class CreateCommand
 * @package Seidelmann\DevUtils\Commands\Phar\Github
 * @author Sebastian Seidelmann <sebastian.seidelmann@googlemail.com>
 */

namespace Seidelmann\DevUtils\Commands\Phar\Github;

use Seidelmann\DevUtils\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

/**
 * Class CreateCommand
 * @package Seidelmann\DevUtils\Commands\Phar\Github
 * @author Sebastian Seidelmann <sebastian.seidelmann@googlemail.com>
 */
class CreateCommand extends \Seidelmann\DevUtils\Commands\Phar\Bitbucket\CreateCommand
{
    /**
     * Configures the current command.
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('phar:github:create')
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
        /* @var $box \Seidelmann\DevUtils\Helper\BoxHelper */
        $box = $this->getHelperSet()->get('box');

        /* @var $git \Seidelmann\DevUtils\Helper\GitHelper */
        $git = $this->getHelperSet()->get('git');

        $releaseBranch = $git->getCurrentBranch();

        $box->ensureDirectory('download');
        $path = $box->build('download', false);

        $git
            ->fetch()
            ->co('gh-pages')
            ->add($box->getRelativePath($path))
            ->commit($box->getRelativePath($path), '[TASK] ADDED the phar')
            ->push('gh-pages')
            ->co($releaseBranch);
    }
}