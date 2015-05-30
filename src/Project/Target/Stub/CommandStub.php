<?php
/**
 * Short description
 * 
 * Long Description
 * 
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 */

namespace Pharc\Project\Target\Stub;

use Symfony\Component\Process\Process;

use Pharc\Project\Target\StubInterface;

/**
 * Class CommandStub
 * @package Pharc\Project\Target\Stub
 */
class CommandStub implements StubInterface
{
    /**
     * @var string
     */
    protected $command;

    /**
     * @var string|null
     */
    protected $workingDir;

    /**
     * @return string
     */
    public function retrieve()
    {
        $process = new Process($this->getCommand(), $this->getWorkingDir());
        if ($process->run() !== 0) {
            throw new \RuntimeException(
                'Unable to run command to produce stub: '
                . $process->getErrorOutput() . PHP_EOL
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
     * @return null|string
     */
    public function getWorkingDir()
    {
        return $this->workingDir;
    }

    /**
     * @param null|string $workingDir
     * @return static
     */
    public function setWorkingDir($workingDir)
    {
        $this->workingDir = $workingDir;
        return $this;
    }
}