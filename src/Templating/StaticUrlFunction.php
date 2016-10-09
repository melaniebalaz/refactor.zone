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
	 * @param $staticRoot
	 * @param $staticUrl
	 */
	public function __construct($staticRoot, $staticUrl) {
		$this->staticRoot = $staticRoot;
		$this->staticUrl = $staticUrl;
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
			return $this->staticUrl . '/v' .
				\urlencode(\filemtime($this->staticRoot . DIRECTORY_SEPARATOR . $value)) .
				$value;
		} else {
			return $value;
		}
	}
}