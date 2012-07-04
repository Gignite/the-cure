<?php
namespace TheCure\Specs;
/**
 * Test the identities handled in a session
 *
 * @package     TheCure
 * @category    IdentityMap
 * @category    Spec
 * @category    Test
 * @copyright   Gignite, 2012
 * @license     MIT
 *  
 * @group  specs
 * @group  identitymap
 */
use TheCure\Maps\IdentityMap;
use TheCure\TransferObjects\TransferObject;
use TheCure\Accessors\TransferObjectAccessor;
use TheCure\Models\User;

class IdentityMapTest extends \PHPUnit_Framework_TestCase {

	protected $domain;

	public function setUp()
	{
		$this->domain = new User;
		$accessor = new TransferObjectAccessor;
		$accessor->set($this->domain, array('_id' => 2));
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
		$this->assertFalse($map->has('ns', $this->domain));
	}

	/**
	 * @depends  testItShouldConstructAnIdentityMap
	 */
	public function testItShouldSetDomain($map)
	{
		$map->set('ns', $this->domain);
		return $map;
	}

	/**
	 * @depends  testItShouldSetDomain
	 */
	public function testItShouldGetDomain($map)
	{
		$this->assertEquals(
			$this->domain,
			$map->get('ns', 2));
	}

	/**
	 * @depends  testItShouldConstructAnIdentityMap
	 */
	public function testItShouldDeleteDomain($map)
	{
		$map->delete('ns', $this->domain);
		$this->assertFalse($map->has('ns', $this->domain));
	}

}