<?php
/**
 * Class Changelog
 * @package sseidelmann\utils
 * @author Sebastian Seidelmann <sebastian.seidelmann@googlemail.com>
 */

namespace Seidelmann\DevUtils\Helper;

use Symfony\Component\Console\Helper\Helper;

/**
 * Class Changelog
 * @package sseidelmann\utils
 * @author Sebastian Seidelmann <sebastian.seidelmann@googlemail.com>
 */
class ChangelogHelper extends AbstractHelper
{
    /**
     * Saves the changelog filename.
     * @var string
     */
    const CHANGELOG_FILENAME = 'CHANGELOG.md';

    /**
     * Returns the canonical name of this helper.
     *
     * @return string The canonical name
     */
    public function getName()
    {
        return 'changelog';
    }

    /**
     * @param $newVersion
     * @param $changesets
     */
    public function create($newVersion, array $changesets)
    {
        $release  = $this->formatChangelogRelease($newVersion, $changesets);

        if (!$this->exists()) {
            $this->setChangelogContent(
                $this->getChangelogHeader()
            );
        }

        $this->setChangelogContent(
            str_replace(
                $this->getChangelogHeader(),
                $this->getChangelogHeader() . PHP_EOL . PHP_EOL . $release,
                $this->getChangelogContent()
            )
        );
    }

    /**
     * Formats the current release.
     * @param string $releaseVersion
     * @param array $changeset
     * @return string
     */
    private function formatChangelogRelease($releaseVersion, array $changeset)
    {
        $release = array();
        $release[] = sprintf('## [%s] - [%s]', $releaseVersion, date('Y-m-d'));

        foreach ($changeset as $line) {
            $release[] = sprintf('- %s', $line);
        }

        return implode(PHP_EOL, $release);
    }

    /**
     * Checks wheather the file exists.
     * @return bool
     */
    private function exists()
    {
        return file_exists($this->getFilePath());
    }

    /**
     * Returns the filepath.
     * @return string
     */
    public function getFilePath($relative = false)
    {
        if ($relative) {
            return self::CHANGELOG_FILENAME;
        }

        return $this->getWorkingDirectory() . self::CHANGELOG_FILENAME;
    }

    /**
     * Returns the changelog content.
     * @return string
     */
    private function getChangelogContent()
    {
        return file_get_contents($this->getFilePath());
    }

    /**
     * Sets the changelog content.
     * @param string $content
     */
    private function setChangelogContent($content)
    {
        file_put_contents($this->getFilePath(), $content);
    }

    /**
     * Returns the changelog header.
     * @return string
     */
    private function getChangelogHeader()
    {
        return <<<EOF
# Change Log
All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).
EOF;
    }
}