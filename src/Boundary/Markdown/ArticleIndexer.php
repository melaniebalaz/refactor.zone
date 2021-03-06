<?php

namespace Opsbears\Refactor\Boundary\Markdown;

use Opsbears\Refactor\Boundary\Objects\Article;
use Opsbears\Refactor\Boundary\Objects\ArticleAwareAuthor;
use Opsbears\Refactor\Boundary\Objects\ArticleAwareCategory;
use Opsbears\Refactor\Boundary\Objects\ArticleAwareSeries;
use Opsbears\Refactor\Boundary\Objects\ArticleDatabase;
use Opsbears\Refactor\Boundary\Objects\ArticleList;
use Opsbears\Refactor\Boundary\Objects\Category;
use Opsbears\Refactor\Boundary\Objects\CategoryList;

class ArticleIndexer {
	/**
	 * @var ArticleConverter
	 */
	private $converter;
	/**
	 * @var string
	 */
	private $datadir;
	/**
	 * @var string
	 */
	private $cachedir;
	/**
	 * @var bool
	 */
	private $production;

	public function __construct(ArticleConverter $converter, string $datadir, string $cachedir, $production = true) {
		$this->converter = $converter;
		$this->datadir = $datadir;
		$this->cachedir = $cachedir;
		$this->production = $production;
	}

	public function loadArticleDatabase() : ArticleDatabase {
		if ($this->production) {
			$cacheFile = $this->cachedir . '/db.serialized';
			if (\file_exists($cacheFile)) {
				return \unserialize(\file_get_contents($cacheFile));
			}
		}

		$articlesDirectory = \scandir($this->datadir . '/articles/');

		/**
		 * @var Article[] $articles
		 */
		$articles   = [];

		/**
		 * @var ArticleAwareCategory[] $categories
		 */
		$categories = [];

		/**
		 * @var ArticleAwareAuthor[] $authors
		 */
		$authors = [];

		/**
		 * @var ArticleAwareSeries[] $series
		 */
		$series     = [];

		foreach ($articlesDirectory as $article) {
			if (\preg_match('/(?P<articleSlug>[a-zA-Z0-9\-]+)\.md$/', $article, $matches)) {
				$articles[] = $this->converter->convert($matches['articleSlug']);
			}
		}

		\usort($articles, function(Article $a, Article $b) {
			if ($a->getPublished()->getTimestamp() > $b->getPublished()->getTimestamp()) {
				return 1;
			} else if ($a->getPublished()->getTimestamp() < $b->getPublished()->getTimestamp()) {
				return -1;
			} else {
				return 0;
			}
		});

		foreach ($articles as $articleKey => $article) {
			$newCategoryList = [];
			/**
			 * @var Category $category
			 */
			foreach ($article->getCategories() as $category) {
				if (!isset($categories[$category->getSlug()])) {
					$categories[$category->getSlug()] = new ArticleAwareCategory(
						$category->getSlug(),
						$category->getName(),
						$category->getHtmlBody(),
						new ArticleList()
					);
				}
				$categories[$category->getSlug()] = $categories[$category->getSlug()]->withAddedArticle($article);
				$newCategoryList[] = $categories[$category->getSlug()];
			}
			if ($article->getSeries()) {
				if (!isset($series[$article->getSeries()->getSlug()])) {
					$series[$article->getSeries()->getSlug()] = new ArticleAwareSeries(
						$article->getSeries()->getSlug(),
						$article->getSeries()->getName(),
						$article->getSeries()->getHtmlBody(),
						new ArticleList()
					);
				}
				$series[$article->getSeries()->getSlug()] =
					$series[$article->getSeries()->getSlug()]->withAddedArticle($article);
			}

			if (!isset($authors[$article->getAuthor()->getSlug()])) {
				$authors[$article->getAuthor()->getSlug()] = new ArticleAwareAuthor(
					$article->getAuthor()->getSlug(),
					$article->getAuthor()->getName(),
					$article->getAuthor()->getUrl(),
					$article->getAuthor()->getHtmlBody(),
					$article->getAuthor()->getFirstName(),
					$article->getAuthor()->getLastName(),
					$article->getAuthor()->getGender(),
					$article->getAuthor()->getFacebookUrl(),
					$article->getAuthor()->getTwitterUrl(),
					$article->getAuthor()->getGplusUrl(),
					$article->getAuthor()->getImageUrl(),
					new ArticleList()
				);
			}
			$authors[$article->getAuthor()->getSlug()] =
				$authors[$article->getAuthor()->getSlug()]->withAddedArticle($article);

			$articles[$articleKey] = new Article(
				$article->getSlug(),
				$article->getName(),
				$authors[$article->getAuthor()->getSlug()],
				$article->getPublished(),
				$article->getModified(),
				$article->getExcerpt(),
				$article->getHtmlBody(),
				$article->getSocialImage(),
				$article->getDecor(),
				$article->getDecor2x(),
				new CategoryList($newCategoryList),
				($article->getSeries()?$series[$article->getSeries()->getSlug()]:null),
				$article->getSubtitle(),
				$article->getEmbeddedImages()
			);
		}

		$result = new ArticleDatabase($authors, $categories, $series, $articles);
		\file_put_contents($this->cachedir . '/db.serialized', \serialize($result));
		return $result;
	}
}