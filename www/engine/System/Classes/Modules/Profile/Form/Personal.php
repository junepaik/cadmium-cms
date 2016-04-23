<?php

namespace Modules\Profile\Form {

	use Modules\Auth, Utils\Form, Utils\Range, Geo\Country, Geo\Timezone, Language;

	class Personal extends Form {

		# Constructor

		public function __construct() {

			parent::__construct('edit');

			# Add fields

			$this->addText('email', Auth::user()->email, FORM_FIELD_TEXT, CONFIG_USER_EMAIL_MAX_LENGTH, ['required' => true]);

			$this->addText('first_name', Auth::user()->first_name, FORM_FIELD_TEXT, CONFIG_USER_FIRST_NAME_MAX_LENGTH);

			$this->addText('last_name', Auth::user()->last_name, FORM_FIELD_TEXT, CONFIG_USER_LAST_NAME_MAX_LENGTH);

			$this->addSelect('sex', Auth::user()->sex, Range\Sex::array());

			$this->addText('city', Auth::user()->city, FORM_FIELD_TEXT, CONFIG_USER_CITY_MAX_LENGTH);

			$this->addSelect('country', Auth::user()->country, Country::array(), Language::get('COUNTRY_NOT_SELECTED'), ['search' => true]);

			$this->addSelect('timezone', Auth::user()->timezone, Timezone::array(), null, ['search' => true]);
		}
	}
}
