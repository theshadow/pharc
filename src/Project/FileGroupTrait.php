<?php
/**
 * Short description
 * 
 * Long Description
 * 
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 */

namespace Pharc\Project;
use Pharc\Project\FileGroup\PreProcessorInterface;

/**
 * Class FileSet
 * @package Pharc\Compiler\Target
 */
trait FileGroupTrait
{
    /**
     * @var bool
     */
    protected $ignoreVcs = true;
    /**
     * @var bool
     */
    protected $strip = true;
    /**
     * @var string|null
     */
    protected $spacer = '';
    /**
     * @var string[]
     */
    protected $excludes = [];
    /**
     * @var array
     */
    protected $excludedNames = [];
    /**
     * @var string[]
     */
    protected $names = [];
    /**
     * @var array
     */
    protected $preprocessors = [];

    /**
     * @var array
     */
    protected $paths = [];

    /**
     * @return boolean
     */
    public function isIgnoreVcs()
    {
        return $this->ignoreVcs;
    }

    /**
     * @param boolean $ignoreVcs
     * @return static
     */
    public function setIgnoreVcs($ignoreVcs)
    {
        $this->ignoreVcs = $ignoreVcs;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isStrip()
    {
        return $this->strip;
    }

    /**
     * @param boolean $strip
     * @return static
     */
    public function setStrip($strip)
    {
        $this->strip = $strip;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getSpacer()
    {
        return $this->spacer;
    }

    /**
     * @param null|string $spacer
     * @return static
     */
    public function setSpacer($spacer)
    {
        $this->spacer = $spacer;
        return $this;
    }

    /**
     * @return \string[]
     */
    public function getExcludes()
    {
        return $this->excludes;
    }

    /**
     * @param \string[] $excludes
     * @return static
     */
    public function setExcludes($excludes)
    {
        $this->excludes = $excludes;
        return $this;
    }

    /**
     * @return \string[]
     */
    public function getNames()
    {
        return $this->names;
    }

    /**
     * @param \string[] $names
     * @return static
     */
    public function setNames($names)
    {
        $this->names = $names;
        return $this;
    }

    /**
     * @return PreProcessorInterface[]
     */
    public function getPreprocessors()
    {
        return $this->preprocessors;
    }

    /**
     * @param PreProcessorInterface[] $preprocessors
     * @return static
     */
    public function setPreprocessors(array $preprocessors)
    {
        $this->preprocessors = $preprocessors;
        return $this;
    }

    /**
     * @param string $name
     * @param PreProcessorInterface $preprocessor
     * @return static
     */
    public function addPreprocessor($name, PreProcessorInterface $preprocessor)
    {
        $this->preprocessors[$name] = $preprocessor;
        return $this;
    }

    /**
     * @return array
     */
    public function getPaths()
    {
        return $this->paths;
    }

    /**
     * @param array $paths
     * @return static
     */
    public function setPaths(array $paths)
    {
        $this->paths = $paths;
        return $this;
    }

    /**
     * @return array
     */
    public function getExcludedNames()
    {
        return $this->excludedNames;
    }

    /**
     * @param array $excludedNames
     * @return static
     */
    public function setExcludedNames(array $excludedNames)
    {
        $this->excludedNames = $excludedNames;
        return $this;
    }
}