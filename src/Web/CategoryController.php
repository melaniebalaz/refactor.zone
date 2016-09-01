<?php

namespace Opsbears\Refactor\Web;

class CategoryController extends AbstractController {
	public function indexAction() : array {
		return [
			'categories' => $this->getArticleProvider()->getCategories()->getCategories(),
		];
	}

	public function categoryAction(string $slug) : array {
		$response = $this->getArticleProvider()->getLatestArticlesByCategory($slug);
		return [
			'page'          => 1,
			'category'      => $response->getCategory(),
			'articles'      => $response->getArticles(),
			'totalArticles' => \ceil($response->getTotalArticles() / 10),
		];
	}

	public function pageAction(string $slug, int $page) {
		$response = $this->getArticleProvider()->getLatestArticlesByCategory($slug, ($page - 1) * 10);
		return [
			'page'          => $page,
			'category'      => $response->getCategory(),
			'articles'      => $response->getArticles(),
			'totalArticles' => \ceil($response->getTotalArticles() / 10),
		];
	}

	public function feedAction($slug) {
		$response = $this->getArticleProvider()->getLatestArticlesByCategory($slug);
		return [
			'page'       => 1,
			'category'   => $response->getCategory(),
			'articles'   => $response->getArticles(),
			'totalPages' => \ceil($response->getTotalArticles() / 10),
		];
	}
}
