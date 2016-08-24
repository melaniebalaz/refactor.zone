<?php


namespace Opsbears\Refactor\Web;

class ErrorController extends AbstractController  {
	public function error(\Exception $exception) {
		return [
			'exception' => $exception
		];
	}

	public function notFound() {
		return [];
	}

	public function methodNotAllowed($allowedMethods) {
		return ['allowedMethods' => $allowedMethods];
	}
}