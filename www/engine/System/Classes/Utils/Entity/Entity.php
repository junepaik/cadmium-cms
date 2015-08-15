<?php

namespace System\Utils {

	use DB;

	abstract class Entity {

		private $type = '', $table = '', $nesting = false, $has_super = false;

		protected $params = null, $foreigns = null;

		protected $id = 0, $created_id = 0, $data = array(), $path = array();

		# Init parent entities

		private function initPath() {

			if (!$this->nesting) return array();

			$entity = $this; $path = array($entity);

			while (0 !== $entity->data['parent_id']) {

				$entity = $entity->params->get('parent_id')->entity();

				if (false !== $entity) $path[] = $entity; else return array();
			}

			return array_reverse($path);
		}

		# Init entity

		private function init($name, $value) {

			$selection = array_merge(array('id'), array_keys($this->params->get()));

			DB::select($this->table, $selection, array($name => $value), null, 1);

			if (!(DB::last() && (DB::last()->rows === 1))) return false;

			$data = DB::last()->row();

			# Validate data

			$this->id = $this->params->id()->set($data['id']);

			foreach ($this->params->get() as $name => $param) $this->data[$name] = $param->set($data[$name]);

			$this->path = $this->initPath();

			# Implement entity

			$this->implement();

			# Cache entity

			Entity\Factory::cache($this->type, $this);

			# ------------------------

			return true;
		}

		# Get dataset

		private function getDataset(&$params, array $data) {

			$set = array();

			foreach ($params->get() as $name => $param) {

				if (isset($data[$name])) $param->set($data[$name]);

				if (($param instanceof Entity\Param\Relation) && (false === $param->entity())) {

					if ($param->name() !== 'parent_id') return false;

					$set['parent_id'] = $param->set(0);

				} else $set[$name] = $param->value();
			}

			return $set;
		}

        # Constructor

        public function __construct() {

			$class_name = get_class($this);

			$this->type = strval(@constant($class_name . '::TYPE'));

			$this->table = strval(@constant($class_name . '::TABLE'));

			$this->nesting = boolval(@constant($class_name . '::NESTING'));

			$this->has_super = boolval(@constant($class_name . '::HAS_SUPER'));

			$this->params = new Entity\Params(); $this->foreigns = new Entity\Foreigns();

			if ($this->nesting) $this->params->relation('parent_id', $this->type);

            # Define entity presets

            $this->define();
        }

		# Create table

		public function createTable() {

            $set = array_merge($this->params->fieldset(), $this->params->keyset());

            $query = ("CREATE TABLE IF NOT EXISTS `" . $this->table . "`") .

                     ("(" . implode(", ", $set) . ") ENGINE=InnoDB DEFAULT CHARSET=utf8");

            # ------------------------

            return (DB::send($query) && DB::last()->status);
		}

		# Init entity by id

		public function initById($id) {

			if (0 !== $this->id) return true;

			if (0 === ($id = intabs($id))) return false;

			# ------------------------

			return $this->init('id', $id);
		}

		# Init entity by unique param

		public function initByUnique($name, $value) {

			if (0 !== $this->id) return true;

			$name = strval($name); $value = strval($value);

			if (false === ($param = $this->params->get($name))) return false;

			if (!($param instanceof Entity\Param\Unique)) return false;

			# ------------------------

			return $this->init($name, $value);
		}

		# Create entity

		public function create(array $data) {

			if ((0 !== $this->id) && !$this->nesting) return false;

			$params = clone $this->params;

			if ($this->nesting) $params->get('parent_id')->set($this->id);

			if (false === ($set = $this->getDataset($params, $data))) return false;

			# Insert entity

			DB::insert($this->table, $set);

			if (!(DB::last() && DB::last()->status)) return false;

			$this->created_id = DB::last()->id;

			if ($this->nesting) return true;

			# Re-init entity

			$this->params = $params; $this->id = $this->created_id;

			foreach ($set as $name => $value) $this->data[$name] = $value;

			# Implement entity

			$this->implement();

			# ------------------------

			return true;
		}

		# Edit entity

		public function edit(array $data) {

			if (0 === $this->id) return false;

			$params = clone $this->params;

			if (false === ($set = $this->getDataset($params, $data))) return false;

			# Update entity

			DB::update($this->table, $set, array('id' => $this->id));

			if (!(DB::last() && DB::last()->status)) return false;

			# Re-init entity

			$this->params = $params;

			foreach ($set as $name => $value) $this->data[$name] = $value;

			# Implement entity

			$this->implement();

			# ------------------------

			return true;
		}

		# Remove entity

		public function remove() {

			if (0 === $this->id) return false;

			if ($this->has_super && ($this->id === 1)) return false;

			# Count children

			if ($this->nesting) {

				DB::select($this->table, 'COUNT(*) as count', array('parent_id' => $this->id));

				if (!(DB::last() && DB::last()->status)) return false;

				if (intabs(DB::last()->row()['count']) > 0) return false;
			}

			# Delete foreign related entries

			foreach ($this->foreigns->get() as $table => $field) {

				DB::delete($table, array($field => $this->id));

				if (!(DB::last() && DB::last()->status)) return false;
			}

			# Remove entity

			DB::delete($this->table, array('id' => $this->id));

			if (!(DB::last() && DB::last()->status)) return false;

			$this->id = 0; $this->created_id = 0; $this->data = array(); $this->path = array();

			# ------------------------

			return true;
		}

		# Return entity data

		public function __get($name) {

			$name = strval($name);

			if ($name === 'id') return $this->id;

			if ($name === 'created_id') return $this->created_id;

			return (isset($this->data[$name]) ? $this->data[$name] : null);
		}
	}
}
