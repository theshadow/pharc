<?php
/**
 * Short description
 * 
 * Long Description
 * 
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 */

namespace Pharc\Project\FileGroup;


use Pharc\Project\FileGroup\PreProcessor\CommandPreProcessor;
use Pharc\Project\FileGroup\PreProcessor\RegexPreProcessor;

/**
 * Class PreProcessorFactory
 * @package Pharc\Project\FileSet
 */
class PreProcessorFactory
{
    /**
     * @param array $data
     * @return PreProcessorInterface
     */
    public function create(array $data)
    {
        if (isset($data['find']) && isset($data['replace'])) {
            return $this->createRegexPreProcessor($data['find'], $data['replace']);
        } elseif (isset($data['command'])) {
            $workingDir = isset($data['working-dir']) ? $data['working-dir'] : null;
            return $this->createCommandPreProcessor($data['command'], $workingDir);
        }

        throw new \LogicException(
            'Unable to determine type of preprocessor: ' . var_export($data, true)
        );
    }

    public function createCommandPreProcessor($command, $workingDir = null)
    {
        $commandPreProcessor = new CommandPreProcessor();
        $commandPreProcessor->setCommand($command)
            ->setWorkingDir($workingDir);

        return $commandPreProcessor;
    }

    /**
     * @param $find
     * @param $replace
     * @return RegexPreProcessor
     */
    public function createRegexPreProcessor($find, $replace)
    {
        $regexPreProcessor = new RegexPreProcessor();
        $regexPreProcessor->setFind($find)
            ->setReplace($replace);
        return $regexPreProcessor;
    }
}