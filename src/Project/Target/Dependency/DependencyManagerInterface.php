<?php
/**
 * Contains the DependencyMangerInterface interface
 * 
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 */

namespace Pharc\Project\Target\Dependency;

use Pharc\Project\FileGroupInterface;

/**
 * Class DependencyManagerInterface
 * @package Pharc\Project\Target\Dependency
 */
interface DependencyManagerInterface
{
    /**
     * @param array $configuration
     */
    public function __construct(array $configuration);

    /**
     * @return FileGroupInterface
     */
    public function build();
}