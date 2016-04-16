<?php

namespace {

	abstract class Mime extends Range {

		protected static $range = [];

		# Check mime type

		private static function checkType(string $extension, string $type) {

			if (false === ($mime = self::get($extension))) return false;

			return (preg_match(('/^' . $type . '\//'), $mime) ? true : false);
		}

		# Autoloader

		public static function __autoload() {

			self::init(DIR_DATA . 'Mime.php');
		}

		# Check if extension is image

		public static function isImage(string $extension) {

			return self::checkType($extension, 'image');
		}

		# Check if extension is audio

		public static function isAudio(string $extension) {

			return self::checkType($extension, 'audio');
		}

		# Check if extension is video

		public static function isVideo(string $extension) {

			return self::checkType($extension, 'video');
		}
	}
}
