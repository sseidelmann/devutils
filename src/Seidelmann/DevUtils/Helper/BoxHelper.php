<?php
/**
 * Created by PhpStorm.
 * User: sebastianseidelmann
 * Date: 05.02.16
 * Time: 10:28
 */

namespace Seidelmann\DevUtils\Helper;
use Symfony\Component\Console\Helper\Helper;
use Symfony\Component\Process\ProcessBuilder;

/**
 * Class GitHelper
 * @package Seidelmann\DevUtils\Helper
 * @author Sebastian Seidelmann <sebastian.seidelmann@googlemail.com>
 */
class GitHelper extends AbstractHelper
{
    /**
     * Returns the canonical name of this helper.
     *
     * @return string The canonical name
     */
    public function getName()
    {
        return 'git';
    }

    public function build()
    {
        if (file_exists($this->getWorkingDirectory() . 'box.json')) {
            // $this->execute()
        }
    }
}