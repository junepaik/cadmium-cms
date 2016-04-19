<?php

namespace Modules\Entitizer\Lister {

	use Modules\Entitizer, Template;

	class Widgets extends Entitizer\Utils\Lister {

		use Entitizer\Common\Widget;

		# Lister configuration

		protected static $link = '/admin/content/widgets';

		protected static $naming = 'title';

		protected static $display = CONFIG_ADMIN_WIDGETS_DISPLAY;

		protected static $view_main = 'Blocks\Entitizer\Widgets\Lister\Main';

		protected static $view_item = 'Blocks\Entitizer\Widgets\Lister\Item';

		protected static $view_ajax_main = '';

		protected static $view_ajax_item = '';

		# Add additional data for specific entity

		protected function processEntity() {}

		# Add item additional data

		protected function processItem(Template\Asset\Block $view, Entitizer\Dataset\Widget $widget) {

			$view->class = (!$widget->active ? 'inactive' : '');

			$view->name = $widget->name;
		}
	}
}
