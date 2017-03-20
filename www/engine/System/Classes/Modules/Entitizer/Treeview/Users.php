<?php

/**
 * @package Cadmium\System\Modules\Entitizer
 * @author Anton Romanov
 * @copyright Copyright (c) 2015-2017, Anton Romanov
 * @link http://cadmium-cms.com
 */

namespace Modules\Entitizer\Treeview {

	use Modules\Entitizer;

	class Users extends Entitizer\Utils\Treeview {

		use Entitizer\Common\User, Entitizer\Collection\Users;
	}
}
