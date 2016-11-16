<?php

namespace Opsbears\Refactor\Web;

use Opsbears\Refactor\Boundary\Objects\Article;

class StartPageController extends AbstractController {
	public function startPageAction() : array {
		$response = $this->getArticleProvider()->getLatestArticles(0, 9);

		return [
			'categories'    => $this->getArticleProvider()->getCategories()->getCategories(),
			'page'          => 1,
			'articles'      => $response->getArticles(),
			'totalArticles' => $response->getTotalArticles(),
		];
	}

	public function pageAction(int $page) {
		$response = $this->getArticleProvider()->getLatestArticles(($page - 1) * 9, 9);

		return [
			'categories'    => $this->getArticleProvider()->getCategories()->getCategories(),
			'page'       => $page,
			'articles'   => $response->getArticles(),
			'totalPages' => \ceil($response->getTotalArticles() / 9),
		];
	}

	public function feedAction() {
		$this->setResponse($this->getResponse()->withHeader('Content-Type', 'text/xml'));

		$response = $this->getArticleProvider()->getLatestArticles();
		return [
			'articles'   => $response->getArticles(),
			'request'    => $this->getRequest()
		];
	}


	public function instantFeedAction() {
		$this->setResponse($this->getResponse()->withHeader('Content-Type', 'text/xml'));

		$response = $this->getArticleProvider()->getLatestArticles();

		$articles = $response->getArticles();
		$recommendedArticles = [];

		foreach ($articles as $article) {
			/**
			 * @var Article[] $articleRecommendedArticles
			 */
			$articleRecommendedArticles =
				$this->getArticleProvider()->getLatestArticlesByAuthor($article->getAuthor()->getSlug(), 0, 5)
					 ->getArticles();
			foreach ($articleRecommendedArticles as $key => $recommendedArticle) {
				if ($recommendedArticle->getSlug() == $article->getSlug()) {
					unset($articleRecommendedArticles[$key]);
				}
			}
			if (count($articleRecommendedArticles) == 5) {
				array_pop($articleRecommendedArticles);
			}
			$recommendedArticles[$article->getSlug()] = $articleRecommendedArticles;
		}

		return [
			'articles'   => $articles,
			'request'    => $this->getRequest(),
			'articleRecommendedArticles' => $recommendedArticles
		];
	}
}
