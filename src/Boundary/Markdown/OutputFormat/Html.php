<?php

namespace Opsbears\Refactor\Boundary\Markdown\OutputFormat;

use Highlight\Highlighter;
use MarkdownExtended\Util\Helper;

class Html extends \MarkdownExtended\OutputFormat\Html  {
	public function buildPreformatted($text = null, array $attributes = array()) {
		if (isset($attributes['language'])) {
			$highlighter = new Highlighter();
			$text = $highlighter->highlight($attributes['language'], $text)->value;
			return "\n" . $this->getTagString($this->getTagString($text, 'code'), 'pre') . "\n";
		} else {
			return "\n" . $this->getTagString($text, 'pre', $attributes) . "\n";
		}
	}
}