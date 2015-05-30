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
 * Interface FileSetInterface
 * @package Pharc\Project
 */
interface FileGroupInterface
{
    /**
     * @return boolean
     */
    public function isIgnoreVcs();

    /**
     * @param boolean $ignoreVcs
     * @return static
     */
    public function setIgnoreVcs($ignoreVcs);

    /**
     * @return boolean
     */
    public function isStrip();

    /**
     * @param boolean $strip
     * @return static
     */
    public function setStrip($strip);

    /**
     * @return null|string
     */
    public function getSpacer();

    /**
     * @param null|string $spacer
     * @return static
     */
    public function setSpacer($spacer);

    /**
     * @return \string[]
     */
    public function getExcludes();

    /**
     * @param \string[] $excludes
     * @return static
     */
    public function setExcludes($excludes);

    /**
     * @return \string[]
     */
    public function getNames();

    /**
     * @param \string[] $names
     * @return static
     */
    public function setNames($names);

    /**
     * @return PreProcessorInterface[]
     */
    public function getPreprocessors();

    /**
     * @param PreProcessorInterface[] $preprocessors
     * @return static
     */
    public function setPreprocessors(array $preprocessors);

    /**
     * @param string $name
     * @param PreProcessorInterface $preprocessor
     * @return static
     */
    public function addPreprocessor($name, PreProcessorInterface $preprocessor);

    /**
     * @param array $paths
     * @return static
     */
    public function setPaths(array $paths);

    /**
     * @return array
     */
    public function getPaths();

    /**
     * @return array
     */
    public function getExcludedNames();

    /**
     * @param array $excludedNames
     * @return static
     */
    public function setExcludedNames(array $excludedNames);
}