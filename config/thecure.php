<?php

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
		'mongo' => array(
			'db'            => 'demo',
			'connection'    => 'mongodb://127.0.0.1',
			'query_options' => array(
				'profiling' => TRUE,
				'safe'      => TRUE,
			),
		),
	),
);