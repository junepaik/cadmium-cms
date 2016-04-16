<?php

namespace {

	abstract class Arr {

		# Get array value by path

		public static function get(array $array, array $path) {

			$value = false;

			foreach ($path as $item) if (isset($array[$item])) $value = ($array = $array[$item]); else return false;

			# ------------------------

			return $value;
		}

		# Select a set of scalar elements from array

		public static function select(array $array, array $keys) {

			$result = [];

			foreach ($keys as $key) if (is_scalar($key)) {

				$result[$key] = ((isset($array[$key]) && is_scalar($array[$key])) ? $array[$key] : false);
			}

			# ------------------------

			return $result;
		}

		# Transform associative array to indexed

		public static function index(array $array, string $key_name, string $value_name) {

			$result = [];

			foreach ($array as $key => $value) $result[] = [$key_name => $key, $value_name => $value];

			# ------------------------

			return $result;
		}

		# Sort array by subvalue

		public static function sortby(array $array, $sub_key, bool $descending = false) {

			$select_key = function($element) use($sub_key) {

				return ((is_array($element) && isset($element[$sub_key])) ? $element[$sub_key] : false);
			};

			$result = []; $column = array_map($select_key, $array);

			if (!$descending) asort($column); else arsort($column);

			foreach (array_keys($column) as $key) $result[$key] = $array[$key];

			# ------------------------

			return $result;
		}

		# Get random value

		public static function random(array $array) {

			return $array[array_rand($array)];
		}

		# Encode array

		public static function encode(array $array) {

			return sha1(serialize($array));
		}
	}
}
