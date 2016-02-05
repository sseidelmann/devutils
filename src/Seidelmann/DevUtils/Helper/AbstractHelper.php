<?php
/**
 * Class AbstractHelper
 * @package Seidelmann\DevUtils\Helper
 * @author Sebastian Seidelmann <sebastian.seidelmann@googlemail.com>
 */

namespace Seidelmann\DevUtils\Helper;

use Symfony\Component\Console\Helper\Helper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

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
     * Saves the input.
     * @var InputInterface
     */
    private $input;

    /**
     * Saves the output
     * @var OutputInterface
     */
    private $output;

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
     * Sets the io.
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @return void
     */
    public function setIO(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;
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
    protected function execute($command, $debug = false)
    {
        $return = array();
        if ($this->commandPrefix) {
            $command = $this->commandPrefix . ' ' . $command;
        }
        $command = sprintf('cd %s && %s', $this->getWorkingDirectory(), $command);

        if ($debug) {
            echo "> " . $command . PHP_EOL;
        }
        exec($command, $return);

        return $return;
    }

    /**
     * Returns the input.
     * @return InputInterface
     */
    protected function getInput()
    {
        return $this->input;
    }

    /**
     * Returns the output.
     * @return OutputInterface
     */
    protected function getOutput()
    {
        return $this->output;
    }

    /**
     * Creates the directory if not existing.
     * @param string $path
     * @return string
     */
    public function ensureDirectory($path)
    {
        if (!is_dir($this->getWorkingDirectory() . $path)) {
            mkdir($this->getWorkingDirectory() . $path);
        }

        return $this->getWorkingDirectory() . $path;
    }
}