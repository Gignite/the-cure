<?php
/**
 * Test the identities handled in a session
 * 
 * @package     Beautiful
 * @subpackage  Beautiful Domain
 * @category    IdentityMap
 * @category    Test
 * @author      Luke Morton
 * @copyright   Luke Morton, 2011
 * @license     MIT
 */
use Gignite\TheCure\IdentityMap;
use Gignite\TheCure\Object;

class IdentityMapTest extends PHPUnit_Framework_TestCase {

	protected $domain;

	public function setUp()
	{
		$this->domain = new Models\User;
		$this->domain->__object(new Object(array('_id' => 2)));
	}

	public function testItShouldConstructAnIdentityMap()
	{
		return new IdentityMap;
	}

	/**
	 * @depends  testItShouldConstructAnIdentityMap
	 */
	public function testItShouldNotHaveDomain($map)
	{
		$this->assertFalse($map->has($this->domain));
	}

	/**
	 * @depends  testItShouldConstructAnIdentityMap
	 */
	public function testItShouldSetDomain($map)
	{
		$map->set($this->domain);
		return $map;
	}

	/**
	 * @depends  testItShouldSetDomain
	 */
	public function testItShouldGetDomain($map)
	{
		$this->assertEquals($this->domain, $map->get('Models\User', 2));
	}

	/**
	 * @depends  testItShouldConstructAnIdentityMap
	 */
	public function testItShouldDeleteDomain($map)
	{
		$map->delete($this->domain);
		$this->assertFalse($map->has($this->domain));
	}

}