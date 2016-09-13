<?php

namespace Opsbears\Refactor\Boundary;

interface ArticleProvider {
	public function getArticle(string $slug) : GetArticleResponse;

	public function getCategories() : GetCategoriesResponse;

	public function getSeries() : GetSeriesResponse;

	public function getLatestArticles(
		int $from = 0,
		int $count = 10
	) : GetLatestArticlesResponse;

	public function getLatestArticlesBySeries(
		string $slug,
		int $from = 0,
		int $count = 10
	) : GetLatestArticlesBySeriesResponse;

	public function getLatestArticlesByCategory(
		string $slug,
		int $from = 0,
		int $count = 10
	) : GetLatestArticlesByCategoryResponse;

	public function getLatestArticlesByAuthor(
		string $slug,
		int $from = 0,
		int $count = 10
	) : GetLatestArticlesByAuthorResponse;
}