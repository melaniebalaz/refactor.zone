<?php

namespace Opsbears\Refactor\Boundary\Markdown;

use Opsbears\Refactor\Boundary\Objects\Series;

class SeriesConverter {
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

	public function convert($slug) : Series {
		$markdown = $this->reader->read($this->datadir . '/series/' . $slug . '.md');
		$metadata = $markdown->getMetadata();

		return new Series($slug, $metadata['name'], $markdown->getHtmlBody());
	}
}
