<?php

namespace Opsbears\Refactor\Boundary\Markdown;

use Opsbears\Refactor\Boundary\Objects\Author;
use Opsbears\Refactor\Boundary\Objects\Category;
use Opsbears\Refactor\Boundary\Objects\Series;

class AuthorConverter {
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

	public function convert($slug) : Author {
		$markdown = $this->reader->read($this->datadir . '/authors/' . $slug . '.md');
		$metadata = $markdown->getMetadata();

		return new Author(
			$slug,
			$metadata['name'],
			$metadata['url'],
			$markdown->getHtmlBody(),
			$metadata['firstname'],
			$metadata['lastname'],
			$metadata['gender'],
			$metadata['facebook'],
			$metadata['twitter'],
			$metadata['googleplus'],
			$metadata['image']
		);
	}
}