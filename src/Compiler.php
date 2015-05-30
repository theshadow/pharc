<?php
/**
 * Short description
 * 
 * Long Description
 * 
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 */

namespace Pharc;

use Pharc\Project\Target;

/**
 * Class Compiler
 * @package Pharc
 */
class Compiler
{
    /**
     * @param Target $target
     */
    public function compile(Target $target)
    {
        if (file_exists($target->getPhar())) {
            unlink($target->getPhar());
        }

    }
}