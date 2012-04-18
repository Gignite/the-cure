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

	protected function extract_identity_from_object(StdClass $object)
	{
		return (string) $object->_id;
	}

	protected function extract_identity(Model $model)
	{
		return $this->extract_identity_from_object($model->__object());
	}

	public function has(Model $model)
	{
		return in_array($model, $this->identities);
	}

	public function get($class, $id)
	{
		if ($id instanceOf StdClass)
		{
			$id = $this->extract_identity_from_object($id);
		}

		return Arr::get($this->identities, $class.$id);
	}

	public function set(Model $model)
	{
		$id = $this->extract_identity($model);
		$this->identities[get_class($model).$id] = $model;
	}

	public function unset(Model $model)
	{
		$id = $this->extract_identity($model);
		unset($this->identities[get_class($model).$id]);
	}

}