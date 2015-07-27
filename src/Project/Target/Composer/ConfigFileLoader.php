<?php
/**
 * Short description
 * 
 * Long Description
 * 
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 */

namespace Pharc\Project\Target\Composer;

/**
 * Class ConfigFileLoader
 * @package Pharc\Project\Target\Composer
 */
class ConfigFileLoader
{
    const ERROR_NOT_ACCESSIBLE = 1;
    const ERROR_NOT_READABLE = 2;

    /**
     * @param $filename
     * @return array
     */
    public function load($filename)
    {
        if (!is_readable($filename)) {
            throw new \LogicException(
                'The composer json configuration file ' . $filename . ' is either not readable or does not existed.',
                static::ERROR_NOT_ACCESSIBLE
            );
        }
        $contents = file_get_contents($filename);
        if ($contents === false) {
            throw new \LogicException(
                'Unable to read the contents of ' . $filename,
                static::ERROR_NOT_READABLE
            );
        }

        $results = json_decode($contents, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \LogicException(
                'Unable to parse composer json file ' . $filename . ': ' . json_last_error_msg()
            );
        }

        return $results;
    }
}