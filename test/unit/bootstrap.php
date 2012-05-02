<?php
/**
 * A PHPUnit (unit test) bootstrap
 *
 * @package     TheCure
 * @category    Test
 * @copyright   Gignite, 2012
 */
define('EXT', '.php');
define('APPPATH', __DIR__.'/');
define('SYSPATH', APPPATH.'/../system/');

error_reporting(E_ALL | E_STRICT);

require SYSPATH.'classes/Kohana/Core.php';
require SYSPATH.'classes/Kohana.php';

spl_autoload_register(array('Kohana', 'auto_load'));

I18n::lang('en-gb');

Kohana::$config = new Kohana_Config;
Kohana::$config->attach(new Config_File);

Kohana::modules(array('the-cure' => APPPATH.'/../../'));