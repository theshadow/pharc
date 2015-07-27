<?php
/**
 * Short description
 * 
 * Long Description
 * 
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 */

namespace Pharc\Project\FileGroup;

/**
 * Interface PreProcessorInterface
 * @package Pharc\Project\FileSet
 */
interface PreProcessorInterface
{
    /**
     * @param $contents
     * @return mixed
     */
    public function process($contents);
}