<?php

namespace Opsbears\Refactor\Web;

class TextController extends AbstractController {
	public function tosAction() {
		return [
			'categories'    => $this->getArticleProvider()->getCategories()->getCategories(),
		];
	}

	public function privacyAction() {
		return [
			'categories'    => $this->getArticleProvider()->getCategories()->getCategories(),
		];
	}

	public function contactAction() {
		return [
			'categories'    => $this->getArticleProvider()->getCategories()->getCategories(),
		];
	}

	public function imprintAction() {
		return [
			'categories'    => $this->getArticleProvider()->getCategories()->getCategories(),
		];
	}
}