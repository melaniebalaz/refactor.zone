<?php

namespace Opsbears\Refactor\Boundary;

use Opsbears\Refactor\Boundary\Objects\AuthorList;

class GetAuthorsResponse {
	/**
	 * @var AuthorList
	 */
	private $authors;

	public function __construct(AuthorList $authors) {
		$this->authors = $authors;
	}

	public function getAuthors(): AuthorList {
		return $this->authors;
	}
}