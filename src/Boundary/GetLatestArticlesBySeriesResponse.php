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
	private $totalArticles;

	public function __construct(Series $series, ArticleList $articles, int $totalArticles) {
		$this->articles      = $articles;
		$this->series        = $series;
		$this->totalArticles = $totalArticles;
	}

	public function getArticles(): ArticleList {
		return $this->articles;
	}

	public function getSeries(): Series {
		return $this->series;
	}

	public function getTotalArticles(): int {
		return $this->totalArticles;
	}
}