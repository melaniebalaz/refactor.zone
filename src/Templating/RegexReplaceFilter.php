<?php

namespace Opsbears\Refactor\Templating;

use Piccolo\Templating\TemplateFilter;

class RegexReplaceFilter implements TemplateFilter {
	/**
	 * {@inheritdoc}
	 */
	public function getName() : string {
		return 'regexp_replace';
	}

	/**
	 * Filter a certain value and return the modified result.
	 *
	 * @param mixed $value
	 *
	 * @return mixed
	 */
	public function filter($value) {
		$pattern = func_get_arg(1);
		$replacement = func_get_arg(2);
		return preg_replace($pattern, $replacement, $value);
	}
}