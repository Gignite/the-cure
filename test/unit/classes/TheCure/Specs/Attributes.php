<?php
namespace TheCure\Specs;
/**
 * Test the attributes container
 *
 * @package     TheCure
 * @category    Attributes
 * @category    Spec
 * @category    Test
 * @copyright   Gignite, 2012
 * @license     MIT
 * 
 * @group  specs
 * @group  attributes
 */
use TheCure\Lists\AttributeList;

use TheCure\Attributes\Field;

class AttributesTest extends \PHPUnit_Framework_TestCase {

	public function testItShouldAddAttributesViaConstruct()
	{
		$attributes = new AttributeList(
			$name = new Field('name'),
			$age = new Field('age'));
		$this->assertSame(
			array_values(compact('name', 'age')),
			$attributes->asArray());
		return $attributes;
	}

	/**
	 * @depends  testItShouldAddAttributesViaConstruct
	 */
	public function testItShouldAddAttribute($attributes)
	{
		$attributes->add(new Field('email'));
		$this->assertSame(3, count($attributes->asArray()));
		return $attributes;
	}

	/**
	 * @depends  testItShouldAddAttribute
	 */
	public function testItShouldAddAttributes($attributes)
	{
		$attributes->add(new Field('nickname'), new Field('url'));
		$this->assertSame(5, count($attributes->asArray()));
		return $attributes;
	}

	/**
	 * @depends  testItShouldAddAttributes
	 */
	public function testItShouldAddAttributesViaArray($attributes)
	{
		$attributes->add(array(
			new Field('facebook'),
			new Field('twitter'),
		));
		$this->assertSame(7, count($attributes->asArray()));
		return $attributes;
	}

	/**
	 * @depends  testItShouldAddAttributesViaArray
	 */
	public function testItShouldReplaceAttribute($attributes)
	{
		$attributes->replace(new Field('facebook', array('value' => TRUE)));
		$this->assertSame(7, count($attributes->asArray()));
		$this->assertTrue($attributes->get('facebook')->value());
		return $attributes;
	}

	/**
	 * @depends  testItShouldAddAttributesViaArray
	 */
	public function testItShouldRemoveAttribute($attributes)
	{
		$attributes->remove('facebook');
		$this->assertSame(6, count($attributes->asArray()));
		return $attributes;
	}

	/**
	 * @depends  testItShouldRemoveAttribute
	 * @expectedException  TheCure\Exceptions\AliasUsedException
	 */
	public function testItShouldThrowExceptionWhenAddingUsedAlias($attributes)
	{
		$attributes->add(new Field('twitter'));
	}

	/**
	 * @depends  testItShouldRemoveAttribute
	 * @expectedException  TheCure\Exceptions\AliasUnusedException
	 */
	public function testItShouldThrowExceptionWhenReplacingUnusedAlias($attributes)
	{
		$attributes->replace(new Field('unused'));
	}

	/**
	 * @depends  testItShouldRemoveAttribute
	 */
	public function testItShouldHaveArrayAccess($attributes)
	{
		$expectedField = 'name';
		$this->assertSame($expectedField, $attributes[$expectedField]->name());

		$this->assertTrue(isset($attributes[$expectedField]));

		$attributes[] = new Field('new_field');
		$this->assertSame(7, count($attributes->asArray()));

		unset($attributes['new_field']);
		$this->assertSame(6, count($attributes->asArray()));
	}

}

