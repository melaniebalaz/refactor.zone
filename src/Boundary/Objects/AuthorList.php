<?php

namespace Opsbears\Refactor\Boundary\Objects;

class AuthorList extends \SplDoublyLinkedList {
	public function __construct(array $categories) {
		foreach ($categories as $author) {
			$this[] = $author;
		}
	}

	private function checkType($value) {
		if (!$value instanceof Author) {
			throw new \InvalidArgumentException();
		}
	}

	/**
	 * @param mixed $index
	 * @param Author $newval
	 */
	public function add($index, $newval) {
		$this->checkType($newval);
		parent::add($index, $newval);
	}

	/**
	 * @param mixed $index
	 * @param Author $newval
	 */
	public function offsetSet($index, $newval) {
		$this->checkType($newval);
		parent::offsetSet($index, $newval);
	}

	/**
	 * @param mixed $index
	 *
	 * @return Author
	 */
	public function offsetGet($index) {
		return parent::offsetGet($index);
	}

	/**
	 * @param Author $value
	 */
	public function push($value) {
		$this->checkType($value);
		parent::push($value);
	}

	/**
	 * @param Author $value
	 */
	public function unshift($value) {
		$this->checkType($value);
		parent::unshift($value);
	}
}