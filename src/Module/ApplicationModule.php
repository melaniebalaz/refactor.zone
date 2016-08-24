<?php


namespace Opsbears\Refactor\Module;

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
}
