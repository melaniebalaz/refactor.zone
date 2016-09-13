<?php

namespace Opsbears\Refactor\Boundary\Objects;

class ArticleAwareAuthor extends Author {
	/**
	 * @var ArticleList
	 */
	private $articles;

	public function __construct(
		string $slug,
		string $name,
		string $url,
		string $htmlBody,
		string $firstName,
		string $lastName,
		string $gender,
		string $facebookUrl,
		string $twitterUrl,
		string $gplusUrl,
		string $imageUrl,
		ArticleList $articles
	) {
		parent::__construct(
			$slug,
			$name,
			$url,
			$htmlBody,
			$firstName,
			$lastName,
			$gender,
			$facebookUrl,
			$twitterUrl,
			$gplusUrl,
			$imageUrl
		);
		$this->articles = $articles;
	}

	public function withAddedArticle(Article $article) : ArticleAwareAuthor {
		$articles = (array)$this->getArticles();
		$articles[] = $article;
		return new ArticleAwareAuthor(
			$this->getSlug(),
			$this->getName(),
			$this->getUrl(),
			$this->getHtmlBody(),
			$this->getFirstName(),
			$this->getLastName(),
			$this->getGender(),
			$this->getFacebookUrl(),
			$this->getTwitterUrl(),
			$this->getGplusUrl(),
			$this->getImageUrl(),
			new ArticleList($articles)
		);
	}

	public function getArticles(): ArticleList {
		return $this->articles;
	}
}
