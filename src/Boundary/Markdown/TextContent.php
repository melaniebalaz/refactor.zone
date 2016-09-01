<?php

namespace Opsbears\Refactor\Boundary\Markdown;

class TextContent {
	private $metadata = [];
	private $htmlBody = '';

	public function __construct(array $metadata, string $htmlBody) {
		$this->metadata = $metadata;
		$this->htmlBody = $htmlBody;
	}

	/**
	 * @return array
	 */
	public function getMetadata(): array {
		return $this->metadata;
	}

	/**
	 * @return string
	 */
	public function getHtmlBody(): string {
		return $this->htmlBody;
	}
}
