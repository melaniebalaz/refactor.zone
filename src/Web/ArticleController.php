<?php

namespace Opsbears\Refactor\Web;

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
		return [
			'categories' => $this->getArticleProvider()->getCategories()->getCategories(),
			'article'    => $article,
		];
	}
}
