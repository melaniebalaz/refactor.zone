<?php

namespace Opsbears\Refactor\Boundary\Markdown;

use Opsbears\Refactor\Boundary\Objects\Category;

class CategoryConverter {
	/**
	 * @var string
	 */
	private $datadir;
	/**
	 * @var MarkdownReader
	 */
	private $reader;

	public function __construct(string $datadir, MarkdownReader $reader) {
		$this->datadir = $datadir;
		$this->reader = $reader;
	}

	public function convert($slug) : Category {
		$markdown = $this->reader->read($this->datadir . '/categories/' . $slug . '.md');
		$metadata = $markdown->getMetadata();

		return new Category($slug, $metadata['name'], $markdown->getHtmlBody());
	}
}
