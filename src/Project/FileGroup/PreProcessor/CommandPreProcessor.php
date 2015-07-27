<?php
/**
 * Short description
 * 
 * Long Description
 * 
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 */

namespace Pharc\Project\FileGroup\PreProcessor;

use Symfony\Component\Process\Process;

use Pharc\Project\FileGroup\PreProcessorInterface;

/**
 * Class CommandPreProcessor
 * @package Pharc\Project\FileSet\PreProcessor
 */
class CommandPreProcessor implements PreProcessorInterface
{
    /**
     * @var string
     */
    protected $command;

    /**
     * @var string
     */
    protected $workingDir;

    /**
     * @param $contents
     * @return mixed
     */
    public function process($contents)
    {
        $process = new Process($this->getCommand());
        $process->setStdin($contents);
        if ($process->run() !== 0) {
            throw new \RuntimeException(
                'Unable to execute the command ' . $this->getCommand() . ': ' . PHP_EOL
                .  $process->getErrorOutput() . PHP_EOL
                . $process->getOutput()
            );
        }
        return $process->getOutput();
    }

    /**
     * @return string
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * @param string $command
     * @return static
     */
    public function setCommand($command)
    {
        $this->command = $command;
        return $this;
    }

    /**
     * @return string
     */
    public function getWorkingDir()
    {
        return $this->workingDir;
    }

    /**
     * @param string $workingDir
     * @return static
     */
    public function setWorkingDir($workingDir)
    {
        $this->workingDir = $workingDir;
        return $this;
    }
}