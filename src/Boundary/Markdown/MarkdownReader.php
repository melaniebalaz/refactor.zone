<?php

namespace Opsbears\Refactor\Boundary\Markdown;


use MarkdownExtended\MarkdownExtended;
use MarkdownExtended\Parser;
use Opsbears\Refactor\Boundary\Markdown\OutputFormat\Html;

class MarkdownReader {
	public function read($filename) {
		$config = MarkdownExtended::getDefaults();

		$config['output_format_options'][Html::class] = $config['output_format_options']['html'];
		$config['output_format_options'][Html::class]['codeblock_attribute_mask'] = '%%';
		$config['output_format'] = Html::class;

		$parser = new Parser( $config );
		$content = $parser->transformSource( $filename );

		return new TextContent($content->getMetadata(), $content->getBody());
	}
}
