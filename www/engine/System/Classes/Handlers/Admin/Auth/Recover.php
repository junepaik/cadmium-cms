<?php

namespace System\Handlers\Admin\Auth {

	use Error, System, System\Forms, System\Views, System\Utils\Ajax, System\Utils\Auth, System\Utils\Config;
	use System\Utils\Entity, System\Utils\Extend, System\Utils\Lister, System\Utils\Messages;
	use System\Utils\Pagination, System\Utils\Requirements, System\Utils\Utils;

	use Agent, Arr, Cookie, Date, DB, Explorer, Form, Geo\Country, Geo\Timezone;
	use Headers, Language, Mailer, Number, Request, Session, String, Tag, Template, Url, Validate;

	class Recover extends System\Frames\Admin\Component\Auth {

		# Handle request

		protected function handle() {

			if ($this->initial()) Request::redirect('/admin/register');

			if (false === ($code = Auth::secret(true))) Request::redirect('/admin/reset');

			# Create form

			$form = new Forms\Recover(true);

			if ($form->handle()) Request::redirect('/admin/login?submitted=recover');

			# Create contents block

			$contents = new Views\Admin\Blocks\Contents\Auth\Recover($code);

			$form->implement($contents);

			# Fill template

			$this->setTitle(Language::get('TITLE_AUTH_RECOVER'));

			$this->setContents($contents);

			# ------------------------

			return true;
		}
	}
}
