<?php

namespace Modules\Auth\Handler {

	use Frames, Modules\Auth, Request;

	class Login extends Frames\Admin\Area\Auth {

		protected $title = 'TITLE_AUTH_LOGIN';

		# Handle request

		protected function handle() {

			if (Auth::initial()) Request::redirect(INSTALL_PATH . '/admin/register');

			return (new Auth\Action\Login)->handle();
		}
	}
}
