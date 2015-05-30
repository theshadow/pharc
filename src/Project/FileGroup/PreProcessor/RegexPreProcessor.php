<?php
/**
 * Short description
 * 
 * Long Description
 * 
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 */

namespace Pharc\Project\FileGroup\PreProcessor;

use Pharc\Project\FileGroup\PreProcessorInterface;

/**
 * Class RegexPreProcessor
 * @package Pharc\Project\FileSet\PreProcessor
 */
class RegexPreProcessor implements PreProcessorInterface
{
    /**
     * @var string
     */
    protected $find;

    /**
     * @var string
     */
    protected $replace;

    /**
     * @param $contents
     * @return mixed
     */
    public function process($contents)
    {
        return preg_replace($this->getFind(), $this->getReplace(), $contents);
    }

    /**
     * @return string
     */
    public function getFind()
    {
        return $this->find;
    }

    /**
     * @param string $find
     * @return static
     */
    public function setFind($find)
    {
        $this->find = $find;
        return $this;
    }

    /**
     * @return string
     */
    public function getReplace()
    {
        return $this->replace;
    }

    /**
     * @param string $replace
     * @return static
     */
    public function setReplace($replace)
    {
        $this->replace = $replace;
        return $this;
    }
}