<?php

namespace Opsbears\Refactor\Boundary\Markdown\OutputFormat;

use Highlight\Highlighter;
use MarkdownExtended\Util\Helper;

class Html extends \MarkdownExtended\OutputFormat\Html  {
	public function buildPreformatted($text = null, array $attributes = array()) {
		if (isset($attributes['language'])) {
			if ($attributes['language'] == 'dotsvg') {
				$tempfile = \tempnam(\sys_get_temp_dir(), 'graph-');
				$dotfile  = $tempfile . '.dot';
				$svgfile  = $tempfile . '.svg';
				$pngfile  = $tempfile . '.png';
				\file_put_contents($dotfile, \html_entity_decode($text));
				exec('/usr/local/bin/dot -Tsvg ' . escapeshellarg($dotfile) . ' -o' . escapeshellarg($svgfile));
				exec('/usr/local/bin/dot -Tpng ' . escapeshellarg($dotfile) . ' -o' . escapeshellarg($pngfile));
				$svg = \file_get_contents($svgfile);
				$png = \file_get_contents($pngfile);
				\unlink($pngfile);
				\unlink($svgfile);
				\unlink($dotfile);
				$svg = \preg_replace('/<\?xml(.*?)\?>/s', '', $svg);
				$svg = \preg_replace('/<!--(.*?)-->/s', '', $svg);
				$svg = \preg_replace('/<!DOCTYPE(.*?)>/s', '', $svg);
				$png = 'data:image/png;base64,' . \base64_encode($png);
				$svg = \preg_replace('/<\/svg>/', '<image src="' . \htmlspecialchars($png) . '" /></svg>', $svg);
				return $svg;
			} else {
				$highlighter = new Highlighter();
				$text        = $highlighter->highlight($attributes['language'], $text)->value;
				return "\n" . $this->getTagString($this->getTagString($text, 'code'), 'pre') . "\n";
			}
		} else {
			return "\n" . $this->getTagString($text, 'pre', $attributes) . "\n";
		}
	}
}