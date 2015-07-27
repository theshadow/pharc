<?php
/**
 * Short description
 * 
 * Long Description
 * 
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 */

namespace Pharc;

use Pharc\Project\Target;

/**
 * Class Project
 * @package Pharc
 */
class Project implements \ArrayAccess, \Countable
{
    /**
     * @var Target[]
     */
    protected $targets = array();

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Count elements of an object
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return value is cast to an integer.
     */
    public function count()
    {
        return count($this->targets);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     */
    public function offsetExists($offset)
    {
        return $this->hasTarget($offset);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     */
    public function offsetGet($offset)
    {
        return $this->getTarget($offset);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $this->addTarget($offset, $value);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->targets[$offset]);
    }


    /**
     * @param $name
     * @return bool
     */
    public function hasTarget($name)
    {
        return isset($this->targets[$name]);
    }

    /**
     * @param $name
     * @param Target $target
     * @return static
     */
    public function addTarget($name, Target $target)
    {
        $this->targets[$name] = $target;
        return $this;
    }

    /**
     * @param $name
     * @return Target
     */
    public function getTarget($name)
    {
        if (!$this->hasTarget($name)) {
            throw new \LogicException('The target "' . $name . '" does not exist.');
        }

        return $this->targets[$name];
    }

    /**
     * @return array
     */
    public function getAvailableTargets()
    {
        return array_keys($this->targets);
    }
}