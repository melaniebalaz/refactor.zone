<?php

namespace Opsbears\Refactor\Web;

class NewsletterController extends AbstractController {
	public function newsletterAction() {
		return [
			'categories'    => $this->getArticleProvider()->getCategories()->getCategories(),
		];
	}

	public function confirmAction() {
		return [
			'categories'    => $this->getArticleProvider()->getCategories()->getCategories(),
		];
	}

	public function thankyouAction() {
		return [
			'categories'    => $this->getArticleProvider()->getCategories()->getCategories(),
		];
	}

	public function unsubscribeAction() {
		return [
			'categories'    => $this->getArticleProvider()->getCategories()->getCategories(),
		];
	}
}