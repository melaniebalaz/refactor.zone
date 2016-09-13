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
			'request'       => $this->getRequest(),
			'categories'    => $this->getArticleProvider()->getCategories()->getCategories(),
			'page'          => 1,
			'category'      => $response->getCategory(),
			'articles'      => $response->getArticles(),
			'totalArticles' => \ceil($response->getTotalArticles() / 10),
		];
	}

	public function pageAction(string $slug, int $page) {
		$response = $this->getArticleProvider()->getLatestArticlesByCategory($slug, ($page - 1) * 10);
		return [
			'request'       => $this->getRequest(),
			'categories'    => $this->getArticleProvider()->getCategories()->getCategories(),
			'page'          => $page,
			'category'      => $response->getCategory(),
			'articles'      => $response->getArticles(),
			'totalArticles' => \ceil($response->getTotalArticles() / 10),
		];
	}

	public function feedAction($slug) {
		$this->setResponse($this->getResponse()->withHeader('Content-Type', 'text/xml'));
		$response = $this->getArticleProvider()->getLatestArticlesByCategory($slug);
		return [
			'request'    => $this->getRequest(),
			'categories' => $this->getArticleProvider()->getCategories()->getCategories(),
			'page'       => 1,
			'category'   => $response->getCategory(),
			'articles'   => $response->getArticles(),
			'totalPages' => \ceil($response->getTotalArticles() / 10),
		];
	}
}
