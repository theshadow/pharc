<?php
/**
 * Short description
 * 
 * Long Description
 * 
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 */

namespace Pharc\Compiler;


class Composer
{
    protected $config;
    protected $path;
    protected $includeDev = false;
    protected $excludes = array();
    protected $includes = array();
}