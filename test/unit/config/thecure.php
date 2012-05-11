<?php

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