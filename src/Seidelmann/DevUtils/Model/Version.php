<?php

namespace Seidelmann\DevUtils\Model;
use Seidelmann\DevUtils\Exception\VersionNotSemanticVersioningException;

/**
 * Class Version
 * @package Seidelmann\DevUtils\Model
 * @author Sebastian Seidelmann <sebastian.seidelmann@wfp2.com>, wfp:2 GmbH & Co. KG
 */
class Version
{
    /**
     * Defines the mask for filter the "v*" versions.
     * @var string
     */
    const MASK_DIRTY_VERSION = '/^[v= ]*%s$/';

    /**
     * The pattern.
     * @var string
     */
    const PATTERN_VERSION = '(([0-9]+)(\\.([0-9]+)(\\.([0-9]+)(-([0-9]+))?(-?([a-zA-Z-+][a-zA-Z0-9\\.\\-:]*)?)?)?)?)';

    /**
     * Saves the raw version.
     * @var string
     */
    private $version;

    /**
     * Saves the major
     * @var int
     */
    private $major = 0;

    /**
     * Saves the minor
     * @var int
     */
    private $minor = 0;

    /**
     * Saves the patch.
     * @var int
     */
    private $patch = 0;

    /**
     * Saves the build
     * @var string
     */
    private $build = '';

    /**
     * Saves the PR Tag.
     * @var string
     */
    private $prtag = '';

    /**
     * Constructs the version
     * @param $version
     */
    public function __construct($version)
    {
        $this->version = $version;
        $this->parse();
    }

    /**
     * Creates a string.
     * @return string
     */
    public function __toString()
    {
        return sprintf(
            '%s.%s.%s%s%s',
            $this->major,
            $this->minor,
            $this->patch,
            strlen($this->build) === 0 ? '' : '-' . $this->build,
            strlen($this->prtag) === 0 ? '' : '-' . $this->prtag
        );
    }

    /**
     * Validates the version
     * @throws VersionNotSemanticVersioningException
     */
    private function parse()
    {
        $expression = sprintf(self::MASK_DIRTY_VERSION, self::PATTERN_VERSION);

        if (!preg_match($expression, $this->version, $matches)) {
            throw new VersionNotSemanticVersioningException(sprintf(
                '"%s" is not a valid version for semantic versioning 2.0.0 (http://semver.org/)',
                $this->version)
            );
        }

        $this->major = $matches[2];
        $this->minor = $matches[4];
        $this->patch = $matches[6];
        $this->build = isset($matches[8]) ? $matches[8] : '';
        $this->prtag = isset($matches[10]) ? $matches[10] : '';
    }

    /**
     * @return int
     */
    public function getMajor()
    {
        return $this->major;
    }

    /**
     * @return int
     */
    public function getMinor()
    {
        return $this->minor;
    }

    /**
     * @return int
     */
    public function getPatch()
    {
        return $this->patch;
    }

    /**
     * @return string
     */
    public function getBuild()
    {
        return $this->build;
    }

    /**
     * @return string
     */
    public function getPrtag()
    {
        return $this->prtag;
    }


}