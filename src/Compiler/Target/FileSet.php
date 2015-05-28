<?php
/**
 * Short description
 * 
 * Long Description
 * 
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 */

namespace Pharc\Compiler;

/**
 * Class FileSet
 * @package Pharc\Compiler
 */
class FileSet
{
    protected $setName;
    protected $path;
    protected $name;
    protected $ignoreVcs = true;
    protected $strip = true;
    protected $spacer;
    protected $excludes = array();
    protected $preprocessors = array();
}