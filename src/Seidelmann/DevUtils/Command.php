<?php
/**
 * Class Command
 * @package Seidelmann\DevUtils
 * @author Sebastian Seidelmann <sebastian.seidelmann@googlemail.com>
 */

namespace Seidelmann\DevUtils;

use Seidelmann\DevUtils\Helper\ChangelogHelper;
use Seidelmann\DevUtils\Helper\GitHelper;
use \Symfony\Component\Console\Command\Command as BaseCommand;

/**
 * Class Command
 * @package Seidelmann\DevUtils
 * @author Sebastian Seidelmann <sebastian.seidelmann@googlemail.com>
 */
class Command extends BaseCommand
{
    /**
     * Returns the git helper.
     * @return GitHelper
     */
    protected function getGitHelper()
    {
        /* @var $helper GitHelper */
        $helper = $this->getHelper('git');
        $helper->setWorkingDirectory($this->getWorkingDirectory());

        return $helper;
    }

    /**
     * Returns the changelog helper.
     * @return ChangelogHelper
     */
    protected function getChangelogHelper()
    {
        /* @var $helper ChangelogHelper */
        $helper = $this->getHelper('changelog');
        $helper->setWorkingDirectory($this->getWorkingDirectory());

        return $helper;
    }

    /**
     * Returns the working directory.
     * @return string
     */
    protected function getWorkingDirectory()
    {
        return getcwd() . DIRECTORY_SEPARATOR;
    }
}