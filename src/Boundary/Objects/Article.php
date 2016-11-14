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
	/**
	 * @var string
	 */
	private $decor;
	/**
	 * @var string
	 */
	private $decor2x;

	/**
	 * @var string
	 */
	private $subtitle;
	/**
	 * @var array
	 */
	private $embeddedImages = [];

	public function __construct(
		string $slug,
		string $name,
		Author $author,
		\DateTime $published,
		\DateTime $modified,
		string $excerpt,
		string $htmlBody,
		string $socialImage,
		string $decor,
		string $decor2x,
		CategoryList $categories,
		Series $series = null,
		string $subtitle = '',
		array $embeddedImages = []
	) {
		parent::__construct($slug, $name, $htmlBody);
		$this->categories = $categories;
		$this->series     = $series;
		$this->author     = $author;
		$this->published  = $published;
		$this->excerpt    = $excerpt;
		$this->modified = $modified;
		$this->socialImage = $socialImage;
		$this->decor = $decor;
		$this->decor2x = $decor2x;
		$this->subtitle = $subtitle;
		$this->embeddedImages = $embeddedImages;
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

	public function getFullTitle() : string {
		return ($this->series?$this->series->getName() . ' — ':'') .
			$this->getTitle() .
			($this->getSubtitle()?' — ' . $this->getSubtitle():'');
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

	/**
	 * @return string
	 */
	public function getDecor(): string {
		return $this->decor;
	}

	/**
	 * @return string
	 */
	public function getDecor2x(): string {
		return $this->decor2x;
	}

	/**
	 * @return string
	 */
	public function getSubtitle(): string {
		return $this->subtitle;
	}

	/**
	 * Array of embedded PNG images.
	 *
	 * @return array
	 */
	public function getEmbeddedImages(): array {
		return $this->embeddedImages;
	}

	/**
	 * Return a PNG embedded image
	 *
	 * @param int $number
	 *
	 * @return string
	 *
	 * @throws \OutOfBoundsException
	 */
	public function getEmbeddedImage(int $number) : string {
		if (!isset($this->embeddedImages[$number])) {
			throw new \OutOfBoundsException();
		}
		return $this->embeddedImages[$number];
	}
}
