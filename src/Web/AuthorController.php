<?php

namespace Opsbears\Refactor\Web;

class AuthorController extends AbstractController {
	public function indexAction() : array {
		return [
			'categories' => $this->getArticleProvider()->getCategories()->getCategories(),
			'authors'    => $this->getArticleProvider()->getAuthors()->getAuthors(),
		];
	}

	public function authorAction(string $slug) : array {
		$response = $this->getArticleProvider()->getLatestArticlesByAuthor($slug);
		return [
			'categories'    => $this->getArticleProvider()->getCategories()->getCategories(),
			'page'          => 1,
			'author'        => $response->getAuthor(),
			'articles'      => $response->getArticles(),
			'totalArticles' => $response->getTotalArticles(),
		];
	}

	public function pageAction(string $slug, int $page) {
		$response = $this->getArticleProvider()->getLatestArticlesByAuthor($slug, ($page - 1) * 10);
		return [
			'categories' => $this->getArticleProvider()->getCategories()->getCategories(),
			'page'       => $page,
			'author'     => $response->getAuthor(),
			'articles'   => $response->getArticles(),
			'totalPages' => \ceil($response->getTotalArticles() / 10),
		];
	}

	public function feedAction($slug) {
		$this->setResponse($this->getResponse()->withHeader('Content-Type', 'application/rss+xml;charset=utf-8'));

		$response = $this->getArticleProvider()->getLatestArticlesByAuthor($slug);
		return [
			'author'     => $response->getAuthor(),
			'articles'   => $response->getArticles(),
			'request'    => $this->getRequest()
		];
	}
}
