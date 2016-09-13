<?php

namespace Opsbears\Refactor\Boundary\Markdown;

use Opsbears\Refactor\Boundary\ArticleProvider;
use Opsbears\Refactor\Boundary\GetArticleResponse;
use Opsbears\Refactor\Boundary\GetAuthorsResponse;
use Opsbears\Refactor\Boundary\GetCategoriesResponse;
use Opsbears\Refactor\Boundary\GetLatestArticlesByAuthorResponse;
use Opsbears\Refactor\Boundary\GetLatestArticlesByCategoryResponse;
use Opsbears\Refactor\Boundary\GetLatestArticlesBySeriesResponse;
use Opsbears\Refactor\Boundary\GetLatestArticlesResponse;
use Opsbears\Refactor\Boundary\GetSeriesResponse;
use Opsbears\Refactor\Boundary\NotFoundException;
use Opsbears\Refactor\Boundary\Objects\ArticleList;
use Opsbears\Refactor\Boundary\Objects\AuthorList;
use Opsbears\Refactor\Boundary\Objects\CategoryList;
use Opsbears\Refactor\Boundary\Objects\SeriesList;

class MarkdownArticleProvider implements ArticleProvider {
	/**
	 * @var ArticleConverter
	 */
	private $articleConverter;
	/**
	 * @var ArticleIndexer
	 */
	private $indexer;

	public function __construct(ArticleConverter $articleConverter, ArticleIndexer $indexer) {
		$this->articleConverter = $articleConverter;
		$this->indexer = $indexer;
	}

	public function getArticle(string $slug) : GetArticleResponse {
		return new GetArticleResponse($this->articleConverter->convert($slug));
	}

	public function getLatestArticles(int $from = 0, int $count = 10) : GetLatestArticlesResponse {
		$db = $this->indexer->loadArticleDatabase();
		$articles = \array_slice($db->getArticles(), $from, $count);
		return new GetLatestArticlesResponse(new ArticleList($articles), \count($db->getArticles()));
	}

	public function getLatestArticlesBySeries(string $slug, int $from = 0, int $count = 10) : GetLatestArticlesBySeriesResponse {
		$db = $this->indexer->loadArticleDatabase();
		$series = $db->getSeries();

		$foundSeries = null;
		foreach ($series as $seriesEntry) {
			if ($seriesEntry->getSlug() == $slug) {
				$foundSeries = $seriesEntry;
			}
		}
		if (!$foundSeries) {
			throw new NotFoundException();
		}

		$articles = \array_slice($foundSeries->getArticles(), $from, $count);
		return new GetLatestArticlesBySeriesResponse(
			$foundSeries,
			new ArticleList($articles),
			\count($db->getArticles())
		);
	}

	public function getLatestArticlesByCategory(string $slug, int $from = 0, int $count = 10) : GetLatestArticlesByCategoryResponse {
		$db = $this->indexer->loadArticleDatabase();
		$categories = $db->getCategories();

		$foundCategory = null;
		foreach ($categories as $category) {
			if ($category->getSlug() == $slug) {
				$foundCategory = $category;
			}
		}
		if (!$foundCategory) {
			throw new NotFoundException();
		}

		$articles = \array_slice($foundCategory->getArticles(), $from, $count);
		return new GetLatestArticlesByCategoryResponse(
			$foundCategory,
			new ArticleList($articles),
			\count($db->getArticles())
		);
	}

	public function getCategories() : GetCategoriesResponse {
		$db = $this->indexer->loadArticleDatabase();
		$categories = $db->getCategories();

		return new GetCategoriesResponse(new CategoryList($categories));
	}

	public function getSeries() : GetSeriesResponse {
		$db = $this->indexer->loadArticleDatabase();
		$series = $db->getSeries();

		return new GetSeriesResponse(new SeriesList($series));
	}

	public function getLatestArticlesByAuthor(
		string $slug,
		int $from = 0,
		int $count = 10
	) : GetLatestArticlesByAuthorResponse {
		$db = $this->indexer->loadArticleDatabase();
		$authors = $db->getAuthors();

		$foundAuthor = null;
		foreach ($authors as $author) {
			if ($author->getSlug() == $slug) {
				$foundAuthor = $author;
				break;
			}
		}
		if (!$foundAuthor) {
			throw new NotFoundException();
		}

		return new GetLatestArticlesByAuthorResponse(
			$foundAuthor,
			new ArticleList(
				\array_slice(
					iterator_to_array($foundAuthor->getArticles()),
					$from,
					$count
				)
			),
			$foundAuthor->getArticles()->count()
		);
	}

	public function getAuthors() : GetAuthorsResponse {
		$db = $this->indexer->loadArticleDatabase();
		return new GetAuthorsResponse(
			new AuthorList($db->getAuthors())
		);
	}
}
