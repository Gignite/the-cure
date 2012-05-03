<?php
namespace Gignite\TheCure\Models;

use Gignite\TheCure\Models\Magic as MagicModel;

use Gignite\TheCure\Relationships\OneToOne as OneToOneRelationship;
use Gignite\TheCure\Relationships\BelongsToOne as BelongsToOneRelationship;

class Userr extends MagicModel {
	
	public static function attributes()
	{
		return array(
			new OneToOneRelationship('password', array(
				'mapper_suffix' => 'Password',
			)),
		);
	}

}

class Password extends MagicModel {
	
	public static function attributes()
	{
		return array(
			new BelongsToOneRelationship('user', array(
				'mapper_suffix' => 'Userr',
			)),
		);
	}

	public function __construct($password)
	{
		$this->__object()->password = md5($password);
	}

}

namespace Gignite\TheCure\Mappers\Mongo;

use Gignite\TheCure\Mappers\Mongo as MongoMapper;

class Userr extends MongoMapper {}
class Password extends MongoMapper {}

namespace Gignite\TheCure\Mappers\Mock;

use Gignite\TheCure\Mappers\Mock as MockMapper;

class Userr extends MockMapper {}
class Password extends MockMapper {}

namespace Gignite\TheCure\Acceptance\Relationships;

/**
 * @group  acceptance
 * @group  relationships
 * @group  relationships.onetoone
 */

use Gignite\TheCure\Mapper\Container;

use Gignite\TheCure\Models\Userr;
use Gignite\TheCure\Models\Password;

class OneToOne extends \PHPUnit_Framework_TestCase {

	public function provideContainers()
	{
		return array(
			array(new Container('Mock')),
			array(new Container('Mongo')),
		);
	}

	/**
	 * @dataProvider  provideContainers
	 */
	public function testItShouldWork($container)
	{
		$user = new Userr;
		$user->__container($container);
		
		$password = new Password('a password');
		$password->__container($container);

		$user->password($password);

		$container->mapper('Password')->save($password);
		$container->mapper('Userr')->save($user);

		// Test OneToOne
		$this->assertSame($password, $user->password());

		// Test BelongsToOne
		$this->assertSame($user, $password->user());
	}

}

