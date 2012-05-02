<?php

return array(
	'factory' => array(
		'prefixes' => array(
			'model'  => 'Gignite\TheCure\Models',
			'mapper' => 'Gignite\TheCure\Mappers',
		),
	),
	'mappers' => array(
		'Mock' => array(),
		'ConnectionTest' => array(
			'connection_class' => 'Gignite\TheCure\Connections\ConnectionTest',
		),
	),
);