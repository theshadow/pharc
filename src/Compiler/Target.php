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
 * Class Target
 * @package Pharc\Compiler
 */
class Target
{
    /**
     * @var array
     */
    public static $SIGNATURE_METHODS = [
        'SHA1' => \Phar::SHA1,
        'SHA256' => \Phar::SHA256,
        'SHA512' => \Phar::SHA512
    ];

    protected $phar;
    protected $stub;
    protected $license;
    protected $signatureMethod;
    protected $composer;
    protected $files;
}