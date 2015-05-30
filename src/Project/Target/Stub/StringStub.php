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

/**
 * Class StringStub
 * @package Pharc\Project\Target\Stub
 */
class StringStub implements StubInterface
{
    /**
     * @var string
     */
    protected $string;

    /**
     * @return string
     */
    public function retrieve()
    {
        return $this->getString();
    }

    /**
     * @return string
     */
    public function getString()
    {
        return $this->string;
    }

    /**
     * @param string $string
     * @return static
     */
    public function setString($string)
    {
        $this->string = $string;
        return $this;
    }
}