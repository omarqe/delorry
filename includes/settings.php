<?php

return [
	// Price per kilometer (in RM)
	'price' => 1.50,

	// Maximum booking days (60 days by default or two months ahead)
	'max_booking_days' => 60,

	// Available places for DeLorry service
	'places' => [
		'PEN' => [
			'label' => 'Penang',
			'distance' => [
				'KUL' => 357.3,
				'KED' => 431.9,
				'KEL' => 342.7
			]
		],
		'KUL' => [
			'label' => 'Kuala Lumpur',
			'distance' => [
				'PEN' => 357.3,
				'KED' => 431.9,
				'KEL' => 438.6
			]
		],
		'KED' => [
			'label' => 'Kedah',
			'distance' => [
				'KUL' => 431.9,
				'PEN' => 114.8,
				'KEL' => 356.0
			]
		],
		'KEL' => [
			'label' => 'Kelantan',
			'distance' => [
				'PEN' => 342.7,
				'KED' => 356.0,
				'KUL' => 438.6
			]
		]
	],

	'vehicles' => ['Van', 'Lorry', 'Pickup']
];