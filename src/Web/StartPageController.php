<?php

namespace Opsbears\Refactor\Web;

class StartPageController extends AbstractController {
	public function startPageAction() : array {
		$response = $this->getArticleProvider()->getLatestArticles(0, 9);

		return [
			'page'          => 1,
			'articles'      => $response->getArticles(),
			'totalArticles' => $response->getTotalArticles(),
		];
	}

	public function pageAction(int $page) {
		$response = $this->getArticleProvider()->getLatestArticles(($page - 1) * 9, 9);

		return [
			'page'       => $page,
			'articles'   => $response->getArticles(),
			'totalPages' => \ceil($response->getTotalArticles() / 9),
		];
	}
}
