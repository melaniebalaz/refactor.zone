<?php

namespace Opsbears\Refactor\Boundary;

use Opsbears\Refactor\Boundary\Objects\CategoryList;

class GetCategoriesResponse {
	/**
	 * @var CategoryList
	 */
	private $categories;

	public function __construct(CategoryList $categories) {
		$this->categories = $categories;
	}

	public function getCategories(): CategoryList {
		return $this->categories;
	}
}
