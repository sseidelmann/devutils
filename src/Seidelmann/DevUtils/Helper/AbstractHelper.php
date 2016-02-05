<?php
/**
 * Class AbstractHelper
 * @package Seidelmann\DevUtils\Helper
 * @author Sebastian Seidelmann <sebastian.seidelmann@googlemail.com>
 */

namespace Seidelmann\DevUtils\Helper;

use Symfony\Component\Console\Helper\Helper;

/**
 * Class AbstractHelper
 * @package Seidelmann\DevUtils\Helper
 * @author Sebastian Seidelmann <sebastian.seidelmann@googlemail.com>
 */
abstract class AbstractHelper extends Helper
{
    /**
     * Saves the working directory.
     * @var string
     */
    private $workingDirectory;

    /**
     * Saves the command prefix.
     * @var string
     */
    private $commandPrefix;

    /**
     * Initialize the helper.
     * @return void
     */
    public function init()
    {

    }

    /**
     * Sets the working directory.
     * @param string $workingDirectory
     * @return void
     */
    public function setWorkingDirectory($workingDirectory)
    {
        $this->workingDirectory = $workingDirectory;
    }

    /**
     * Sets the command prefix.
     * @param string $prefix
     */
    public function setCommandPrefix($prefix)
    {
        $this->commandPrefix = $prefix;
    }

    /**
     * Returns the working directory.
     * @return string
     */
    protected function getWorkingDirectory()
    {
        return $this->workingDirectory;
    }

    /**
     * Executes a command.
     * @param string $command
     * @return array
     */
    protected function execute($command)
    {
        $return = array();
        if ($this->commandPrefix) {
            $command = $this->commandPrefix . ' ' . $command;
        }
        $command = sprintf('cd %s && %s', $this->getWorkingDirectory(), $command);

        exec($command, $return);

        return $return;
    }
}