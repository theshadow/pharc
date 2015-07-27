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
use Pharc\Project\Target\TargetHydrator;

/**
 * Class ProjectHydrator
 * @package Pharc\Project
 */
class ProjectHydrator
{
    /**
     * @var TargetHydrator
     */
    protected $targetHydrator;

    public function hydrate(Project $project, $data)
    {
        foreach ($data['targets'] as $name => $targetData) {
            $target = $this->getTargetHydrator()->hydrate(new Target(), $targetData);
            $project->addTarget($name, $target);
        }
        return $project;
    }

    /**
     * @return TargetHydrator
     */
    public function getTargetHydrator()
    {
        if (is_null($this->targetHydrator)) {
            $this->targetHydrator = new TargetHydrator();
        }
        return $this->targetHydrator;
    }

    /**
     * @param TargetHydrator $targetHydrator
     * @return static
     */
    public function setTargetHydrator($targetHydrator)
    {
        $this->targetHydrator = $targetHydrator;
        return $this;
    }
}