<?php
/**
 * Default configuration file
 *
 * @package     TheCure
 * @category    Config
 * @copyright   Gignite, 2012
 * @license     MIT
 */
return array(
	'factory' => array(
		'prefixes' => array(
			'connection' => 'Connections',
			'mapper'     => 'Mappers',
			'model'      => 'Models',
		),
		'separator' => '\\',
	),

	'mappers' => array(
		'Mongo' => array(
			'db'               => 'demo',
			'connection'       => 'mongodb://127.0.0.1',
			'query_options'    => array(
				'profiling' => TRUE,
				'safe'      => TRUE,
			),
			'connection_class' => 'TheCure\Connections\Mongo',
		),
	),
);