<?php

namespace Opsbears\Refactor\Web;

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
		$this->setResponse($this->getResponse()->withHeader('Content-Type', 'application/rss+xml;charset=utf-8'));

		$response = $this->getArticleProvider()->getLatestArticles();
		return [
			'articles'   => $response->getArticles(),
			'request'    => $this->getRequest()
		];
	}


	public function instantFeedAction() {
		$this->setResponse($this->getResponse()->withHeader('Content-Type', 'application/rss+xml;charset=utf-8'));

		$response = $this->getArticleProvider()->getLatestArticles();
		return [
			'articles'   => $response->getArticles(),
			'request'    => $this->getRequest()
		];
	}
}
