<?php

namespace Modules\Entitizer\Collection {

	use Modules\Entitizer;

	trait Menuitems {

		protected static $order_by = ['position' => 'ASC', 'id' => 'ASC'];

		# Init collection

		protected function init() {

			$this->config->add('active', false, function (bool $active) {

				return ($active ? "ent.active = 1" : '');
			});
		}
	}
}
