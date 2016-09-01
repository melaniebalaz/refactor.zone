<?php

namespace Opsbears\Refactor\Boundary;

use Opsbears\Refactor\Boundary\Objects\ArticleList;
use Opsbears\Refactor\Boundary\Objects\Category;

class GetLatestArticlesByCategoryResponse {
	/**
	 * @var Category
	 */
	private $category;
	/**
	 * @var ArticleList
	 */
	private $articles;
	/**
	 * @var int
	 */
	private $totalArticles;

	public function __construct(Category $category, ArticleList $articles, int $totalArticles) {
		$this->category      = $category;
		$this->articles      = $articles;
		$this->totalArticles = $totalArticles;
	}

	/**
	 * @return Category
	 */
	public function getCategory(): Category {
		return $this->category;
	}

	/**
	 * @param Category $category
	 */
	public function setCategory(Category $category) {
		$this->category = $category;
	}

	/**
	 * @return ArticleList
	 */
	public function getArticles(): ArticleList {
		return $this->articles;
	}

	/**
	 * @param ArticleList $articles
	 */
	public function setArticles(ArticleList $articles) {
		$this->articles = $articles;
	}

	/**
	 * @return int
	 */
	public function getTotalArticles(): int {
		return $this->totalArticles;
	}

	/**
	 * @param int $totalArticles
	 */
	public function setTotalArticles(int $totalArticles) {
		$this->totalArticles = $totalArticles;
	}
}