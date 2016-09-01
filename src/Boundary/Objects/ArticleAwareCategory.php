<?php

namespace Opsbears\Refactor\Boundary\Objects;

class ArticleAwareCategory extends Category {
	/**
	 * @var ArticleList
	 */
	private $articles;

	public function __construct(string $slug, string $name, string $htmlBody, ArticleList $articles) {
		parent::__construct($slug, $name, $htmlBody);
		$this->articles = $articles;
	}

	public function withAddedArticle(Article $article) : ArticleAwareCategory {
		$articles = (array)$this->getArticles();
		$articles[] = $article;
		return new ArticleAwareCategory(
			$this->getSlug(),
			$this->getName(),
			$this->getHtmlBody(),
			new ArticleList($articles)
		);
	}

	public function getArticles(): ArticleList {
		return $this->articles;
	}
}