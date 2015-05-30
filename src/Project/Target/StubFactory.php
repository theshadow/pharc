<?php
/**
 * Short description
 * 
 * Long Description
 * 
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 */

namespace Pharc\Project\Target;

use Pharc\Project\Target\Stub\FileStub;
use Pharc\Project\Target\Stub\StringStub;
use Pharc\Project\Target\Stub\CommandStub;

/**
 * Class StubFactory
 * @package Pharc\Project\Target
 */
class StubFactory
{
    /**
     * @param $data
     * @return StubInterface
     */
    public function create(array $data)
    {
        if (array_key_exists('command', $data)) {
            $workingDir = isset($data['working-dir']) ? $data['working-dir'] : null;
            return $this->createCommandStub($data['command'], $workingDir);
        } elseif (array_key_exists('string', $data)) {
            return $this->createStringStub($data['string']);
        } elseif (array_key_exists('file', $data)) {
            return $this->createFileStub($data['file']);
        }

        throw new \LogicException('Unable to deduce stub type based on parameters: ' . var_export($data, true));
    }

    /**
     * @param string $string
     * @return StringStub
     */
    public function createStringStub($string)
    {
        $stringStub = new StringStub();
        $stringStub->setString($string);

        return $stringStub;
    }

    /**
     * @param $command
     * @param null $workingDir
     * @return CommandStub
     */
    public function createCommandStub($command, $workingDir = null)
    {
        $commandStub = new CommandStub();
        $commandStub->setCommand($command);
        $commandStub->setWorkingDir($workingDir);

        return $commandStub;
    }

    /**
     * @param $file
     * @return FileStub
     */
    public function createFileStub($file)
    {
        $fileStub = new FileStub();
        $fileStub->setFile($file);
        return $fileStub;
    }
}