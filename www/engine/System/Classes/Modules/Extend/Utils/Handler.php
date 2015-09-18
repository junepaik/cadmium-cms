<?php

namespace System\Modules\Extend\Utils {

	use System\Modules\Config, System\Utils\View, Ajax, Arr, Form, Language, Request, Template;

	trait Handler {

		private $section = '', $items = [];

		# Get section

		private function getHandlerSection() {

			return ((strcasecmp(Request::get('list'), SECTION_ADMIN) === 0) ? SECTION_ADMIN : SECTION_SITE);
		}

		# Get items

		private function getHandlerItems() {

			$items = self::items($this->section); $active = key($items);

            $name = Config::get(self::$param[$this->section]); $default = self::$default[$this->section];

			if (self::valid($name) && isset($items[$name])) $active = $name;

			else if (self::valid($default) && isset($items[$default])) $active = $default;

			foreach (array_keys($items) as $name) $items[$name]['active'] = ($name === $active);

			# ------------------------

			return Arr::subvalSort($items, 'title');
		}

		# Get sections loop

		private function getSectionsLoop() {

			$loop = []; $sections = [];

			$sections[SECTION_SITE]     = Language::get('SECTION_SITE');
			$sections[SECTION_ADMIN]    = Language::get('SECTION_ADMIN');

			foreach ($sections as $name => $title) {

				$class = (($name === $this->section) ? 'active item' : 'item');

				$loop[] = ['name' => strtolower($name), 'title' => $title, 'class' => $class];
			}

			return $loop;
		}

		# Get contents

		private function getContents() {

			$contents = View::get(self::$view_main);

			# Set sections

			$contents->sections = $this->getSectionsLoop();

			$contents->section = $this->section;

			# Set items

			$items = Template::group();

			foreach ($this->items as $name => $extension) {

				$items->add($item = View::get(self::$view_item));

				foreach (self::$data as $name) $item->$name = $extension[$name];

				$item->is_active = boolval($extension['active']);
			}

			if ($items->count()) $contents->block('items', $items);

			# ------------------------

			return $contents;
		}

        # Handle ajax request

		private function handleAjax() {

			$ajax = Ajax::dataset();

			# Create form

			$form = new Form('ajax'); $form->virtual('name');

			# Post form

			if (false === ($post = $form->post())) return $ajax->error();

			# Save configuration

			$param = self::$param[$this->section];

			if (false === Config::set($param, $post['name'])) return $ajax->error(Language::get(self::$error_name));

			if (false === Config::save()) return $ajax->error(Language::get(self::$error_save));

			# ------------------------

			return $ajax;
		}

		# Handle common request

		public function handle() {

			$this->section = $this->getHandlerSection();

			$this->items = $this->getHandlerItems();

            if (Request::isAjax()) return $this->handleAjax();

			# ------------------------

			return $this->getContents();
		}
	}
}
