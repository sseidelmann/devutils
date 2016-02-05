<?php
/**
 * Class Application
 * @package Seidelmann\DevUtils
 * @author Sebastian Seidelmann <sebastian.seidelmann@googlemail.com>
 */

namespace Seidelmann\DevUtils;

use Seidelmann\DevUtils\Helper\AbstractHelper;
use Symfony\Component\Console\Application as ConsoleApplication;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class Application
 * @package Seidelmann\DevUtils
 * @author Sebastian Seidelmann <sebastian.seidelmann@googlemail.com>
 */
class Application extends ConsoleApplication
{
    /**
     * Runs the current application.
     *
     * @param InputInterface $input An Input instance
     * @param OutputInterface $output An Output instance
     *
     * @return int 0 if everything went fine, or an error code
     *
     * @throws \Exception When doRun returns Exception
     */
    public function run(InputInterface $input = null, OutputInterface $output = null)
    {
        foreach ($this->getHelperSet()->getIterator() as $helper) {
            if ($helper instanceof AbstractHelper) {
                $helper->setWorkingDirectory(getcwd() . DIRECTORY_SEPARATOR);
                $helper->init();
            }
        }

        return parent::run($input, $output);
    }

}