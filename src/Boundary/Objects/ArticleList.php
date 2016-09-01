<?php

namespace Opsbears\Refactor\Boundary\Objects;

class ArticleList extends \SplDoublyLinkedList {
	public function __construct(array $articles = []) {
		foreach ($articles as $article) {
			$this[] = $article;
		}
	}

	private function checkType($value) {
		if (!$value instanceof Article) {
			throw new \InvalidArgumentException();
		}
	}

	/**
	 * @param mixed $index
	 * @param Article $newval
	 */
	public function add($index, $newval) {
		$this->checkType($newval);
		parent::add($index, $newval);
	}

	/**
	 * @param mixed $index
	 * @param Article $newval
	 */
	public function offsetSet($index, $newval) {
		$this->checkType($newval);
		parent::offsetSet($index, $newval);
	}

	/**
	 * @param mixed $index
	 *
	 * @return Article
	 */
	public function offsetGet($index) {
		parent::offsetGet($index);
	}

	/**
	 * @param Article $value
	 */
	public function push($value) {
		$this->checkType($value);
		parent::push($value);
	}

	/**
	 * @param Article $value
	 */
	public function unshift($value) {
		$this->checkType($value);
		parent::unshift($value);
	}
}