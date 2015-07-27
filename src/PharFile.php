<?php
/**
 * PharFile is a wrapper around the PHP Phar archive.
 * 
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 */

namespace Pharc;

use Phar;

use Pharc\Project\FileGroup\PreProcessorInterface;
use Pharc\Project\FileGroupInterface;
use Pharc\Project\Target\StubInterface;
use Symfony\Component\Finder\Finder;

use Pharc\Project\Target\FileGroup;

/**
 * Class PharFile
 * @package Pharc
 */
class PharFile
{
    /**
     * @var string
     */
    protected $filename;

    /**
     * @var Phar
     */
    protected $phar;

    /**
     * @var integer
     */
    protected $signatureMethod;

    /**
     * @var string
     */
    protected $cwd;

    /**
     * @param $filename
     * @param string $cwd
     * @param int $signatureMethod
     */
    public function __construct($filename, $cwd, $signatureMethod = Phar::SHA1)
    {
        $this->setFilename($filename)
            ->setSignatureMethod($signatureMethod)
            ->setCwd($cwd);
    }

    /**
     *
     */
    public function __destruct()
    {
        unset($this->phar);
    }

    /**
     * @param string $filename
     * @return static
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
        return $this;
    }

    /**
     * @param Phar $phar
     * @return static
     */
    public function setPhar($phar)
    {
        $this->phar = $phar;
        return $this;
    }

    /**
     * @param int $signatureMethod
     * @return static
     */
    public function setSignatureMethod($signatureMethod)
    {
        $this->signatureMethod = $signatureMethod;
        return $this;
    }

    /**
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @return int
     */
    public function getSignatureMethod()
    {
        return $this->signatureMethod;
    }

    /**
     * @return string
     */
    public function getCwd()
    {
        return $this->cwd;
    }

    /**
     * @param string $cwd
     * @return static
     */
    public function setCwd($cwd)
    {
        $this->cwd = $cwd;
        return $this;
    }

    /**
     * @return $this
     */
    public function startBuffering()
    {
        $this->getPhar()->startBuffering();
        return $this;
    }

    /**
     * @return $this
     */
    public function stopBuffering()
    {
        $this->getPhar()->stopBuffering();
        return $this;
    }

    /**
     * @return Phar
     */
    public function getPhar()
    {
        if (is_null($this->phar))
        {
            $this->phar = new Phar($this->getFilename(), 0, $this->getFilename());
            $this->phar->setSignatureAlgorithm($this->getSignatureMethod());
        }
        return $this->phar;
    }

    /**
     * @param FileGroupInterface $fileGroup
     */
    public function addFileGroup(FileGroupInterface $fileGroup)
    {
        $finder = new Finder();
        $finder->files()
            ->ignoreUnreadableDirs(true)
            ->ignoreVCS($fileGroup->isIgnoreVcs())
            ->sort(function (\SplFileInfo $a, \SplFileInfo $b) {
                return strcmp(
                    strtr($a->getRealPath(), '\\', '/'),
                    strtr($b->getRealPath(), '\\', '/')
                );
            });

        $finder->files()->in($fileGroup->getPaths());

        foreach ($fileGroup->getNames() as $name) {
            $finder->files()->name($name);
        }

        foreach ($fileGroup->getExcludedNames() as $name) {
            $finder->files()->notName($name);
        }

        foreach ($fileGroup->getExcludes() as $name) {
            $finder->files()->exclude($name);
        }

        foreach ($finder as $file) {
            $this->addFile($file, $fileGroup->getPreprocessors(), $fileGroup->isStrip(), $fileGroup->getSpacer());
        }
    }

    /**
     * @param \SplFileInfo $file
     * @param PreProcessorInterface[] $preProcessors
     * @param bool $strip
     * @param string $spacer
     */
    public function addFile(\SplFileInfo $file, array $preProcessors = [], $strip = true, $spacer = '')
    {
        $path = strtr(
            str_replace(
                $this->cwd . DIRECTORY_SEPARATOR,
                '',
                $file->getRealPath()
            ),
            '\\',
            '/'
        );

        $contents = file_get_contents($file);
        if ($contents === false) {
            throw new \LogicException(
                'Unable to read the contents of ' . $file->getRealPath()
            );
        }

        foreach ($preProcessors as $preProcessor) {
            $contents = $preProcessor->process($contents);
        }

        if ($strip) {
            $contents = $this->stripWhitespace($contents);
        }

        echo $path, PHP_EOL;

        $contents = $spacer . $contents . $spacer;

        $this->getPhar()->addFromString($path, $contents);
    }

    /**
     * @param StubInterface $stub
     * @return static
     */
    public function setStub(StubInterface $stub)
    {
        $this->getPhar()->setStub($stub->retrieve());
        return $this;
    }

    /**
     * Removes whitespace from a PHP source string while preserving line numbers.
     *
     * @param  string $source A PHP string
     * @return string The PHP string with the whitespace removed
     */
    private function stripWhitespace($source)
    {
        if (!function_exists('token_get_all')) {
            return $source;
        }
        $output = '';
        foreach (token_get_all($source) as $token) {
            if (is_string($token)) {
                $output .= $token;
            } elseif (in_array($token[0], array(T_COMMENT, T_DOC_COMMENT))) {
                $output .= str_repeat("\n", substr_count($token[1], "\n"));
            } elseif (T_WHITESPACE === $token[0]) {
                // reduce wide spaces
                $whitespace = preg_replace('{[ \t]+}', ' ', $token[1]);
                // normalize newlines to \n
                $whitespace = preg_replace('{(?:\r\n|\r|\n)}', "\n", $whitespace);
                // trim leading spaces
                $whitespace = preg_replace('{\n +}', "\n", $whitespace);
                $output .= $whitespace;
            } else {
                $output .= $token[1];
            }
        }
        return $output;
    }
}