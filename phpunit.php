<?php
/**
 * Test bootstrap for Beautiful Domain
 *
 * You will find a number of Mock (but real) objects used in
 * various tests. In the future I plan to place these in their
 * own directory.
 * 
 * @package     Beautiful
 * @subpackage  Beautiful Domain
 * @category    Test
 * @author      Luke Morton
 * @copyright   Luke Morton, 2011
 * @license     MIT
 */
define('EXT', '.php');
define('APPPATH', __DIR__.'/test/');
define('SYSPATH', APPPATH.'/system/');

error_reporting(E_ALL | E_STRICT);

require SYSPATH.'classes/Kohana/Core.php';
require SYSPATH.'classes/Kohana.php';

spl_autoload_register(array('Kohana', 'auto_load'));

I18n::lang('en-gb');

Kohana::$config = new Kohana_Config;
Kohana::$config->attach(new Config_File);

Kohana::modules(array('the-cure' => __DIR__.'/'));

use Gignite\TheCure\Mapper\Container;
use Gignite\TheCure\Mapper\ConnectionSetGet;
use Gignite\TheCure\Connections\Connection;
use Gignite\TheCure\Mappers\Mock as MockMapper;
use Gignite\TheCure\Mappers\Mongo as MongoMapper;
use Gignite\TheCure\Field;
use Gignite\TheCure\Models\Model;
use Gignite\TheCure\Models\Magic as MagicModel;
use Gignite\TheCure\Relation;
use Gignite\TheCure\Relationships\Relationship;
use Gignite\TheCure\Relationships\OneToMany;

class Model_User extends Model {}
class Model_User_Admin extends Model_User {}

class Model_User_Magic extends MagicModel {

	public static function fields()
	{
		return parent::fields() + array(
			'name'    => new Field('name'),
			'friends' => new OneToMany('friends', array(
				'mapper_suffix' => 'User',
				'model_suffix'  => 'Magic',
			)),
		);
	}

}

class Model_User_MockableRelation extends MagicModel {

	public static $relation;

	public static function fields()
	{
		return parent::fields() + array(
			'relation' => call_user_func(static::$relation),
		);
	}

	public function __container(Container $container = NULL)
	{
		if ($this->__container === NULL)
		{
			parent::__container(new Container('Array'));
		}

		return parent::__container();
	}

}

class Relationship_Mock extends Relationship
	implements Relation\Add, Relation\Remove, Relation\Set {

	protected $method_called;

	public function method_called()
	{
		return $this->method_called;
	}

	public function find(Container $container, $value)
	{
		$this->method_called = 'find';
	}

	public function add(Container $container, $object, $relation)
	{
		$this->method_called = 'add';
	}

	public function remove(Container $container, $object, $relation)
	{
		$this->method_called = 'remove';
	}

	public function set(Container $container, $object, $relation)
	{
		$this->method_called = 'set';
	}

}

class Mapper_Mongo_User extends MongoMapper {}
class Mapper_Array_User extends MockMapper {}

class Mapper_ConnectionTest_User implements ConnectionSetGet {

	protected $connection;

	public function connection(Connection $connection = NULL)
	{
		if ($connection === NULL)
		{
			return $this->connection;
		}

		$this->connection = $connection;
	}

	public function identities() {}
	public function config() {}

}

class Connection_ConnectionTest implements Connection {

	public function get() {}

}