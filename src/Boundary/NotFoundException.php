<?php

namespace Opsbears\Refactor\Boundary;

class NotFoundException extends \Exception {
	public function __construct() {
		parent::__construct('', 404);
	}
}