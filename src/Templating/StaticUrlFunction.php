<?php

namespace Opsbears\Refactor\Templating;

use Piccolo\Templating\TemplateFunction;

class StaticUrlFunction implements TemplateFunction {

	private $staticRoot;
	/**
	 * @var
	 */
	private $staticUrl;
	/**
	 * @var bool
	 */
	private $version;

	/**
	 * @param      $staticRoot
	 * @param      $staticUrl
	 * @param bool $version
	 */
	public function __construct($staticRoot, $staticUrl, $version = true) {
		$this->staticRoot = $staticRoot;
		$this->staticUrl = $staticUrl;
		$this->version = $version;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getName() : string {
		return 'staticUrl';
	}

	public function execute() {
		$value = func_get_arg(0);
		if (\file_exists($this->staticRoot . DIRECTORY_SEPARATOR . $value)) {
			if (!$this->version) {
				return $this->staticUrl . $value;
			} else {
				return $this->staticUrl . '/v' .
					\urlencode(\filemtime($this->staticRoot . DIRECTORY_SEPARATOR . $value)) .
					$value;
			}
		} else {
			return $value;
		}
	}
}