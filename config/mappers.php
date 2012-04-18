<?php

return array(
	'mongo' => array(
		'db'            => 'demo',
		'connection'    => 'mongodb://127.0.0.1',
		'query_options' => array(
			'profiling' => TRUE,
			'safe'      => TRUE,
		),
	),
);