<?php

namespace Opsbears\Refactor\Boundary\Objects;

class SeriesList extends \SplDoublyLinkedList {
	public function __construct(array $series) {
		foreach ($series as $seriesItem) {
			$this[] = $seriesItem;
		}
	}

	private function checkType($value) {
		if (!$value instanceof Series) {
			throw new \InvalidArgumentException();
		}
	}

	/**
	 * @param mixed $index
	 * @param Series $newval
	 */
	public function add($index, $newval) {
		$this->checkType($newval);
		parent::add($index, $newval);
	}

	/**
	 * @param mixed $index
	 * @param Series $newval
	 */
	public function offsetSet($index, $newval) {
		$this->checkType($newval);
		parent::offsetSet($index, $newval);
	}

	/**
	 * @param mixed $index
	 *
	 * @return Series
	 */
	public function offsetGet($index) {
		return parent::offsetGet($index);
	}

	/**
	 * @param Series $value
	 */
	public function push($value) {
		$this->checkType($value);
		parent::push($value);
	}

	/**
	 * @param Series $value
	 */
	public function unshift($value) {
		$this->checkType($value);
		parent::unshift($value);
	}
}