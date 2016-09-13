<?php

namespace Opsbears\Refactor\Web;

class SitemapController extends AbstractController {
	public function sitemapAction() {
		$this->setResponse($this->getResponse()->withHeader('Content-Type', 'application/xml'));
		$articles = $this->getArticleProvider()->getLatestArticles(0, 500)->getArticles();
		return [
			'articles'    => $articles,
		];
	}
}
