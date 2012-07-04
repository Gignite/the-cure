<?php
/**
 * An identity map
 *
 * @package     TheCure
 * @category    IdentityMap
 * @copyright   Gignite, 2012
 * @license     MIT
 */
namespace TheCure\Maps;

use TheCure\Models\Model;

use TheCure\Accessors\TransferObjectAccessor;

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

	/**
	 * @param  $object
	 * @return string
	 */
	protected function extractIdentityFromObject($object)
	{
		return (string) $object->_id;
	}

	/**
	 * @param  Models\Model $model
	 * @return string
	 */
	protected function extractIdentity(Model $model)
	{
		$accessor = new TransferObjectAccessor;
		$object = $accessor->get($model);
		return $this->extractIdentityFromObject($object);
	}

	/**
	 * @param  $model
	 * @param  null $id
	 * @return string
	 */
	protected function key($ns, $id)
	{
		if ($id instanceOf Model)
		{
			$id = $this->extractIdentity($id);
		}

		return $ns.$id;
	}

	/**
	 * Has this identity map mapped $model?
	 *
	 * @param  Model   $model
	 * @return boolean
	 */
	public function has($ns, Model $model)
	{
		$key = $this->key($ns, $model);
		return array_search($model, $this->identities) === $key;
	}

	/**
	 * Get a Model by class name and ID.
	 *
	 * @param   string  class name
	 * @param   mixed   ID
	 * @return  Model
	 */
	public function get($ns, $id)
	{
		$key = $this->key($ns, $id);
		
		if (isset($this->identities[$key]))
		{
			return $this->identities[$key];
		}
	}

	/**
	 * Add a Model to the identity map.
	 *
	 * @param  mixed
	 * @param  mixed
	 */
	public function set($ns, $model)
	{
		$this->identities[$this->key($ns, $model)] = $model;
	}

	/**
	 * Delete a Model from the identity map.
	 *
	 * @param Models\Model $model
	 */
	public function delete($ns, Model $model)
	{
		unset($this->identities[$this->key($ns, $model)]);
	}

}