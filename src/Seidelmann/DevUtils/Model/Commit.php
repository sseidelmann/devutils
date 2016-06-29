<?php
/**
 * Class Application
 * @package Seidelmann\DevUtils
 * @author Sebastian Seidelmann <sebastian.seidelmann@googlemail.com>
 */

namespace Seidelmann\DevUtils\Model;

use Seidelmann\DevUtils\Model\Commit\Author;

class Commit
{
    /**
     * @var string
     */
    private $message;

    /**
     * @var Author
     */
    private $author;

    /**
     * @var string
     */
    private $hash;

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     * @return Commit
     */
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return Author
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param Author $author
     * @return Commit
     */
    public function setAuthor($author)
    {
        $this->author = $author;
        return $this;
    }

    /**
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * @param string $hash
     * @return Commit
     */
    public function setHash($hash)
    {
        $this->hash = $hash;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     * @return Commit
     */
    public function setDate(\DateTime $date)
    {
        $this->date = $date;
        return $this;
    }




}