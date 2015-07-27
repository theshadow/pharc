<?php
/**
 * Short description
 * 
 * Long Description
 * 
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 */

namespace Pharc\Project\Target\Composer;

use Pharc\Project\FileGroup\FileGroupHydratorTrait;
use Pharc\Project\Target\Composer;

/**
 * Class ComposerHydrator
 * @package Pharc\Project\Target\Composer
 */
class ComposerHydrator
{
    use FileGroupHydratorTrait;

    /**
     * @param Composer $composer
     * @param $data
     * @return Composer
     */
    public function hydrate(Composer $composer, $data)
    {
        $composer->setConfig($data['config']);

        if (isset($data['exclude'])) {
            $composer->setExcludes($data['exclude']);
        }

        if (isset($data['include-dev'])) {
            $composer->setIncludeDev($data['include-dev']);
        }

        if (isset($data['path'])) {
            $composer->setPaths($data['path']);
        }

        $this->getFileGroupHydrator()->hydrate($composer, $data);

        return $composer;
    }
}