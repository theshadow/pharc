<?php
/**
 * Contains the DependencyManagerInterface
 * 
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 */

namespace Pharc\Project\Target\Dependency;

use LogicException;

use Pharc\Project\Target\Composer\DependencyManager as ComposerDependencyManager;

/**
 * Class DependencyManagerFactory
 * @package Pharc\Project\Target\Dependency
 */
class DependencyManagerFactory
{
    /**
     * string
     */
    const COMPOSER_DEPENDENCY_TYPE = 'composer';

    /**
     * @var array
     */
    static $DEPENDENCY_MANAGERS = ['composer'];

    /**
     * @param $type
     * @param array $config
     * @return DependencyManagerInterface
     */
    public function create($type, array $config)
    {
        if ($type === static::COMPOSER_DEPENDENCY_TYPE) {
            return $this->createComposerManager($config);
        }

        throw new \InvalidArgumentException(
            '$type must be one of the following [' . implode(',', static::$DEPENDENCY_MANAGERS) . ']'
        );
    }

    /**
     * @param array $config
     * @return ComposerDependencyManager
     */
    public function createComposerManager(array $config)
    {
        if (array_key_exists(static::COMPOSER_DEPENDENCY_TYPE, $config)) {
            throw new LogicException(
                static::COMPOSER_DEPENDENCY_TYPE . ' build configuration not found'
            );
        }

        return new ComposerDependencyManager($config);
    }
}