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
	private $totalPages;

	public function __construct(Category $category, ArticleList $articles, int $totalPages) {
		$this->category = $category;
		$this->articles = $articles;
		$this->totalPages = $totalPages;
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
	public function getTotalPages(): int {
		return $this->totalPages;
	}

	/**
	 * @param int $totalPages
	 */
	public function setTotalPages(int $totalPages) {
		$this->totalPages = $totalPages;
	}
}