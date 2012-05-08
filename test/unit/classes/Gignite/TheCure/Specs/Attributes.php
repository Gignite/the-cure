<?php
namespace Gignite\TheCure\Specs;

/**
 * @group  specs
 * @group  attributes
 */

use Gignite\TheCure\Attributes;
use Gignite\TheCure\Field;

class AttributesTest extends \PHPUnit_Framework_TestCase {

	public function testItShouldAddAttributesViaConstruct()
	{
		$attributes = new Attributes(
			$name = new Field('name'),
			$age = new Field('age'));
		$this->assertSame(
			array_values(compact('name', 'age')),
			$attributes->as_array());
		return $attributes;
	}

	/**
	 * @depends  testItShouldAddAttributesViaConstruct
	 */
	public function testItShouldAddAttribute($attributes)
	{
		$attributes->add(new Field('email'));
		$this->assertSame(3, count($attributes->as_array()));
		return $attributes;
	}

	/**
	 * @depends  testItShouldAddAttribute
	 */
	public function testItShouldAddAttributes($attributes)
	{
		$attributes->add(new Field('nickname'), new Field('url'));
		$this->assertSame(5, count($attributes->as_array()));
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
		$this->assertSame(7, count($attributes->as_array()));
		return $attributes;
	}

	/**
	 * @depends  testItShouldAddAttributesViaArray
	 */
	public function testItShouldRemoveAttribute($attributes)
	{
		$attributes->remove('facebook');
		$this->assertSame(6, count($attributes->as_array()));
		return $attributes;
	}

	/**
	 * @depends  testItShouldRemoveAttribute
	 * @expectedException  Gignite\TheCure\Attribute\AliasUsedException
	 */
	public function testItShouldThrowExceptionWhenAddingUsedAlias($attributes)
	{
		$attributes->add(new Field('twitter'));
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
		$this->assertSame(7, count($attributes->as_array()));

		unset($attributes['new_field']);
		$this->assertSame(6, count($attributes->as_array()));
	}

}

