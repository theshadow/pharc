<?php
/**
 * Short description
 * 
 * Long Description
 * 
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 */

namespace Pharc\Project;

use InvalidArgumentException;
use LogicException;
use RuntimeException;

use Symfony\Component\Yaml\Yaml;

/**
 * Class FileLoader
 * @package Pharc
 */
class BuildFileLoader
{
    /**
     * @param string $file Full path to the yaml file.
     * @return array
     */
    public function load($file)
    {
        if (empty($file) || !is_string($file)) {
            throw new InvalidArgumentException(
                '$file must be a valid string.'
            );
        }

        if (!is_readable($file))
        {
            throw new LogicException(
                $file . ' is not readable or does not exist.'
            );
        }

        try {
            $data = Yaml::parse(file_get_contents($file));
        } catch(\Exception $e) {
            throw new RuntimeException(
                'Unable to parse the contents of ' . $file,
                0,
                $e
            );
        }

        return $data;
    }
}