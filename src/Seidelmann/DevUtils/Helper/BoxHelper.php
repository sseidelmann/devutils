<?php
/**
 * Class BoxHelper
 * @package Seidelmann\DevUtils\Helper
 * @author Sebastian Seidelmann <sebastian.seidelmann@googlemail.com>
 */

namespace Seidelmann\DevUtils\Helper;

/**
 * Class BoxHelper
 * @package Seidelmann\DevUtils\Helper
 * @author Sebastian Seidelmann <sebastian.seidelmann@googlemail.com>
 */
class BoxHelper extends AbstractHelper
{
    /**
     * Returns the canonical name of this helper.
     *
     * @return string The canonical name
     */
    public function getName()
    {
        return 'box';
    }

    /**
     * Builds the box.
     * @param string $buildDirectory
     * @param bool   $withExtension
     * @return string
     */
    public function build($buildDirectory = null, $withExtension = true)
    {
        if (file_exists($this->getBoxFilePath())) {
            $this->execute('box build');

            $boxConfiguration = json_decode(file_get_contents($this->getBoxFilePath()), true);
            $output           = $boxConfiguration['output'];
            $phar             = $output;

            if (!$withExtension) {
                $phar = current(explode('.', $phar));
            }

            if (null !== $buildDirectory) {
                $phar = trim($buildDirectory, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $phar;
            }

            if ($phar != $output) {
                $command = sprintf('mv %s %s', $output, $phar);
                $this->execute($command);
            }

            return $this->getWorkingDirectory() . $phar;
        }

        return false;
    }

    /**
     * Returns the relative path.
     * @param string $phar
     * @return string
     */
    public function getRelativePath($phar)
    {
        return str_replace($this->getWorkingDirectory(), '', $phar);
    }

    /**
     * Returns the file path to box file.
     * @return string
     */
    private function getBoxFilePath()
    {
        return $this->getWorkingDirectory() . 'box.json';
    }
}