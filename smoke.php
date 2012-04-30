<?php
/**
 * Smoke test
 * 
 * @package     TheCure
 * @category    Test
 * @author      Luke Morton
 * @copyright   Gignite, 2012
 */
namespace Models;

define('EXT', '.php');
define('APPPATH', __DIR__.'/test/');
define('SYSPATH', APPPATH.'/system/');

error_reporting(E_ALL | E_STRICT);

require SYSPATH.'classes/Kohana/Core.php';
require SYSPATH.'classes/Kohana.php';

spl_autoload_register(array('Kohana', 'auto_load'));

\I18n::lang('en-gb');

\Kohana::$config = new \Kohana_Config;
\Kohana::$config->attach(new \Config_File);

\Kohana::modules(array('the-cure' => __DIR__.'/'));

use Gignite\TheCure\Models\Magic as MagicModel;
use Gignite\TheCure\Field;
use Gignite\TheCure\Relationships\OneToMany as OneToManyRelationship;
use Gignite\TheCure\Mapper\Container;

class User extends MagicModel {

	public static function fields()
	{
		return array(
			'name' => new Field('name'),
			'age'  => new Field('age'),
			'friends' => new OneToManyRelationship('friends', array(
				'mapper_suffix' => 'User',
				// 'model_suffix'  => 'Admin',
			)),
		);
	}

}

$user = new User;
$user->__container(new Container('Mock'));
// Or try with mongo!!
//     $user->__container(new Container('Mongo'));
$user->name('Luke');
var_dump($user->name());

$bob = new User;
$bob->name('Bob');
$user->add_friends($bob);
var_dump($user->friends()->current()->name());
var_dump($user->friends()->count());

$user->remove_friends($bob);
var_dump($user->friends()->count());