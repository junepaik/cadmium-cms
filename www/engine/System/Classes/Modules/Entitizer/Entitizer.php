<?php

namespace System\Modules {

	use Exception;

	abstract class Entitizer {

		const ERROR_TYPE                    = 'Invalid entity type';
		const ERROR_DEFINITION              = 'Entity definition does not exists';
		const ERROR_LISTER                  = 'Entity lister does not exists';
		const ERROR_CONTROLLER              = 'Entity controller does not exists';

		private static $cache = [];

		# Registred types

		private static $types = [

			ENTITY_TYPE_PAGE                => 'System\Modules\Entitizer\Entity\Page',
			ENTITY_TYPE_MENUITEM            => 'System\Modules\Entitizer\Entity\Menuitem',
			ENTITY_TYPE_USER                => 'System\Modules\Entitizer\Entity\User',
			ENTITY_TYPE_USER_SECRET         => 'System\Modules\Entitizer\Entity\User\Secret',
			ENTITY_TYPE_USER_SESSION        => 'System\Modules\Entitizer\Entity\User\Session'
		];

		# Definitions

		private static $definitions = [

			ENTITY_TYPE_PAGE                => 'System\Modules\Entitizer\Definition\Page',
			ENTITY_TYPE_MENUITEM            => 'System\Modules\Entitizer\Definition\Menuitem',
			ENTITY_TYPE_USER                => 'System\Modules\Entitizer\Definition\User',
			ENTITY_TYPE_USER_SECRET         => 'System\Modules\Entitizer\Definition\User\Secret',
			ENTITY_TYPE_USER_SESSION        => 'System\Modules\Entitizer\Definition\User\Session'
		];

		# Listers

		private static $listers = [

			ENTITY_TYPE_PAGE                => 'System\Modules\Entitizer\Lister\Pages',
			ENTITY_TYPE_MENUITEM            => 'System\Modules\Entitizer\Lister\Menuitems',
			ENTITY_TYPE_USER                => 'System\Modules\Entitizer\Lister\Users'
		];

		# Controlles

		private static $controllers = [

			ENTITY_TYPE_PAGE                => 'System\Modules\Entitizer\Controller\Page',
			ENTITY_TYPE_MENUITEM            => 'System\Modules\Entitizer\Controller\Menuitem',
			ENTITY_TYPE_USER                => 'System\Modules\Entitizer\Controller\User'
		];

		# Create new entity

		public static function create($type, $id = 0) {

			$type = strval($type); $id = intval($id);

			if (!isset(self::$types[$type])) throw new Exception\General(self::ERROR_TYPE);

			if (isset(self::$cache[$type][$id]) && (0 !== self::$cache[$type][$id]->id)) return self::$cache[$type][$id];

			$entity = new self::$types[$type]; $entity->init($id);

			# ------------------------

			return $entity;
		}

		# Create new entity definition

		public static function definition($type) {

			$type = strval($type);

			if (!isset(self::$definitions[$type])) throw new Exception\General(self::ERROR_DEFINITION);

			# ------------------------

			return new self::$definitions[$type];
		}

		# Create new entity lister

		public static function lister($type) {

			$type = strval($type);

			if (!isset(self::$listers[$type])) throw new Exception\General(self::ERROR_LISTER);

			# ------------------------

			return new self::$listers[$type];
		}

		# Create new entity controller

		public static function controller($type, $id = 0) {

			$type = strval($type); $id = intval($id);

			if (!isset(self::$controllers[$type])) throw new Exception\General(self::ERROR_CONTROLLER);

			# ------------------------

			return new self::$controllers[$type]($id);
		}

		# Cache entity

		public static function cache(Entitizer\Utils\Entity $entity) {

			$class = get_class($entity);

			if (false === ($type = array_search($class, self::$types, true))) return false;

			if (0 !== $entity->id) self::$cache[$type][$entity->id] = $entity;

			# ------------------------

			return true;
		}

		# Create new page entity

		public static function page($id = 0) {

			return self::create(ENTITY_TYPE_PAGE, $id);
		}

		# Create new menuitem entity

		public static function menuitem($id = 0) {

			return self::create(ENTITY_TYPE_MENUITEM, $id);
		}

		# Create new user entity

		public static function user($id = 0) {

			return self::create(ENTITY_TYPE_USER, $id);
		}

		# Create new user secret entity

		public static function userSecret($id = 0) {

			return self::create(ENTITY_TYPE_USER_SECRET, $id);
		}

		# Create new user session entity

		public static function userSession($id = 0) {

			return self::create(ENTITY_TYPE_USER_SESSION, $id);
		}
	}
}
