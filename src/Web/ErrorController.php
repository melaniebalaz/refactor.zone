<?php


namespace Opsbears\Refactor\Web;

class ErrorController extends AbstractController  {
	public function error(\Exception $exception) {
		return [
			'categories'    => $this->getArticleProvider()->getCategories()->getCategories(),
			'exception' => $exception
		];
	}

	public function notFound() {
		return [
			'categories'    => $this->getArticleProvider()->getCategories()->getCategories(),
		];
	}

	public function methodNotAllowed($allowedMethods) {
		return [
			'categories'    => $this->getArticleProvider()->getCategories()->getCategories(),
			'allowedMethods' => $allowedMethods
		];
	}
}
