<?php

namespace Opsbears\Refactor\Boundary;

use Opsbears\Refactor\Boundary\Objects\Article;

class GetArticleResponse {
	/**
	 * @var Article
	 */
	private $article;

	public function __construct(Article $article) {
		$this->article = $article;
	}

	/**
	 * @return Article
	 */
	public function getArticle(): Article {
		return $this->article;
	}
}