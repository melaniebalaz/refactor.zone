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

		$embeddedImages = [];

		$html = $textContent->getHtmlBody();
		\preg_match_all('/<image src="(?P<png>.*?)" \\/><\\/svg>/s', $html, $matches);
		$i = 0;
		$replace = [];
		foreach ($matches['png'] as $match) {
			$replace[$match] = $i;
			$embeddedImages[$i] = \base64_decode(\str_replace('data:image/png;base64,', '', $match));
			$i++;
		}
		$html = preg_replace_callback(
			'/<image src="(?P<png>.*?)" \\/><\\/svg>/s',
			function($matches) use ($replace, $slug) {
				return '<image src="/' . urlencode($slug) . '/' . $replace[$matches['png']] . '.png" /></svg>';
			}, $html);

		return new Article(
			$slug,
			$metadata['title'],
			$author,
			new \DateTime($metadata['published']),
			$modified,
			$metadata['excerpt'],
			$html,
			$metadata['social'],
			$metadata['decor'],
			$metadata['decor2x'],
			new CategoryList($categories),
			$series,
			(isset($metadata['subtitle'])?$metadata['subtitle']:''),
			$embeddedImages
		);
	}
}