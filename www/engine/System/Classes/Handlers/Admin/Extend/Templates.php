<?php

namespace System\Handlers\Admin\Extend {

	use System, System\Modules\Extend, Language;

	class Templates extends System\Frames\Admin\Component\Panel {

		# Handle request

		protected function handle() {

			$this->title = Language::get('TITLE_EXTEND_TEMPLATES');

			return Extend\Handler\Templates::handle();
		}
	}
}
