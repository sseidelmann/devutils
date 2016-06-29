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
     * @var string
     */
    private $messageRaw;

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
     * Saves the commit type.
     * @var string
     */
    private $commitType = 'task';

    /**
     * Saves the ticket.
     * @var string
     */
    private $ticket = false;

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Returns the commit type.
     * @return string
     */
    public function getCommitType()
    {
        return $this->commitType;
    }

    /**
     * @return string|bool
     */
    public function getTicket()
    {
        return $this->ticket;
    }

    /**
     * Parses the message.
     * @return string
     */
    private function parseMessage()
    {
        if (preg_match('/\[([^\]]+)\].*/', $this->messageRaw, $matches)) {
            $this->messageRaw = str_replace(sprintf('[%s]', $matches[1]), '', $this->messageRaw);
            $this->commitType = strtolower($matches[1]);
        }

        if (preg_match('/\[([^\]]+)\].*/', $this->messageRaw, $matches)) {
            $this->messageRaw = str_replace(sprintf('[%s]', $matches[1]), '', $this->messageRaw);
            $this->ticket = strtolower($matches[1]);
        }

        return trim($this->messageRaw);
    }

    /**
     * @param string $message
     * @return Commit
     */
    public function setMessage($message)
    {
        $this->messageRaw = $message;
        $this->message    = $this->parseMessage();
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