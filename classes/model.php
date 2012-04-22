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
 *     $user = new Model_User;
 *     $user->__object((object) array(
 *         'name' => 'Luke',
 *     ));
 *     $object = $user->__object();
 *
 * @package     TheCure
 * @category    Model
 * @copyright   Gignite, 2011
 */
abstract class Model {

	protected $object;
	
	/**
	 * Get/set the data transfer object.
	 *
	 * @param   StdClass  If setting pass in a StdClass
	 * @return  StdClass  If getting
	 */
	public function __object($object = NULL)
	{
		if ($object === NULL)
		{
			if ($this->object === NULL)
			{
				$this->object = new StdClass;
			}
			
			return $this->object;
		}

		$this->object = $object;
	}

}