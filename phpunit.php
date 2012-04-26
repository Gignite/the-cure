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
define('SYSPATH', __DIR__.'/../../../system/');

error_reporting(E_ALL | E_STRICT);

require SYSPATH.'classes/kohana/core.php';
require SYSPATH.'classes/kohana.php';

spl_autoload_register(array('Kohana', 'auto_load'));

I18n::lang('en-gb');

Kohana::$config = new Kohana_Config;
Kohana::$config->attach(new Config_File);

Kohana::modules(array('the-cure' => __DIR__.'/'));

class Model_User extends Model {}
class Model_User_Admin extends Model_User {}

class Model_User_Magic extends Model_Magic {

	public static function fields()
	{
		return parent::fields() + array(
			'name'    => new Field('name'),
			'friends' => new Relationship_OneToMany('friends', array(
				'mapper_suffix' => 'User',
				'model_suffix'  => 'Magic',
			)),
		);
	}

}

class Model_User_MockableRelation extends Model_Magic {

	public static $relation;

	public static function fields()
	{
		return parent::fields() + array(
			'relation' => call_user_func(static::$relation),
		);
	}

	public function __container(MapperContainer $container = NULL)
	{
		if ($this->__container === NULL)
		{
			parent::__container(new MapperContainer('Array'));
		}

		return parent::__container();
	}

}

class Relationship_Mock extends Relationship
	implements Relationship_AddRemove {

	protected $method_called;

	public function method_called()
	{
		return $this->method_called;
	}

	public function find(MapperContainer $container, $value)
	{
		$this->method_called = 'find';
	}

	public function add(MapperContainer $container, $object, $relation)
	{
		$this->method_called = 'add';
	}

	public function remove(MapperContainer $container, $object, $relation)
	{
		$this->method_called = 'remove';
	}

}

class Mapper_Mongo_User extends Mapper_Mongo {}
class Mapper_Array_User extends Mapper_Array {}

class Mapper_ConnectionTest_User implements MapperConnection {

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