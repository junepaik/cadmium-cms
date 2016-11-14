<?php

/**
 * @package Framework\Form
 * @author Anton Romanov
 * @copyright Copyright (c) 2015-2016, Anton Romanov
 * @link http://cadmium-cms.com
 */

namespace Form\Field {

	use Form, Validate;

	class Checkbox extends Form\Field {

		# Field default value

		protected $value = false;

		/**
		 * Constructor
		 */

		public function __construct(Form $form, string $key, string $value = '') {

			# Init field

			self::init($form, $key);

			# Set value

			$this->setValue($value);
		}

		/**
		 * Set a value
		 *
		 * @return the result value
		 */

		public function setValue(string $value) {

			return ($this->value = Validate::boolean($value));
		}

		/**
		 * Get a block
		 */

		public function getBlock() {

			$tag = $this->getTag('input');

			$tag->setAttribute('type', 'checkbox');

			if ($this->value) $tag->setAttribute('checked', 'checked');

			# ------------------------

			return $this->toBlock($tag);
		}
	}
}
