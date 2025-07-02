	<?php

	return [

		/*
		|--------------------------------------------------------------------------
		| Allowed countries to be loaded.
		| Leave it empty to load all countries else include the country iso2
		| value in the allowed_countries array.
		|--------------------------------------------------------------------------
		*/

		'allowed_countries' => [
			'BZ', // Belice
			'CR', // Costa Rica
			'SV', // El Salvador
			'GT', // Guatemala
			'HN', // Honduras
			'NI', // Nicaragua
			'PA', // Panamá
		],

		/*
		|--------------------------------------------------------------------------
		| Disallowed countries to not be loaded.
		| Leave it empty to allow all countries to be loaded else include the
		| country iso2 value in the disallowed_countries array.
		|--------------------------------------------------------------------------
		*/

		'disallowed_countries' => [],

		/*
		|--------------------------------------------------------------------------
		| Supported locales.
		|--------------------------------------------------------------------------
		*/

		'accepted_locales' => [
			'ar',
			'az',
			'bn',
			'br',
			'de',
			'en',
			'es',
			'fa',
			'fr',
			'hr',
			'it',
			'ja',
			'kr',
			'nl',
			'pl',
			'pt',
			'ro',
			'ru',
			'tr',
			'zh',
		],

		/*
		|--------------------------------------------------------------------------
		| Enabled modules.
		| The cities module depends on the states module.
		|--------------------------------------------------------------------------
		*/

		'modules' => [
			'states' => true,
			'cities' => true,
			'timezones' => false,
			'currencies' => false,
			'languages' => false,
		],

		/*
		|--------------------------------------------------------------------------
		| Routes.
		|--------------------------------------------------------------------------
		*/

		'routes' => false,

		/*
		|--------------------------------------------------------------------------
		| Connection.
		|--------------------------------------------------------------------------
		*/

		'connection' => env('WORLD_DB_CONNECTION', env('DB_CONNECTION')),

		/*
		|--------------------------------------------------------------------------
		| Migrations.
		|--------------------------------------------------------------------------
		*/

		'migrations' => [
			'countries' => [
				'table_name' => 'countries',
				'optional_fields' => [],
			],
			'states' => [
				'table_name' => 'states',
				'optional_fields' => [],
			],
			'cities' => [
				'table_name' => 'cities',
				'optional_fields' => [],
			],
			'timezones' => [
				'table_name' => 'timezones',
			],
			'currencies' => [
				'table_name' => 'currencies',
			],
			'languages' => [
				'table_name' => 'languages',
			],
		],

		/*
		|--------------------------------------------------------------------------
		| Fully qualified class names for package models.
		| You can extend package models with your custom ones.
		|--------------------------------------------------------------------------
		*/

		'models' => [
			'cities' => \Nnjeim\World\Models\City::class,
			'countries' => \Nnjeim\World\Models\Country::class,
			'currencies' => \Nnjeim\World\Models\Currency::class,
			'languages' => \Nnjeim\World\Models\Language::class,
			'states' => \Nnjeim\World\Models\State::class,
			'timezones' => \Nnjeim\World\Models\Timezone::class,
		],

	];
