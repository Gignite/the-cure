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
class Mapper_Mongo_User extends Mapper_Mongo {}