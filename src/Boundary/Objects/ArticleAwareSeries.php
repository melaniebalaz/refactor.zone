<?php

namespace Opsbears\Refactor\Boundary\Objects;

class ArticleAwareSeries extends Series {
	/**
	 * @var ArticleList
	 */
	private $articles;

	public function __construct(string $slug, string $name, string $htmlBody, ArticleList $articles) {
		parent::__construct($slug, $name, $htmlBody);
		$this->articles = $articles;
	}

	public function withAddedArticle(Article $article) : ArticleAwareSeries {
		$articles = \iterator_to_array($this->getArticles());
		$articles[] = $article;
		return new ArticleAwareSeries(
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
