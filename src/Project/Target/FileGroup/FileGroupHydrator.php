<?php
/**
 * Short description
 * 
 * Long Description
 * 
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 */

namespace Pharc\Project\Target\FileGroup;

use Pharc\Project\FileGroup\FileGroupHydratorTrait;
use Pharc\Project\Target\FileGroup;

/**
 * Class FilesHydrator
 * @package Pharc\Project\Target\Files
 */
class FileGroupHydrator
{
    use FileGroupHydratorTrait;

    /**
     * @param FileGroup $fileGroup
     * @param array $data
     * @return FileGroup
     */
    public function hydrate(FileGroup $fileGroup, array $data)
    {
        if (isset($data['name'])) {
            $fileGroup->setName($data['name']);
        }
        $this->getFileGroupHydrator()->hydrate($fileGroup, $data);

        return $fileGroup;
    }
}