<?php

namespace Opsbears\Refactor\Boundary\Objects;

class Article extends TextContent {
	/**
	 * @var CategoryList
	 */
	private $categories;
	/**
	 * @var Series
	 */
	private $series;
	/**
	 * @var Author
	 */
	private $author;
	/**
	 * @var \DateTime
	 */
	private $published;
	/**
	 * @var string
	 */
	private $excerpt;
	/**
	 * @var \DateTime
	 */
	private $modified;
	/**
	 * @var string
	 */
	private $socialImage;

	public function __construct(
		string $slug,
		string $name,
		Author $author,
		\DateTime $published,
		\DateTime $modified,
		string $excerpt,
		string $htmlBody,
		string $socialImage,
		CategoryList $categories,
		Series $series = null
	) {
		parent::__construct($slug, $name, $htmlBody);
		$this->categories = $categories;
		$this->series     = $series;
		$this->author     = $author;
		$this->published  = $published;
		$this->excerpt    = $excerpt;
		$this->modified = $modified;
		$this->socialImage = $socialImage;
	}

	public function getPublished(): \DateTime {
		return $this->published;
	}

	public function getModified(): \DateTime {
		return $this->modified;
	}

	public function getAuthor(): Author {
		return $this->author;
	}

	public function getTitle() : string {
		return $this->getName();
	}

	/**
	 * @return CategoryList|Category[]
	 */
	public function getCategories() : CategoryList {
		return $this->categories;
	}

	public function getSeries() {
		return $this->series;
	}

	public function getExcerpt(): string {
		return $this->excerpt;
	}

	public function getSocialImage(): string {
		return $this->socialImage;
	}
}
