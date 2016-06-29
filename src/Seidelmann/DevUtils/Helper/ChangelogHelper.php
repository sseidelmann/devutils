<?php
/**
 * Class Changelog
 * @package sseidelmann\utils
 * @author Sebastian Seidelmann <sebastian.seidelmann@googlemail.com>
 */

namespace Seidelmann\DevUtils\Helper;

use Seidelmann\DevUtils\Model\Version;
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
     * Saves the pattern for the changelog file.
     * @var string
     */
    const PATTERN_CHANGELOG_FILE = 'CHANGELOG-%s.md';

    /**
     * Saes the changelog name.
     * @var string
     */
    private $changelogName = self::CHANGELOG_FILENAME;

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
     * Update the changelog
     * @param string  $oldVersion
     * @param Version $newVersion
     */
    public function update($oldVersion, Version $newVersion)
    {
        $releaseContent = [];

        $this->setChangelogName($newVersion);

        /* @var $git \Seidelmann\DevUtils\Helper\GitHelper */
        $git     = $this->getHelperSet()->get('git');
        $commits = $git->getCommits($oldVersion);

        $releaseContent[] = sprintf('* %s (%s)', (string) $newVersion, date('Y-m-d'));
        $releaseContent[] = '';

        foreach ($commits as $commit) {
            $releaseContent[] = str_replace('  ', ' ', sprintf(
                ' * %s %s %s (%s)',
                $commit->getCommitType(),
                $commit->getTicket(),
                $commit->getMessage(),
                $commit->getAuthor()->getName()
            ));
        }

        $header = $this->getChangelogHeader($newVersion);

        if (!$this->exists()) {
            $this->setChangelogContent($header);
        }

        $this->setChangelogContent(
            str_replace(
                $header,
                $header . PHP_EOL . PHP_EOL . implode(PHP_EOL, $releaseContent),
                $this->getChangelogContent()
            )
        );
    }

    /**
     * Changes the name for the changelog.
     * @param Version $version
     * @return void
     */
    private function setChangelogName(Version $version)
    {
        $this->changelogName = sprintf(
            self::PATTERN_CHANGELOG_FILE,
            sprintf('%s.%s', $version->getMajor(), $version->getMinor())
        );
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
            return $this->changelogName;
        }

        return $this->getWorkingDirectory() . $this->changelogName;
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
     * @param Version $version
     * @return string
     */
    private function getChangelogHeader(Version $version)
    {
        $header = <<<EOF
CHANGELOG for %s.%s.x
===================

This changelog references the relevant changes (bug and security fixes) done
in %s versions.

This project adheres to [Semantic Versioning](http://semver.org/).
Changelog created by [devutils](https://github.com/sseidelmann/devutils).
EOF;

        return sprintf(
            $header,
            $version->getMajor(),
            $version->getMinor(),
            $version->getMinor() == 0 ?
                sprintf('%s.x major', $version->getMajor()) :
                sprintf('%s.%s minor', $version->getMajor(), $version->getMinor())
        );
    }
}