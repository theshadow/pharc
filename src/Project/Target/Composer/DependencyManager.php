<?php
/**
 * Contains the DependencyManager class.
 * 
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 */

namespace Pharc\Project\Target\Composer;

use Pharc\Project\FileGroupInterface;
use Pharc\Project\Target\Dependency\DependencyManagerInterface;
use Pharc\Project\Target\FileGroup;

/**
 * Class DependencyManager
 * @package Pharc\Project\Target\Composer
 */
class DependencyManager implements DependencyManagerInterface
{
    /**
     * @var array
     */
    protected $configuration;

    /**
     * @var ConfigFileLoader
     */
    protected $configFileLoader;

    /**
     * @param array $configuration
     */
    public function __construct(array $configuration)
    {
        $this->setConfiguration($configuration);
    }

    /**
     * @return array
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     * @param array $configuration
     * @return static
     */
    public function setConfiguration($configuration)
    {
        $this->configuration = $configuration;
        return $this;
    }

    /**
     * @return FileGroupInterface
     */
    public function build()
    {
        $buildConfig = $this->getConfiguration();
        $composerConfig = $this->getConfigFileLoader()
            ->load($buildConfig['config']);

        $vendorPath = getcwd() . '/vendor/';
        if (isset($composerConfig['config']['vendor-dir'])) {
            $vendorPath = $composerConfig['config']['vendor-dir'];
        }

        $dependencyPaths = $composerConfig['require'];

        if ($buildConfig['include-dev']) {
            $dependencyPaths = array_merge($dependencyPaths, $composerConfig['require-dev']);
        }

        $dependencyPaths = array_reduce(array_keys($dependencyPaths), function ($carry, $path) use ($vendorPath) {
            if ($path === 'php') return $carry;
            $carry[] = $vendorPath . $path . DIRECTORY_SEPARATOR;
            return $carry;
        }, []);

        // always add the composer autoload files.
        $dependencyPaths[] = $vendorPath . 'composer/';

        $fileGroup = new FileGroup();
        $fileGroup->setNames(['autoload.php']);
        $fileGroup->setPaths($dependencyPaths);

        return $fileGroup;
    }

    /**
     * @return ConfigFileLoader
     */
    public function getConfigFileLoader()
    {
        if (is_null($this->configFileLoader)) {
            $this->configFileLoader = new ConfigFileLoader();
        }
        return $this->configFileLoader;
    }

    /**
     * @param ConfigFileLoader $configFileLoader
     * @return static
     */
    public function setConfigFileLoader($configFileLoader)
    {
        $this->configFileLoader = $configFileLoader;
        return $this;
    }
}