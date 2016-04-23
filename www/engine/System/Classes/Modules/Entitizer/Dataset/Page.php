<?php

namespace Modules\Entitizer\Dataset {

	use Modules\Entitizer, Modules\Settings;

	class Page extends Entitizer\Utils\Dataset {

		use Entitizer\Common\Page;

		# Init dataset

		protected function init() {

			$this->addHandler('active', function (array $data) {

				return (($data['visibility'] === VISIBILITY_PUBLISHED) && !$data['locked']);
			});

			$this->addHandler('link', function (array $data) {

				if ('' === $data['slug']) return '';

				return (INSTALL_PATH . '/' . $data['slug']);
			});

			$this->addHandler('canonical', function (array $data) {

				if ('' === $data['slug']) return '';

				return (Settings::get('system_url') . (($data['id'] !== 1) ? ('/' . $data['slug']) : ''));
			});
		}
	}
}
