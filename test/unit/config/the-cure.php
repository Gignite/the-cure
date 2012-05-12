<?php
/**
 * Test configuration file
 *
 * @package     TheCure
 * @category    Config
 * @copyright   Gignite, 2012
 * @license     MIT
 */
return array(
	'factory' => array(
		'prefixes' => array(
			'model'  => 'TheCure\Models',
			'mapper' => 'TheCure\Mappers',
		),
	),
	'mappers' => array(
		'Mock' => array(),
		'ConnectionTest' => array(
			'connection_class' => 'TheCure\Connections\ConnectionTest',
		),
	),
);