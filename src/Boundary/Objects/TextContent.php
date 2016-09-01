<?php

namespace Opsbears\Refactor\Boundary\Objects;

class TextContent {
	/**
	 * @var string
	 */
	private $slug;
	/**
	 * @var string
	 */
	private $name;
	/**
	 * @var string
	 */
	private $htmlBody;

	public function __construct(string $slug, string $name, string $htmlBody) {
		$this->slug     = $slug;
		$this->name     = $name;
		$this->htmlBody = $htmlBody;
	}

	public function getSlug() : string {
		return $this->slug;
	}

	public function getName() : string {
		return $this->name;
	}

	public function getHtmlBody() : string {
		return $this->htmlBody;
	}
}