<?php

namespace Opsbears\Refactor\Web;

class AuthorController extends AbstractController {
	public function indexAction() : array {
		return [
			'authors' => $this->getArticleProvider()->getAuthors(),
		];
	}

	public function authorAction(string $slug) : array {
		$response = $this->getArticleProvider()->getLatestArticlesByAuthor($slug);
		return [
			'page'          => 1,
			'author'        => $response->getAuthor(),
			'articles'      => $response->getArticles(),
			'totalArticles' => $response->getTotalArticles(),
		];
	}

	public function pageAction(string $slug, int $page) {
		$response = $this->getArticleProvider()->getLatestArticlesByAuthor($slug, ($page - 1) * 10);
		return [
			'page'       => $page,
			'author'     => $response->getAuthor(),
			'articles'   => $response->getArticles(),
			'totalPages' => \ceil($response->getTotalArticles() / 10),
		];
	}

	public function feedAction($slug) {
		$response = $this->getArticleProvider()->getLatestArticlesByAuthor($slug);
		return [
			'author'   => $response->getAuthor(),
			'articles' => $response->getArticles(),
		];
	}
}
