<?php

namespace Opsbears\Refactor\Web;

use Opsbears\Refactor\Boundary\ArticleProvider;
use Opsbears\Refactor\Templating\StaticUrlFunction;
use Piccolo\Web\Processor\Controller\DataObjects\HTTPRequestResponseContainer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;

abstract class AbstractController {
	/**
	 * @var ServerRequestInterface
	 */
	private $request;
	/**
	 * @var ResponseInterface
	 */
	private $response;
	/**
	 * @var ArticleProvider
	 */
	private $articleProvider;
	/**
	 * @var HTTPRequestResponseContainer
	 */
	private $requestResponseContainer;

	/**
	 * @var \DateTime|null
	 */
	private $lastModified = null;

	public function __construct(
		ArticleProvider        $articleProvider,
		ServerRequestInterface $request,
		ResponseInterface      $response,
		HTTPRequestResponseContainer $requestResponseContainer,
		StaticUrlFunction $staticUrlFunction
	) {
		$this->request         = $request;
		$this->response        = $response;
		$this->articleProvider = $articleProvider;
		$this->requestResponseContainer = $requestResponseContainer;

		$this->setResponse(
			$this->getResponse()->withAddedHeader('Link', '<' . $staticUrlFunction->execute('/css/site.min.css') . '>' .
				'; rel="preload"; as="style"'));
	}

	protected function getArticleProvider(): ArticleProvider {
		return $this->articleProvider;
	}

	protected function getRequest(): ServerRequestInterface {
		return $this->request;
	}

	protected function getResponse(): ResponseInterface {
		return $this->response;
	}

	protected function setResponse(ResponseInterface $response) {
		$this->response = $response;
		$this->requestResponseContainer->setResponse($response);
	}

	protected function redirectToPath($path, bool $permanent = false) {
		return $this->redirectTo((string)$this->getRequest()->getUri()->withPath($path), $permanent);
	}

	protected function redirectTo(string $url, bool $permanent = false) {
		return $this->getResponse()
					->withHeader('Location', (string)$url)
					->withStatus(($permanent?301:302));
	}

	protected function setLastModified(\DateTime $lastModified) {
		if (!$this->lastModified || $this->lastModified->getTimestamp() < $lastModified->getTimestamp()) {
			$this->lastModified = $lastModified;
			$this->setResponse(
				$this->getResponse()
					->withoutHeader('Last-Modified')
					->withHeader('Last-Modified', $lastModified->format('D, d M Y H:i:s'))
			);
		}
	}
}