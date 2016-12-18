<?php

namespace Opsbears\Refactor\Web;

class LiveController extends AbstractController {
	public function liveAction() {
		return [
			'categories' => $this->getArticleProvider()->getCategories()->getCategories(),
		];
	}
}