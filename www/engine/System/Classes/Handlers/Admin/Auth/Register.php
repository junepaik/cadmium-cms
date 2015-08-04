<?php

namespace System\Handlers\Admin\Auth {

	use Error, System, System\Utils\Ajax, System\Utils\Auth, System\Utils\Config, System\Utils\Entity;
	use System\Utils\Extend, System\Utils\Lister, System\Utils\Messages, System\Utils\Pagination;
	use System\Utils\Requirements, System\Utils\Utils;

	use Agent, Arr, Cookie, Date, DB, Explorer, Form, Geo\Country, Geo\Timezone;
	use Headers, Language, Mailer, Number, Request, Session, String, Tag, Template, Url, Validate;

	class Register extends System\Frames\Admin\Handler {

		private $form = null;

		# Get contents

		private function getContents() {

			$contents = Template::block('Contents/Auth/Register');

			# Set form

			foreach ($this->form->fields() as $name => $field) $contents->block(('field_' . $name), $field);

			# ------------------------

			return $contents;
		}

		# Handle request

		protected function handle() {

			# Create form

			$this->form = new Form('register'); $fieldset = $this->form->fieldset();

			# Add form fields

			$fieldset->text			('name', '', CONFIG_USER_NAME_MAX_LENGTH, Language::get('USER_FIELD_NAME'), FORM_FIELD_REQUIRED);

			$fieldset->password		('password', '', CONFIG_USER_PASSWORD_MAX_LENGTH, Language::get('USER_FIELD_PASSWORD'), FORM_FIELD_REQUIRED);

			$fieldset->password		('password_retype', '', CONFIG_USER_PASSWORD_MAX_LENGTH, Language::get('USER_FIELD_PASSWORD_RETYPE'), FORM_FIELD_REQUIRED);

			$fieldset->text			('email', '', CONFIG_USER_EMAIL_MAX_LENGTH, Language::get('USER_FIELD_EMAIL'), FORM_FIELD_REQUIRED);

			$fieldset->captcha		('captcha', '', CONFIG_CAPTCHA_LENGTH, Language::get('USER_FIELD_CAPTCHA'), FORM_FIELD_REQUIRED);

			# Post form

			if (false !== ($post = $this->form->post())) {

				if ($this->form->errors()) Messages::error(Language::get('FORM_ERROR_REQUIRED'));

				else if (true !== ($result = Auth::register($post))) Messages::error(Language::get($result));

				else Request::redirect('/admin/login?submitted=register');
			}

			# Fill template

			$this->setTitle(Language::get('TITLE_AUTH_REGISTER'));

			$this->setContents($this->getContents());

			# ------------------------

			return true;
		}
	}
}
