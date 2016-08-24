<?php

namespace Opsbears\Refactor\Boundary;

use Opsbears\Refactor\Boundary\Objects\ArticleList;

class GetLatestArticlesResponse {
	/**
	 * @var ArticleList
	 */
	private $articles;
	/**
	 * @var int
	 */
	private $totalPages;

	public function __construct(ArticleList $articles, int $totalPages) {
		$this->articles = $articles;
		$this->totalPages = $totalPages;
	}

	public function getArticles(): ArticleList {
		return $this->articles;
	}

	public function getTotalPages(): int {
		return $this->totalPages;
	}
}