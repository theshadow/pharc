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
 * Class FileGroupHydratorTrait
 * @package Pharc\Project\FileSet
 */
trait FileGroupHydratorTrait
{
    /**
     * @var FileGroupHydrator
     */
    protected $fileSetHydrator;

    /**
     * @return FileGroupHydrator
     */
    public function getFileGroupHydrator()
    {
        if (is_null($this->fileSetHydrator)) {
            $this->fileSetHydrator = new FileGroupHydrator();
        }
        return $this->fileSetHydrator;
    }

    /**
     * @param FileGroupHydrator $fileSetHydrator
     * @return static
     */
    public function setFileGroupHydrator($fileSetHydrator)
    {
        $this->fileSetHydrator = $fileSetHydrator;
        return $this;
    }
}