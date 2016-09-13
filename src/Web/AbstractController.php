<?php

namespace Opsbears\Refactor\Web;

use Opsbears\Refactor\Boundary\ArticleProvider;
use Piccolo\Web\Processor\Controller\DataObjects\HTTPRequestResponseContainer;
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
		HTTPRequestResponseContainer $requestResponseContainer
	) {
		$this->request         = $request;
		$this->response        = $response
			->withAddedHeader('Link', '</css/site.min.css>; rel=prefetch')
			->withAddedHeader('Link', '</js/site.min.js>; rel=prefetch')
			->withAddedHeader('Link', '</images/refactor-zone-logo.png>; rel=prefetch')
			->withAddedHeader('Link', '</fonts/opensans/Regular/OpenSans-Regular.woff?v=1.1.0>; rel=prefetch')
			->withAddedHeader('Link', '</fonts/font-awesome/fontawesome-webfont.woff2?v=4.6.3>; rel=prefetch')
			->withAddedHeader('Link', '</fonts/opensans/Bold/OpenSans-Bold.woff?v=1.1.0>; rel=prefetch');
		$this->articleProvider = $articleProvider;
		$this->requestResponseContainer = $requestResponseContainer;
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

	protected function redirectToPath($path) {
		return $this->getResponse()->withHeader('Location', (string)$this->getRequest()->getUri()->withPath($path));
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