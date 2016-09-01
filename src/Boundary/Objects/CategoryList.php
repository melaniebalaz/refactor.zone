<?php

namespace Opsbears\Refactor\Boundary\Objects;

class CategoryList extends \SplDoublyLinkedList {
	public function __construct(array $categories) {
		foreach ($categories as $category) {
			$this[] = $category;
		}
	}

	private function checkType($value) {
		if (!$value instanceof Category) {
			throw new \InvalidArgumentException();
		}
	}

	/**
	 * @param mixed $index
	 * @param Category $newval
	 */
	public function add($index, $newval) {
		$this->checkType($newval);
		parent::add($index, $newval);
	}

	/**
	 * @param mixed $index
	 * @param Category $newval
	 */
	public function offsetSet($index, $newval) {
		$this->checkType($newval);
		parent::offsetSet($index, $newval);
	}

	/**
	 * @param mixed $index
	 *
	 * @return Category
	 */
	public function offsetGet($index) {
		return parent::offsetGet($index);
	}

	/**
	 * @param Category $value
	 */
	public function push($value) {
		$this->checkType($value);
		parent::push($value);
	}

	/**
	 * @param Category $value
	 */
	public function unshift($value) {
		$this->checkType($value);
		parent::unshift($value);
	}
}