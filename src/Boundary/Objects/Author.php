<?php

namespace Opsbears\Refactor\Boundary\Objects;

class Author extends TextContent {
	private $url;
	/**
	 * @var string
	 */
	private $facebookUrl;
	/**
	 * @var string
	 */
	private $firstName;
	/**
	 * @var string
	 */
	private $lastName;
	/**
	 * @var string
	 */
	private $gender;
	/**
	 * @var string
	 */
	private $htmlBody;
	/**
	 * @var string
	 */
	private $twitterUrl;
	/**
	 * @var string
	 */
	private $gplusUrl;

	public function __construct(
		string $slug,
		string $name,
		string $url,
		string $htmlBody,
		string $firstName,
		string $lastName,
		string $gender,
		string $facebookUrl,
		string $twitterUrl,
		string $gplusUrl
	) {
		parent::__construct($slug, $name, $htmlBody);
		$this->url         = $url;
		$this->facebookUrl = $facebookUrl;
		$this->firstName   = $firstName;
		$this->lastName    = $lastName;
		$this->gender      = $gender;
		$this->htmlBody    = $htmlBody;
		$this->twitterUrl  = $twitterUrl;
		$this->gplusUrl    = $gplusUrl;
	}

	/**
	 * @return string
	 */
	public function getUrl(): string {
		return $this->url;
	}

	/**
	 * @return string
	 */
	public function getFirstName(): string {
		return $this->firstName;
	}

	/**
	 * @return string
	 */
	public function getLastName(): string {
		return $this->lastName;
	}

	/**
	 * @return string
	 */
	public function getGender(): string {
		return $this->gender;
	}

	/**
	 * @return string
	 */
	public function getFacebookUrl(): string {
		return $this->facebookUrl;
	}

	/**
	 * @return string
	 */
	public function getTwitterUrl(): string {
		return $this->twitterUrl;
	}

	/**
	 * @return string
	 */
	public function getGplusUrl(): string {
		return $this->gplusUrl;
	}
}