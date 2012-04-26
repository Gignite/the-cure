<?php
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

	public function has(Model $model)
	{
		return in_array($model, $this->identities);
	}

	public function get($class, $id)
	{
		return Arr::get($this->identities, $this->key($class, $id));
	}

	public function set(Model $model)
	{
		$this->identities[$this->key($model)] = $model;
	}

	public function delete(Model $model)
	{
		unset($this->identities[$this->key($model)]);
	}

}