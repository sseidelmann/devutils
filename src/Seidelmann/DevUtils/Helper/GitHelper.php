<?php
/**
 * Created by PhpStorm.
 * User: sebastianseidelmann
 * Date: 05.02.16
 * Time: 10:28
 */

namespace Seidelmann\DevUtils\Helper;
use Seidelmann\DevUtils\Model\Commit;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

/**
 * Class GitHelper
 * @package Seidelmann\DevUtils\Helper
 * @author Sebastian Seidelmann <sebastian.seidelmann@googlemail.com>
 */
class GitHelper extends AbstractHelper
{
    /**
     * Initialize the helper.
     * @return void
     */
    public function init()
    {
        $this->setCommandPrefix('git');

        if (!$this->available()) {
            // throw new \Exception(sprintf('%s is not a git project', $this->getWorkingDirectory()));
        }
    }

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
     * Returns the current branch.
     * @return string
     */
    public function getCurrentBranch()
    {
        return current($this->execute('rev-parse --abbrev-ref HEAD'));
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
    public function flowReleaseStart($version)
    {
        $this->execute('flow release start ' . $version);
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
     * @deprecated
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
     * Returns the log.
     * @param string $from
     * @param string $to
     * @return Commit[]
     */
    public function getCommits($from = null, $to = 'HEAD')
    {
        $defaultTZ = date_default_timezone_get();

        $formatMapping = [
            'subject'      => '%s',
            'hash'         => '%h',
            'author_email' => '%ae',
            'author_name'  => '%an',
            'author_date'  => '%aD'
        ];
        $delimiter = '!!!';

        $commits = [];
        if (null === $from) {
            $from = $this->getLastTag();
        }

        $log = $this->execute(sprintf(
            'log %s...%s --pretty=format:"%s" --reverse | grep -v Merge',
            $from,
            $to,
            implode($delimiter, array_values($formatMapping))
        ));

        foreach ($log as $line) {
            $commitValues = array_combine(array_keys($formatMapping), explode('!!!', $line));
            $author = new Commit\Author();
            $author
                ->setEmail($commitValues['author_email'])
                ->setName($commitValues['author_name'])
            ;

            $date = new \DateTime();
            $date->setTimestamp(strtotime($commitValues['author_date']));
            $date->setTimezone(new \DateTimeZone($defaultTZ));

            $commit = new Commit();
            $commit
                ->setMessage($commitValues['subject'])
                ->setHash($commitValues['hash'])
                ->setAuthor($author)
                ->setDate($date)
            ;

            $commits[] = $commit;
        }

        usort($commits, function (Commit $a, Commit $b) {
            return ($a->getDate() < $b->getDate()) ? 1 : -1;
        });

        return $commits;
    }

    /**
     * Checks if the project is a git project.
     * @return bool
     */
    public function available()
    {
        return is_dir($this->getWorkingDirectory() . '.git');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param $additionalAction
     */
    public function createFullRelease($additionalAction = null)
    {
        /* @var $question \Symfony\Component\Console\Helper\QuestionHelper */
        $question = $this->getHelperSet()->get('question');

        $this->fetch()
            ->co('master')
            ->pull()
            ->co('develop')
            ->pull();

        $lastTag = $this->getLastTag();

        $tag = $question->ask(
            $this->getInput(),
            $this->getOutput(),
            new Question(sprintf('Please enter the new tag version (last: %s): ', $lastTag))
        );

        $this->flowReleaseStart($tag);

        if (strlen($lastTag) > 0) {
            $this->createChangelog($tag);
        }

        if (null !== $additionalAction && (is_array($additionalAction) && method_exists($additionalAction[0], $additionalAction[1]))) {
            call_user_func($additionalAction, $tag);
        }

        $this->flowReleaseFinish($tag);

        $this
            ->push('master')
            ->push('develop')
            ->pushTags();
    }

    /**
     * Creates the changelog file.
     * @param string $tag
     * @return void
     */
    private function createChangelog($tag)
    {
        /* @var $changelog \Seidelmann\DevUtils\Helper\ChangelogHelper */
        $changelog     = $this->getHelperSet()->get('changelog');

        $this->getOutput()->writeln('Create the changelog ...');

        $changelogFile = $changelog->getFilePath(true);
        $changelog->create($tag, $this->getChangelogLines());

        $this
            ->add($changelogFile)
            ->commit(
                $changelogFile,
                sprintf('[TASK][RELEASE %s] ADDED the current release in changelog file', $tag)
            );
    }
}