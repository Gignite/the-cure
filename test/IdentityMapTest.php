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

	public function testConstruct()
	{
		return new IdentityMap;
	}

	/**
	 * @depends  testConstruct
	 */
	public function testHasNotDomain($map)
	{
		$this->assertFalse($map->has($this->domain));
	}

	/**
	 * @depends  testConstruct
	 */
	public function testSetDomain($map)
	{
		$map->set($this->domain);
		return $map;
	}

	/**
	 * @depends  testSetDomain
	 */
	public function testGetDomain($map)
	{
		$this->assertEquals($this->domain, $map->get('Models\User', 2));
	}

	/**
	 * @depends  testConstruct
	 */
	public function testDeleteDomain($map)
	{
		$map->delete($this->domain);
		$this->assertFalse($map->has($this->domain));
	}

}