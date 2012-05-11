<?php
namespace TheCure\Specs;
/**
 * Test base domain class
 *
 * @package     TheCure
 * @category    Spec
 * @category    Test
 * @copyright   Gignite, 2012
 * @license     MIT
 * 
 * @group  specs
 * @group  models
 * @group  models.model
 */
use TheCure\Models;

class ModelTest extends \PHPUnit_Framework_TestCase {

	public function testDefaultObject()
	{
		$model = new Models\User;
		$this->assertInstanceOf('TheCure\Object', $model->__object());
	}

}