<?php
/**
 * Short description
 * 
 * Long Description
 * 
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 */

namespace Pharc\Project;

use Pharc\Project;

/**
 * Class ProjectFileParser
 * @package Pharc\Compiler
 */
class BuildFileParser
{
    /**
     * @param array $data
     * @return Project
     */
    public function parse(array $data)
    {
        $projectHydrator = new ProjectHydrator();
        $project = $projectHydrator->hydrate(new Project(), $data);
        return $project;
    }
}