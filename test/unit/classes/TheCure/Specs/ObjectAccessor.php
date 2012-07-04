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
use TheCure\Accessors\TransferObjectAccessor;
use TheCure\TransferObjects\TransferObject;
use TheCure\Models\User\Magic as User;

class ObjectAccessorTest extends \PHPUnit_Framework_TestCase {

	public function testItShouldSetObject()
	{
		$model = new User;
		$accessor = new TransferObjectAccessor;
		$accessor->set($model, new TransferObject(array('name' => 'Luke')));
		$this->assertSame('Luke', $model->name());
	}

	public function testItShouldGetObject()
	{
		$model = new User;
		$model->__object(new TransferObject(array('name' => 'Luke')));
		$accessor = new TransferObjectAccessor;
		$object = $accessor->get($model);
		$this->assertInstanceOf(
			'TheCure\TransferObjects\TransferObject',
			$object);
		$this->assertSame('Luke', $object->name);
	}

	public function testItShouldConvertArrayToObjectWhenSetting()
	{
		$model = new User;
		$accessor = new TransferObjectAccessor;
		$accessor->set($model, array('name' => 'Luke'));
		$this->assertInstanceOf(
			'TheCure\TransferObjects\TransferObject',
			$model->__object());
	}

}

