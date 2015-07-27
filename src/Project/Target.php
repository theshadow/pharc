<?php
/**
 * Short description
 * 
 * Long Description
 * 
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 */

namespace Pharc\Project;
use Pharc\Project\Target\Composer;
use Pharc\Project\Target\FileGroup;
use Pharc\Project\Target\StubInterface;
use Pharc\Project\Target\Dependency\DependencyManagerInterface;

/**
 * Class Target
 * @package Pharc\Compiler
 */
class Target
{
    /**
     * @var array
     */
    public static $SIGNATURE_METHODS = [
        'SHA1' => \Phar::SHA1,
        'SHA256' => \Phar::SHA256,
        'SHA512' => \Phar::SHA512,
        'NONE'   => null,
    ];

    /**
     * @var string
     */
    protected $phar;

    /**
     * @var StubInterface
     */
    protected $stub;

    /**
     * @var string
     */
    protected $license;

    /**
     * @var string
     */
    protected $signatureMethod;

    /**
     * @var array
     */
    protected $dependencyManager;

    /**
     * @var mixed
     */
    protected $files;

    /**
     * @var string
     */
    protected $bin;

    /**
     * @return string
     */
    public function getPhar()
    {
        return $this->phar;
    }

    /**
     * @param string $phar
     * @return static
     */
    public function setPhar($phar)
    {
        $this->phar = $phar;
        return $this;
    }

    /**
     * @return StubInterface
     */
    public function getStub()
    {
        return $this->stub;
    }

    /**
     * @param StubInterface $stub
     * @return static
     */
    public function setStub(StubInterface $stub)
    {
        $this->stub = $stub;
        return $this;
    }

    /**
     * @return string
     */
    public function getLicense()
    {
        return $this->license;
    }

    /**
     * @param string $license
     * @return static
     */
    public function setLicense($license)
    {
        $this->license = $license;
        return $this;
    }

    /**
     * @return string
     */
    public function getSignatureMethod()
    {
        return $this->signatureMethod;
    }

    /**
     * @param string $signatureMethod
     * @return static
     */
    public function setSignatureMethod($signatureMethod)
    {
        $this->signatureMethod = $signatureMethod;
        return $this;
    }

    /**
     * @return DependencyManagerInterface
     */
    public function getDependencyManager()
    {
        return $this->dependencyManager;
    }

    /**
     * @param DependencyManagerInterface $dependencyManager
     * @return static
     */
    public function setDependencyManager(DependencyManagerInterface $dependencyManager)
    {
        $this->dependencyManager = $dependencyManager;
        return $this;
    }

    /**
     * @return FileGroup[]
     */
    public function getFileGroups()
    {
        return $this->files;
    }

    /**
     * @param FileGroup[] $files
     * @return static
     */
    public function setFileGroups(array $files)
    {
        $this->files = $files;
        return $this;
    }

    /**
     * @param $name
     * @param FileGroup $files
     */
    public function addFiles($name, FileGroup $files)
    {
        $this->files[$name] = $files;
    }

    /**
     * @return string
     */
    public function getBin()
    {
        return $this->bin;
    }

    /**
     * @param string $bin
     * @return static
     */
    public function setBin($bin)
    {
        $this->bin = $bin;
        return $this;
    }
}