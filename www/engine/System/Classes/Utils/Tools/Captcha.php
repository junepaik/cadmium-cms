<?php

namespace System\Utils\Tools {

	use Explorer, Headers, Number;

	class Captcha {

		private $captcha = null, $width = 0, $height = 0;

		# Allocate color

		private function allocateColor($color) {

			if (!is_resource($this->captcha)) return false;

			if (null === $color) $color = [0, 0, 0]; else if (!is_array($color) || (count($color) !== 3)) return false;

			foreach (array_values($color) as $key => $value) $color[$key] = Number::format($value, 0, 255);

			# ------------------------

			return imagecolorallocate($this->captcha, $color[0], $color[1], $color[2]);
		}

		# Constructor

		public function __construct($width, $height, $bg_color = null) {

			$width = intval($width); $height = intval($height);

			if (false === ($captcha = imagecreatetruecolor($width, $height))) return;

			$this->captcha = $captcha; $this->width = $width; $this->height = $height;

			if (false !== ($bg_color = $this->allocateColor($bg_color))) {

				imagefilledrectangle($captcha, 0, 0, ($width - 1), ($height - 1), $bg_color);
			}
		}

		# Draw text

		public function text($font, $size, $indent, $step, $text, $color = null) {

			if (!is_resource($this->captcha)) return false;

			$font = strval($font); $size = ((($size = intval($size)) < 8) ? 8 : $size);

			$indent = intval($indent); $step = intval($step); $text = strval($text);

			if (!Explorer::isFile($font)) return false;

			if (false === ($color = $this->allocateColor($color))) return false;

			$base = (floor(($this->height - $size) / 2) + $size);

			foreach (str_split($text) as $index => $character) {

				$angle = mt_rand(-10, 10); $x = ($indent + ($index * $step)); $y = mt_rand(($base - 5), ($base + 5));

				imagettftext($this->captcha, $size, $angle, $x, $y, $color, $font, $character);
			}

			# ------------------------

			return true;
		}

		# Draw noise

		public function noise($rate, $color = null) {

			if (!is_resource($this->captcha)) return false;

			$rate = intval($rate);

			if (false === ($color = $this->allocateColor($color))) return false;

			for ($x = 0; $x < $this->width; $x++) {

				for ($y = 0; $y < $this->height; $y++) {

					if (mt_rand(1, $rate) === 1) imagesetpixel($this->captcha, $x, $y, $color);
				}
			}

			# ------------------------

			return true;
		}

		# Draw lines

		public function lines($count, $color = null) {

			if (!is_resource($this->captcha)) return false;

			$count = intval($count);

			if (false === ($color = $this->allocateColor($color))) return false;

			for ($i = 0; $i < $count; $i++) {

				$point_0 = [0, mt_rand(0, ($this->height - 1))];

				$point_1 = [($this->width - 1), mt_rand(0, ($this->height - 1))];

				imageline($this->captcha, $point_0[0], $point_0[1], $point_1[0], $point_1[1], $color);
			}

			# ------------------------

			return true;
		}

		# Output PNG image

		public function output($quality = 4) {

			if (!is_resource($this->captcha)) return false;

			if (($quality = intval($quality)) > 9) $quality = 9;

			Headers::nocache(); Headers::content(MIME_TYPE_PNG);

			imagepng($this->captcha, null, $quality); imagedestroy($this->captcha);

			# ------------------------

			return true;
		}
	}
}
