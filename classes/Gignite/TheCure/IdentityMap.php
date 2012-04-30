<?php
/**
 * An identity map
 *
 * @package     TheCure
 * @category    IdentityMap
 * @copyright   Gignite, 2012
 */
namespace Gignite\TheCure;

use Gignite\TheCure\Models\Model;

/**
 * Describe the identities handled in a session
 * 
 * In order to ensure only one [Domain] object is created per
 * record (row, document, etc.) we use an [IdentityMap] to
 * register [Domain] objects and check for their existence.
 * 
 * @package     Beautiful
 * @subpackage  Beautiful Domain
 * @category    IdentityMap
 * @author      Luke Morton
 * @copyright   Luke Morton, 2011
 * @license     MIT
 */
class IdentityMap {

	protected $identities = array();

	protected function extract_identity_from_object($object)
	{
		return (string) $object->_id;
	}

	protected function extract_identity(Model $model)
	{
		return $this->extract_identity_from_object($model->__object());
	}

	protected function key($model, $id = NULL)
	{
		if ($model instanceOf Model)
		{
			$class = get_class($model);
		}
		else
		{
			$class = $model;
		}

		if ($id === NULL)
		{
			$id = $this->extract_identity($model);
		}
		
		return $class.$id;
	}

	/**
	 * Has this identity map mapped $model?
	 *
	 * @param   Model
	 * @return  boolean
	 */
	public function has(Model $model)
	{
		return in_array($model, $this->identities);
	}

	/**
	 * Get a Model by class name and ID.
	 *
	 * @param   string  class name
	 * @param   mixed   ID
	 * @return  Model
	 */
	public function get($class, $id)
	{
		$key = $this->key($class, $id);
		
		if (isset($this->identities[$key]))
		{
			return $this->identities[$key];
		}
	}

	/**
	 * Add a Model to the identity map.
	 *
	 * @param   string  class name
	 * @param   mixed   ID
	 * @return  Model
	 */
	public function set(Model $model)
	{
		$this->identities[$this->key($model)] = $model;
	}

	/**
	 * Delete a Model from the identity map.
	 *
	 * @param   string  class name
	 * @param   mixed   ID
	 * @return  Model
	 */
	public function delete(Model $model)
	{
		unset($this->identities[$this->key($model)]);
	}

}