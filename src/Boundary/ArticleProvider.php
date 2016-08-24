<?php

namespace Opsbears\Refactor\Boundary;

interface ArticleProvider {
	public function getArticle(string $slug) : GetArticleResponse;
	public function getLatestArticles(int $page = 1) : GetLatestArticlesResponse;
	public function getLatestArticlesBySeries(string $slug, int $page = 1) : GetLatestArticlesBySeriesResponse;
	public function getLatestArticlesByCategory(string $slug, int $page = 1) : GetLatestArticlesBySeriesResponse;
}