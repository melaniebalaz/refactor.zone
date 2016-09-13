<?php

namespace Opsbears\Refactor\Boundary;

use Opsbears\Refactor\Boundary\Objects\ArticleList;
use Opsbears\Refactor\Boundary\Objects\Author;

class GetLatestArticlesByAuthorResponse {
	/**
	 * @var Author
	 */
	private $author;
	/**
	 * @var ArticleList
	 */
	private $articles;
	/**
	 * @var int
	 */
	private $totalArticles;

	public function __construct(Author $author, ArticleList $articles, int $totalArticles) {
		$this->author      = $author;
		$this->articles      = $articles;
		$this->totalArticles = $totalArticles;
	}

	/**
	 * @return Author
	 */
	public function getAuthor(): Author {
		return $this->author;
	}

	/**
	 * @param Author $author
	 */
	public function setAuthor(Author $author) {
		$this->author = $author;
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