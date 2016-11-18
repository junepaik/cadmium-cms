<?php

namespace Modules {

	use Utils\Validate, Request, Session;

	abstract class Auth {

		private static $admin = false, $user = null;

		# Get auth

		private static function getAuth(string $code, string $type, int $lifetime) {

			if (!($auth = Entitizer::get($type))->init($code, 'code')) return false;

			if (($auth->ip !== REQUEST_CLIENT_IP) || ($auth->time < (REQUEST_TIME - $lifetime))) return false;

			# ------------------------

			return $auth;
		}

		# Get user

		private static function getUser(int $id) {

			if (0 === ($user = Entitizer::get(TABLE_USERS, $id))->id) return false;

			if ($user->rank < (self::$admin ? RANK_ADMINISTRATOR : RANK_USER)) return false;

			# ------------------------

			return (self::$user = $user);
		}

		# Authorize with session code

		public static function init(string $section) {

			self::$admin = ($section === SECTION_ADMIN); self::$user = Entitizer::get(TABLE_USERS);

			# Check session code

			if (false === ($code = Validate::authCode(Session::get('code') ?? ''))) return false;

			# Get auth

			$type = TABLE_USERS_SESSIONS; $lifetime = CONFIG_USER_SESSION_LIFETIME;

			if (false === ($session = self::getAuth($code, $type, $lifetime))) return false;

			# Get user

			if (false === ($user = self::getUser($session->id))) return false;

			# Update session

			$session->edit(['time' => REQUEST_TIME]);

			# Update activity

			$user->edit(['time_logged' => REQUEST_TIME]);

			# ------------------------

			return true;
		}

		# Authorize with secret code

		public static function secret() {

			if ((null === self::$user) || (0 !== self::$user->id)) return false;

			# Check secret code

			if (false === ($code = Validate::authCode(Request::get('code')))) return false;

			# Get auth

			$type = TABLE_USERS_SECRETS; $lifetime = CONFIG_USER_SECRET_LIFETIME;

			if (false === ($secret = self::getAuth($code, $type, $lifetime))) return false;

			# Get user

			if (false === ($user = self::getUser($secret->id))) return false;

			# ------------------------

			return $code;
		}

		# Delete session

		public static function logout() {

			if ((null === self::$user) || (0 === self::$user->id)) return false;

			# Remove session

			Entitizer::get(TABLE_USERS_SESSIONS, self::$user->id)->remove();

			# Remove session variable

			Session::delete('code');

			# ------------------------

			return true;
		}

		# Check if initial registration required

		public static function initial() {

			return (0 === Informer::countEntries(TABLE_USERS, true));
		}

		# Check if authorized

		public static function check() {

			return ((null !== self::$user) && (0 !== self::$user->id));
		}

		# Check if mode is set to admin

		public static function admin() {

			return self::$admin;
		}

		# Return user object

		public static function user() {

			return self::$user;
		}
	}
}
