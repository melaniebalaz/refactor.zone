<?php

namespace Opsbears\Refactor\Web;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

abstract class AbstractController {
	/**
	 * @var ServerRequestInterface
	 */
	private $request;
	/**
	 * @var ResponseInterface
	 */
	private $response;

	public function __construct(ServerRequestInterface $request, ResponseInterface $response) {
		$this->request = $request;
		$this->response = $response;
	}

	protected function getRequest(): ServerRequestInterface {
		return $this->request;
	}

	protected function getResponse(): ResponseInterface {
		return $this->response;
	}

	protected function redirectToPath($path) {
		return $this->getResponse()->withHeader('Location', (string)$this->getRequest()->getUri()->withPath($path));
	}
}