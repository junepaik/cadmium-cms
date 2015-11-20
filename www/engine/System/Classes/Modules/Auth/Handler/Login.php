<?php

namespace System\Modules\Auth\Handler {

	use System\Modules\Auth, System\Utils\Messages, Language, Request;

	class Login extends Auth\Utils\Handler {

		protected $view = 'Blocks\Auth\Login';

		# Handle request

		public function handle() {

			# Create form

			$this->form = new Auth\Form\Login();

			# Submit form

			if ($this->form->submit(new Auth\Controller\Login())) {

				Request::redirect(INSTALL_PATH . (Auth::admin() ? '/admin' : '/profile'));

			} else if (Request::get('submitted') === 'reset') {

				Messages::success(Language::get('USER_SUCCESS_RESET_TEXT'), Language::get('USER_SUCCESS_RESET'));

			} else if (Request::get('submitted') === 'recover') {

				Messages::success(Language::get('USER_SUCCESS_RECOVER_TEXT'), Language::get('USER_SUCCESS_RECOVER'));

			} else if (Request::get('submitted') === 'register') {

				Messages::success(Language::get('USER_SUCCESS_REGISTER_TEXT'), Language::get('USER_SUCCESS_REGISTER'));

			}

			# ------------------------

			return $this->getContents();
		}
	}
}
