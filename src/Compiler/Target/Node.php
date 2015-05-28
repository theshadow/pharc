<?php
/**
 * Short description
 * 
 * Long Description
 * 
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 */

namespace Pharc\Compiler\Target;


trait Node
{
    /**
     * @var
     */
    protected $path;

    /**
     * @var string
     */
    protected $spacer;

    /**
     * @var bool
     */
    protected $isDirectory = false;

    /**
     * @return $this
     */
    public function markAsDirectory()
    {
        $this->isDirectory = true;
        return $this;
    }

    /**
     * @return bool
     */
    public function isDirectory()
    {
        return $this->isDirectory();
    }
}