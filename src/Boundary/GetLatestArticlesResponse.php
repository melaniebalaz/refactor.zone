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
	private $totalArticles;

	public function __construct(ArticleList $articles, int $totalArticles) {
		$this->articles      = $articles;
		$this->totalArticles = $totalArticles;
	}

	public function getArticles(): ArticleList {
		return $this->articles;
	}

	public function getTotalArticles(): int {
		return $this->totalArticles;
	}
}