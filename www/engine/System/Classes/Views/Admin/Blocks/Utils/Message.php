<?php

namespace System\Views\Admin\Blocks\Utils {

	use System\Views;

	class Message extends Views\Templatable {

		# Constructor

        public function __construct() {

            parent::__construct(SECTION_ADMIN, 'Blocks/Utils/Message.tpl');
        }
    }
}
