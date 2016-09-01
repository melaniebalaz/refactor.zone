<?php

namespace Opsbears\Refactor\Boundary\Objects;

class ArticleDatabase {
	/**
	 * @var array|Author[]
	 */
	private $authors;
	/**
	 * @var array|Category[]
	 */
	private $categories;
	/**
	 * @var array|Series[]
	 */
	private $series;
	/**
	 * @var array|Article[]
	 */
	private $articles;

	/**
	 * ArticleDatabase constructor.
	 *
	 * @param ArticleAwareAuthor[]   $authors
	 * @param ArticleAwareCategory[] $categories
	 * @param ArticleAwareSeries[]   $series
	 * @param Article[]              $articles
	 */
	public function __construct(array $authors, array $categories, array $series, array $articles) {
		$this->authors    = $authors;
		$this->categories = $categories;
		$this->series     = $series;
		$this->articles   = $articles;
	}

	/**
	 * @return array|ArticleAwareAuthor[]
	 */
	public function getAuthors() {
		return $this->authors;
	}

	/**
	 * @return array|ArticleAwareCategory[]
	 */
	public function getCategories() {
		return $this->categories;
	}

	/**
	 * @return array|ArticleAwareSeries[]
	 */
	public function getSeries() {
		return $this->series;
	}

	/**
	 * @return array|Article[]
	 */
	public function getArticles() {
		return $this->articles;
	}
}