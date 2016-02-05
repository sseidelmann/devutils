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
class GitHelper extends Helper
{
    /**
     * Saves the working directory.
     * @var string
     */
    private $workingDirectory;

    /**
     * Returns the canonical name of this helper.
     *
     * @return string The canonical name
     */
    public function getName()
    {
        return 'git';
    }

    /**
     * Checkout a branch
     * @param string $branch
     * @return GitHelper
     */
    public function co($branch)
    {
        $this->execute('checkout ' . $branch);
        return $this;
    }

    /**
     * Add a file
     * @param string $branch
     * @return GitHelper
     */
    public function add($file)
    {
        $this->execute('add ' . $file);
        return $this;
    }

    /**
     * Commits files.
     * @param $pattern
     * @param $message
     * @return GitHelper
     */
    public function commit($pattern, $message)
    {
        $this->execute(sprintf('commit %s -m "%s"', $pattern, $message));
        return $this;
    }

    /**
     * Pulls the current branch
     * @return GitHelper
     */
    public function pull()
    {
        $this->execute('pull');
        return $this;
    }

    /**
     * Pushs a branch.
     * @param string $branch
     * @return GitHelper
     */
    public function push($branch)
    {
        $this->co($branch);
        $this->execute('push origin ' . $branch);
        return $this;
    }

    /**
     * Pushs the tags
     * @return GitHelper
     */
    public function pushTags()
    {
        $this->execute('push --tags');
        return $this;
    }

    /**
     * Fetch git information.
     * @return GitHelper
     */
    public function fetch()
    {
        $this->execute('fetch');
        return $this;
    }

    /**
     * Starts a git flow release.
     * @return GitHelper
     */
    public function flowReleaseStart()
    {
        $this->execute('flow release start');
        return $this;
    }

    /**
     * Finsish a git flow release.
     * @param string $version
     * @return GitHelper
     */
    public function flowReleaseFinish($version)
    {
        $this->execute(sprintf('flow release finish -m "%s" %s', $version, $version));
        return $this;
    }

    /**
     * Deletes a tag.
     * @param string $tag
     * @return GitHelper
     */
    public function deleteTag($tag)
    {
        $this->execute('tag -d ' . $tag);
        $this->execute('push origin :refs/tags/' . $tag);
        return $this;
    }

    /**
     * Returns the tags.
     * @return array
     */
    public function getTags()
    {
        $tags = $this->execute('tag');
        usort($tags, 'version_compare');
        return $tags;
    }

    /**
     * Returns the last tag.
     * @return string
     */
    public function getLastTag()
    {
        $tags = $this->getTags();
        return end($tags);
    }

    /**
     * Returns the commits.
     * @param string $current
     * @return array
     */
    public function getChangelogLines($current = null, $format = '%s')
    {
        if (null === $current) {
            $current = $this->getLastTag();
        }

        return $this->execute(sprintf(
            'log %s...HEAD --pretty=format:"%s" --reverse | grep -v Merge',
            $current,
            $format
        ));
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
     * Checks if the project is a git project.
     * @return bool
     */
    public function available()
    {
        return is_dir($this->workingDirectory . '.git');
    }

    /**
     * Executes a git command.
     * @param string $command
     * @return array
     */
    private function execute($command)
    {
        $return = array();
        $command = sprintf('cd %s && git %s', $this->workingDirectory, $command);

        exec($command, $return);

        return $return;
    }
}