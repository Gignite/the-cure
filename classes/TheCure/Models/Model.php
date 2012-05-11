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
 * for setting and getting the data transfer object.
 *
 *     $user = new Models\User;
 *     $user->__object(new Object(array(
 *         'name' => 'Luke',
 *     ));
 *     $object = $user->__object();
 *
 * @package     TheCure
 * @category    Models
 * @copyright   Gignite, 2012
 */
namespace Gignite\TheCure\Models;

use Gignite\TheCure\Object;

abstract class Model {

	protected $__object;
	
	/**
	 * Get/set the data transfer object.
	 *
	 * @param  Object $object If setting pass in an Object
	 * @return Object If getting
	 */
	public function __object(Object $object = NULL)
	{
		if ($object === NULL)
		{
			if ($this->__object === NULL)
			{
				$this->__object = new Object;
			}
			
			return $this->__object;
		}

		$this->__object = $object;
	}

}