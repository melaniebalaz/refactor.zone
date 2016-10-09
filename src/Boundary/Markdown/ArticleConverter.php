<?php

namespace Opsbears\Refactor\Boundary\Markdown;

use Opsbears\Refactor\Boundary\Objects\Article;
use Opsbears\Refactor\Boundary\Objects\CategoryList;

class ArticleConverter {
	/**
	 * @var string
	 */
	private $datadir;
	/**
	 * @var MarkdownReader
	 */
	private $reader;
	/**
	 * @var CategoryConverter
	 */
	private $categoryConverter;
	/**
	 * @var SeriesConverter
	 */
	private $seriesConverter;
	/**
	 * @var AuthorConverter
	 */
	private $authorConverter;

	public function __construct(
		string $datadir,
		MarkdownReader $reader,
		CategoryConverter $categoryConverter,
		SeriesConverter $seriesConverter,
		AuthorConverter $authorConverter
	) {
		$this->datadir = $datadir;
		$this->reader = $reader;
		$this->categoryConverter = $categoryConverter;
		$this->seriesConverter = $seriesConverter;
		$this->authorConverter = $authorConverter;
	}

	public function convert($slug) : Article {
		$file = $this->datadir . '/articles/' . $slug . '.md';
		if (!\preg_match('/^[a-zA-Z0-9\-]+\Z/', $slug) || !\file_exists($file)) {
			throw new \Exception('', 404);
		}
		$modified = new \DateTime();
		$modified->setTimestamp(\filectime($file));
		$textContent = $this->reader->read($file);

		$metadata = $textContent->getMetadata();
		$categories = [];
		if (isset($metadata['categories'])) {
			$categories = \explode(',', $metadata['categories']);
		}
		foreach ($categories as &$category) {
			$category = $this->categoryConverter->convert($category);
		}

		$series = null;
		if (isset($metadata['series'])) {
			$series = $this->seriesConverter->convert($metadata['series']);;
		}

		$author = $this->authorConverter->convert($metadata['author']);

		return new Article(
			$slug,
			$metadata['title'],
			$author,
			new \DateTime($metadata['published']),
			$modified,
			$metadata['excerpt'],
			$textContent->getHtmlBody(),
			$metadata['social'],
			$metadata['decor'],
			$metadata['decor2x'],
			new CategoryList($categories),
			$series);
	}
}