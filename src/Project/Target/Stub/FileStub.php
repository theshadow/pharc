<?php
/**
 * Short description
 * 
 * Long Description
 * 
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 */

namespace Pharc\Project\Target\Stub;

use Pharc\Project\Target\StubInterface;

class FileStub implements StubInterface
{
    const ERROR_UNABLE_ACCESS_FILE = 1;
    const ERROR_UNABLE_TO_READ_FILE = 2;

    /**
     * @var string
     */
    protected $file;

    /**
     * @return string
     */
    public function retrieve()
    {
        if (!is_readable($this->getFile())) {
            throw new \LogicException(
                'The file ' . $this->getFile() . ' is either unreadable or does not exist.',
                static::ERROR_UNABLE_ACCESS_FILE
            );
        }
        $contents = file_get_contents($this->getFile());
        if ($contents === false) {
            throw new \LogicException(
                'Unable to read the contents from ' . $this->getFile(),
                static::ERROR_UNABLE_TO_READ_FILE
            );
        }
        return $contents;
    }

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param mixed $file
     * @return static
     */
    public function setFile($file)
    {
        $this->file = $file;
        return $this;
    }
}