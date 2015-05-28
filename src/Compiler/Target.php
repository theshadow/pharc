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

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $outFile;

    /**
     * @var string
     */
    protected $signatureMethod;

    /**
     * @var array
     */
    protected $includes;

    /**
     * @var array
     */
    protected $excludes;

    protected $stub = <<<'EOF'
#!/usr/bin/env php
<?php
/*
 * This file is part of Pharc.
 *
 * (c) Xander Guzman <xander.guzman@xanderguzman.com>
 *
 * For the full copyright and license information, please view
 * the license that is located at the bottom of this file.
 */
// Avoid APC causing random fatal errors per https://github.com/composer/composer/issues/264
if (extension_loaded('apc') && ini_get('apc.enable_cli') && ini_get('apc.cache_by_default')) {
    if (version_compare(phpversion('apc'), '3.0.12', '>=')) {
        ini_set('apc.cache_by_default', 0);
    } else {
        fwrite(STDERR, 'Warning: APC <= 3.0.12 may cause fatal errors when running composer commands.'.PHP_EOL);
        fwrite(STDERR, 'Update APC, or set apc.enable_cli or apc.cache_by_default to 0 in your php.ini.'.PHP_EOL);
    }
}
Phar::mapPhar('%PHAR%');

require 'phar://%PHAR%/%ENTRY-POINT%';
__HALT_COMPILER();
EOF;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return static
     */
    public function setName($name)
    {
        if (empty($name) || !is_string($name)) {
            throw new \InvalidArgumentException(
                '$name must be a valid string.'
            );
        }
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getOutFile()
    {
        return $this->outFile;
    }

    /**
     * @param string $outFile
     * @return static
     */
    public function setOutFile($outFile)
    {
        if (empty($outFile) || !is_string($outFile)) {
            throw new \InvalidArgumentException(
                '$outFile must be a valid string.'
            );
        }
        $this->outFile = $outFile;
        return $this;
    }

    /**
     * @return string
     */
    public function getSignatureMethod()
    {
        return $this->signatureMethod;
    }

    /**
     * @param string $signatureMethod
     * @return static
     */
    public function setSignatureMethod($signatureMethod)
    {
        if (empty($signatureMethod)
            || !is_string($signatureMethod)
            || !array_key_exists($signatureMethod, static::$SIGNATURE_METHODS)
        ) {
            throw new \InvalidArgumentException(
                '$signatureMethod must be a valid string of one of the following ['
                . implode(', ' , static::$SIGNATURE_METHODS) . '].'
            );
        }
        $this->signatureMethod = $signatureMethod;
        return $this;
    }

    /**
     * @return array
     */
    public function getIncludes()
    {
        return $this->includes;
    }

    /**
     * @param array $includes
     * @return static
     */
    public function setIncludes(array $includes)
    {
        $error = array_reduce($includes, function ($carry, $include) {
            if (!$carry) return true;
            if (empty($include) || !is_string($include)) {
                return true;
            }
        }, false);

        if ($error) {
            throw new \InvalidArgumentException('$includes must be a collection of valid strings.');
        }
        $this->includes = $includes;
        return $this;
    }

    /**
     * @return array
     */
    public function getExcludes()
    {
        return $this->excludes;
    }

    /**
     * @param array $excludes
     * @return static
     */
    public function setExcludes(array $excludes)
    {
        $error = array_reduce($excludes, function ($carry, $exclude) {
            if (!$carry) return true;
            if (empty($exclude) || !is_string($exclude)) {
                return true;
            }
        }, false);

        if ($error) {
            throw new \InvalidArgumentException('$excludes must be a collection of valid strings.');
        }

        $this->excludes = $excludes;
        return $this;
    }

}