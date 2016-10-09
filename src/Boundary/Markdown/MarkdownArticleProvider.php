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
use Opsbears\Refactor\Boundary\Objects\Article;
use Opsbears\Refactor\Boundary\Objects\ArticleList;
use Opsbears\Refactor\Boundary\Objects\AuthorList;
use Opsbears\Refactor\Boundary\Objects\CategoryList;
use Opsbears\Refactor\Boundary\Objects\SeriesList;

class MarkdownArticleProvider implements ArticleProvider {

	/**
	 * @var ArticleIndexer
	 */
	private $indexer;

	public function __construct(ArticleIndexer $indexer) {
		$this->indexer = $indexer;
	}

	/**
	 * @param Article[] $articles
	 *
	 * @return Article[]
	 */
	private function sortArticles(array $articles) {
		foreach ($articles as $key => $article) {
			if ($article->getPublished()->getTimestamp() < 0) {
				unset($articles[$key]);
			}
		}
		\usort($articles, function (Article $a, Article $b) {
			if ($a->getPublished() < $b->getPublished()) {
				return 1;
			} else if ($a->getPublished() > $b->getPublished()) {
				return -1;
			} else {
				return 0;
			}
		});
		return $articles;
	}

	public function getArticle(string $slug) : GetArticleResponse {
		$db = $this->indexer->loadArticleDatabase();
		foreach ($db->getArticles() as $article) {
			if ($article->getSlug() == $slug) {
				return new GetArticleResponse($article);
			}
		}

		throw new \Exception('Article not found', 404);
	}

	public function getLatestArticles(int $from = 0, int $count = 10) : GetLatestArticlesResponse {
		$db = $this->indexer->loadArticleDatabase();
		$articles = $this->sortArticles($db->getArticles());
		$articles = \array_slice($articles, $from, $count);
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

		$articles = \array_slice($this->sortArticles(iterator_to_array($foundSeries->getArticles())), $from, $count);
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

		$articles = \array_slice($this->sortArticles(iterator_to_array($foundCategory->getArticles())), $from, $count);
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
					$this->sortArticles(iterator_to_array($foundAuthor->getArticles())),
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
