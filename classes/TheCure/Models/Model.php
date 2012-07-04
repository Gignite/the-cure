<?php
/**
 * A base class for domain logic
 *
 * The domain objects of your application should be souly
 * focused on handling business logic. They shouldn't need to
 * worry about how to persist the data they contain, nor
 * should they be worried about whether the data has been
 * persisted.
 *
 * This class provides one pseudo-magic method ::__object()
 * for setting and getting the data transfer object. This is
 * not to be relied upon and you should always use
 * `TheCure\ObjectAccessor` to access a models DTO.
 *
 *     $user = new Models\User;
 *     $accessor = new ObjectAccessor;
 *     $accessor->set($user, array(
 *         'name' => 'Luke',
 *     ));
 *     $object = $accessor->get($user);
 *
 * @package     TheCure
 * @category    Model
 * @copyright   Gignite, 2012
 * @license     MIT
 */
namespace TheCure\Models;

use TheCure\TransferObjects\TransferObject;

abstract class Model {

	protected $__object;
	
	/**
	 * Get/set the data transfer object.
	 *
	 * @param  TransferObject $object If setting pass in an TransferObject
	 * @return TransferObject If getting
	 */
	public function __object(TransferObject $object = NULL)
	{
		if ($object === NULL)
		{
			return $this->__object;
		}

		$this->__object = $object;
	}

}