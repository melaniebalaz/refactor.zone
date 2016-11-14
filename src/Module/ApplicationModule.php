<?php


namespace Opsbears\Refactor\Module;

use Opsbears\Refactor\Boundary\ArticleProvider;
use Opsbears\Refactor\Boundary\Markdown\ArticleConverter;
use Opsbears\Refactor\Boundary\Markdown\ArticleIndexer;
use Opsbears\Refactor\Boundary\Markdown\AuthorConverter;
use Opsbears\Refactor\Boundary\Markdown\CategoryConverter;
use Opsbears\Refactor\Boundary\Markdown\MarkdownArticleProvider;
use Opsbears\Refactor\Boundary\Markdown\SeriesConverter;
use Opsbears\Refactor\Boundary\Objects\Category;
use Opsbears\Refactor\Templating\StaticUrlFunction;
use Piccolo\DependencyInjection\DependencyInjectionContainer;
use Piccolo\Module\AbstractModule;
use Piccolo\Templating\Engine\Twig\TwigTemplateEngine;
use Piccolo\Templating\Engine\Twig\TwigTemplatingModule;
use Piccolo\Templating\TemplatingModule;
use Piccolo\Web\HTTP\Guzzle\GuzzleHTTPModule;
use Piccolo\Web\IO\Standard\StandardWebIOModule;
use Piccolo\Web\Processor\Controller\ControllerProcessorWebModule;
use Piccolo\Web\Processor\Controller\View\Templating\TemplatingViewModule;
use Piccolo\Web\Routing\FastRoute\FastRouteModule;

class ApplicationModule extends AbstractModule {
	public function getModuleKey() : string {
		return 'refactor';
	}

	public function getRequiredModules() : array {
		return [
			/**
			 * Use Guzzle for HTTP request and response constructions
			 */
			GuzzleHTTPModule::class,
			/**
			 * Standard PHP input and output mechanisms
			 */
			StandardWebIOModule::class,
			/**
			 * Use FastRoute for routing
			 */
			FastRouteModule::class,
			/**
			 * Use Twig as the templating engine
			 */
			TwigTemplatingModule::class,
			/**
			 * We want to use templating
			 */
			TemplatingModule::class,
			/**
			 * Use templating for the view
			 */
			TemplatingViewModule::class,
			/**
			 * Routing and controller handling
			 */
			ControllerProcessorWebModule::class,
		];
	}

	public function loadConfiguration(array &$moduleConfig, array &$globalConfig) {
		/**
		 * @var TemplatingViewModule $templatingViewModule
		 */
		$templatingViewModule = $this->getRequiredModule(TemplatingViewModule::class);
		$templatingViewModule->addTemplateRoot($globalConfig, __DIR__ . '/../Web/Views','Opsbears\\Refactor\\Web\\');
	}

	public function configureDependencyInjection(DependencyInjectionContainer $dic,
												 array $moduleConfig,
												 array $globalConfig) {
		$dic->alias(ArticleProvider::class, MarkdownArticleProvider::class);
		$dic->setClassParameters(ArticleConverter::class,  ['datadir' => $moduleConfig['datadir']]);
		$dic->setClassParameters(CategoryConverter::class, ['datadir' => $moduleConfig['datadir']]);
		$dic->setClassParameters(SeriesConverter::class,   ['datadir' => $moduleConfig['datadir']]);
		$dic->setClassParameters(AuthorConverter::class,   ['datadir' => $moduleConfig['datadir']]);
		$dic->setClassParameters(ArticleIndexer::class,    [
			'datadir' => $moduleConfig['datadir'],
			'cachedir' => $moduleConfig['cachedir'],
			'production' => $moduleConfig['production'],
		]);
		$dic->setClassParameters(StaticUrlFunction::class,   [
			'staticRoot' => __DIR__ . '/../../htdocs/',
			'staticUrl' => '',
			'version' => $moduleConfig['production']
		]);
	}
}
