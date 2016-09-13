<?php

namespace Opsbears\Refactor\Web;

class SeriesController extends AbstractController {
	public function indexAction() : array {
		return [
			'categories'    => $this->getArticleProvider()->getCategories()->getCategories(),
			'series' => $this->getArticleProvider()->getSeries(),
		];
	}

	public function seriesAction(string $slug) : array {
		$response = $this->getArticleProvider()->getLatestArticlesBySeries($slug);
		return [
			'categories'    => $this->getArticleProvider()->getCategories()->getCategories(),
			'page'          => 1,
			'series'        => $response->getSeries(),
			'articles'      => $response->getArticles(),
			'totalArticles' => $response->getTotalArticles(),
		];
	}

	public function pageAction(string $slug, int $page) {
		$response = $this->getArticleProvider()->getLatestArticlesBySeries($slug, ($page - 1) * 10);
		return [
			'categories'    => $this->getArticleProvider()->getCategories()->getCategories(),
			'page'       => $page,
			'series'     => $response->getSeries(),
			'articles'   => $response->getArticles(),
			'totalPages' => \ceil($response->getTotalArticles() / 10),
		];
	}

	public function feedAction($slug) {
		$response = $this->getArticleProvider()->getLatestArticlesBySeries($slug);
		return [
			'categories'    => $this->getArticleProvider()->getCategories()->getCategories(),
			'series'   => $response->getSeries(),
			'articles' => $response->getArticles(),
		];
	}
}
