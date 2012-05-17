<?php
namespace TheCure\Specs;
/**
 * Test the accessor object for setting and getting the
 * data transfer object to and from a model.
 *
 * @package     TheCure
 * @category    ObjectAccessor
 * @category    Spec
 * @category    Test
 * @copyright   Gignite, 2012
 * @license     MIT
 * 
 * @group  specs
 * @group  objectaccessor
 */
use TheCure\ObjectAccessor;
use TheCure\Object;
use TheCure\Models\User\Magic as User;

class ObjectAccessorTest extends \PHPUnit_Framework_TestCase {

	public function testItShouldSetObject()
	{
		$model = new User;
		$accessor = new ObjectAccessor;
		$accessor->set($model, new Object(array('name' => 'Luke')));
		$this->assertSame('Luke', $model->name());
	}

	public function testItShouldGetObject()
	{
		$model = new User;
		$model->__object(new Object(array('name' => 'Luke')));
		$accessor = new ObjectAccessor;
		$object = $accessor->get($model);
		$this->assertInstanceOf('TheCure\Object', $object);
		$this->assertSame('Luke', $object->name);
	}

	public function testItShouldConvertArrayToObjectWhenSetting()
	{
		$model = new User;
		$accessor = new ObjectAccessor;
		$accessor->set($model, array('name' => 'Luke'));
		$this->assertInstanceOf('TheCure\Object', $model->__object());
	}

}

