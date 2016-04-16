<?php

namespace {

	abstract class Language {

		protected static $phrases = [];

		# Add phrase to list

		private static function addPhrase(string $name, string $value) {

			if (preg_match(REGEX_LANGUAGE_PHRASE_NAME, $name)) self::$phrases[$name] = $value;
		}

		# Load phrases file

		public static function load(string $file_name) {

			if (!is_array($phrases = Explorer::php($file_name))) return false;

			foreach ($phrases as $name => $value) self::addPhrase($name, $value);

			# ------------------------

			return true;
		}

		# Get phrase by name

		public static function get(string $name) {

			return (self::$phrases[$name] ?? false);
		}
	}
}
