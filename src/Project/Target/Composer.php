<?php
/**
 * Short description
 * 
 * Long Description
 * 
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 */

namespace Pharc\Project\Target;

use Pharc\Project\FileGroupInterface;
use Pharc\Project\FileGroupTrait;

/**
 * Class Composer
 * @package Pharc\Project\Target
 */
class Composer implements FileGroupInterface
{
    use FileGroupTrait;

    /**
     * @var string
     */
    protected $config = 'composer.json';

    /**
     * @var bool
     */
    protected $includeDev = false;

    /**
     * @return string
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param string $config
     * @return static
     */
    public function setConfig($config)
    {
        $this->config = $config;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isIncludeDev()
    {
        return $this->includeDev;
    }

    /**
     * @param boolean $includeDev
     * @return static
     */
    public function setIncludeDev($includeDev)
    {
        $this->includeDev = $includeDev;
        return $this;
    }
}