<?php

namespace System\Modules\Entitizer\Definition\User {

	use System\Modules\Entitizer;

	class Secret extends Entitizer\Utils\Definition {

		use Entitizer\Common\User\Secret;

		# Define presets

		protected function define() {

			# Add params

			$this->textual      ('code',            true, 40, true, true, true);
			$this->textual      ('ip',              true, 255, false, true, false);
			$this->numeric      ('time',            false, 10, 0, true, false);
		}
	}
}
