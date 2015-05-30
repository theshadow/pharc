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

class DefaultStub implements StubInterface
{
    /**
     * @var string
     */
    protected $phar;

    /**
     * @var string
     */
    protected $bin;

    /**
     * @return string
     */
    public function retrieve()
    {
        $bin = $this->getBin();
        if ($bin[0] !== '/') {
            $bin = '/' . $bin;
        }

        return str_replace(
            ['%%COMPOSER_FILE%%', '%%BIN_PATH%%'],
            [$this->getPhar(), $bin],
            file_get_contents(__DIR__ . '/../../../../res/pharc.stub')
        );
    }

    /**
     * @return string
     */
    public function getPhar()
    {
        return $this->phar;
    }

    /**
     * @param string $phar
     * @return static
     */
    public function setPhar($phar)
    {
        $this->phar = $phar;
        return $this;
    }

    /**
     * @return string
     */
    public function getBin()
    {
        return $this->bin;
    }

    /**
     * @param string $bin
     * @return static
     */
    public function setBin($bin)
    {
        $this->bin = $bin;
        return $this;
    }
}