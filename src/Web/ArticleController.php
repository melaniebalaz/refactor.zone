<?php

namespace Opsbears\Refactor\Web;

use Opsbears\Refactor\Boundary\Objects\Article;

class ArticleController extends AbstractController {
	public function articleAction(string $slug) {
		$article = $this->getArticleProvider()->getArticle($slug)->getArticle();
		$this->setLastModified($article->getModified());
		return [
			'categories' => $this->getArticleProvider()->getCategories()->getCategories(),
			'article'    => $article,
		];
	}

	public function ampArticleAction(string $slug) {
		$article = $this->getArticleProvider()->getArticle($slug)->getArticle();
		$this->setLastModified($article->getModified());
		return [
			'categories' => $this->getArticleProvider()->getCategories()->getCategories(),
			'stylesheet' => \file_get_contents(__DIR__ . '/../../htdocs/css/amp.min.css'),
			'article'    => $article,
		];
	}

	public function instantArticleAction(string $slug) {
		$article = $this->getArticleProvider()->getArticle($slug)->getArticle();
		$this->setLastModified($article->getModified());

		/**
		 * @var Article[] $recommendedArticles
		 */
		$recommendedArticles = $this->getArticleProvider()->getLatestArticlesByAuthor($article->getAuthor()->getSlug(), 0, 5)->getArticles();
		foreach ($recommendedArticles as $key => $recommendedArticle) {
			if ($recommendedArticle->getSlug() == $article->getSlug()) {
				unset($recommendedArticles[$key]);
			}
		}
		if (count($recommendedArticles) == 5) {
			array_pop($recommendedArticles);
		}
		return [
			'categories' => $this->getArticleProvider()->getCategories()->getCategories(),
			'article'    => $article,
			'recommendedArticles' => $recommendedArticles
		];
	}

	public function imageAction(string $slug, int $number) {
		$article = $this->getArticleProvider()->getArticle($slug)->getArticle();
		$this->setLastModified($article->getModified());
		try {
			$image = $article->getEmbeddedImage($number);
			$this->setResponse($this->getResponse()->withAddedHeader('Content-Type', 'image/png'));
			return $image;
		} catch (\OutOfBoundsException $e) {
			throw new \Exception('', 404);
		}
	}
}
