<?php

return array(
	'factory' => array(
		'prefixes' => array(
			'mapper' => 'Mappers',
			'model'  => 'Models',
		),
		'separator' => '\\',
	),

	'mappers' => array(
		'mongo' => array(
			'db'               => 'demo',
			'connection'       => 'mongodb://127.0.0.1',
			'query_options'    => array(
				'profiling' => TRUE,
				'safe'      => TRUE,
			),
			'connection_class' => 'Gignite\Connections\Mongo',
		),
	),
);