<?php

namespace Modules\Entitizer\Handler {

	use Modules\Entitizer, Template;

	class Page extends Entitizer\Utils\Handler {

		use Entitizer\Common\Page;

		# Handler configuration

		protected static $controller = 'Modules\Entitizer\Controller\Page';

		protected static $link = '/admin/content/pages';

		protected static $naming = 'title', $naming_new = '';

		protected static $form_class = 'Modules\Entitizer\Form\Page';

		protected static $message_success_save = 'PAGE_SUCCESS_SAVE';

		protected static $message_error_move = 'PAGE_ERROR_MOVE';

		protected static $message_error_remove = 'PAGE_ERROR_REMOVE';

		protected static $view = 'Blocks\Entitizer\Pages\Main';

		# Add parent additional data

		protected function processEntityParent(Template\Asset\Block $parent) {

			if ((0 === $this->parent->id) || !$this->parent->active) {

				$parent->block('browse')->disable(); $parent->block('browse_disabled')->enable();

			} else $parent->block('browse')->link = $this->parent->link;
		}

		# Add additional parent data for specific entity

		protected function processEntity(Template\Asset\Block $contents) {

			if (!$this->create && $this->parent->locked) $contents->block('locked')->enable();
		}
	}
}
