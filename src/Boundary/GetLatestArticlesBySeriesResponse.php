<?php

namespace Opsbears\Refactor\Boundary;

use Opsbears\Refactor\Boundary\Objects\ArticleList;
use Opsbears\Refactor\Boundary\Objects\Series;

class GetLatestArticlesBySeriesResponse {
	/**
	 * @var ArticleList
	 */
	private $articles;

	/**
	 * @var Series
	 */
	private $series;

	/**
	 * @var int
	 */
	private $totalPages;

	public function __construct(Series $series, ArticleList $articles, int $totalPages) {
		$this->articles = $articles;
		$this->series = $series;
		$this->totalPages = $totalPages;
	}

	public function getArticles(): ArticleList {
		return $this->articles;
	}

	public function getSeries(): Series {
		return $this->series;
	}

	public function getTotalPages(): int {
		return $this->totalPages;
	}
}