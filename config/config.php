<?php

use Opsbears\Refactor\Module\ApplicationModule;
use Opsbears\Refactor\Web\ArticleController;
use Opsbears\Refactor\Web\CategoryController;
use Opsbears\Refactor\Web\ErrorController;
use Opsbears\Refactor\Web\SeriesController;
use Opsbears\Refactor\Web\StartPageController;

$localConfig = [];
if (\file_exists(__DIR__ . '/local.config.php')) {
	$localConfig = include(__DIR__ . '/local.config.php');
}

return \array_merge([
	/**
	 * Piccolo modules to load. These modules will consume the configuration below.
	 */
	'modules'   => [
		/**
		 * Our application here. This will load the other required modules.
		 */
		ApplicationModule::class,
	],
	/**
	 * Routing information via FastRoute. (This can be easily replaced.)
	 *
	 * @see https://github.com/nikic/FastRoute
	 */
	'fastroute' => [
		/**
		 * Error handlers. The fastroute module supports 404, 405 and 500 error handlers.
		 */
		'errorHandlers' => [
			404 => [ErrorController::class, 'notFound'],
			405 => [ErrorController::class, 'methodNotAllowed'],
			500 => [ErrorController::class, 'error'],
		],
		/**
		 * Routes. Examples:
		 *
		 * ['GET',  '/{slug:[a-zA-Z\-]+}', BlogController::class, 'postAction'],
		 */
		'routes'        => [
			['GET', '/', StartPageController::class, 'startPageAction'],
			['GET', '/page/{page:[0-9]+}', StartPageController::class, 'pageAction'],
			['GET', '/feed', SeriesController::class, 'feedAction'],
			['GET', '/series', SeriesController::class, 'indexAction'],
			['GET', '/series/{slug:[a-zA-Z\-]+}', SeriesController::class, 'seriesAction'],
			['GET', '/series/{slug:[a-zA-Z\-]+}/page/{page:[0-9]+}', SeriesController::class, 'pageAction'],
			['GET', '/series/{slug:[a-zA-Z\-]+}/feed', SeriesController::class, 'feedAction'],
			['GET', '/category', CategoryController::class, 'indexAction'],
			['GET', '/category/{slug:[a-zA-Z\-]+}', CategoryController::class, 'categoryAction'],
			['GET', '/category/{slug:[a-zA-Z\-]+}/page/{page:[0-9]+}', CategoryController::class, 'pageAction'],
			['GET', '/category/{slug:[a-zA-Z\-]+}/feed', CategoryController::class, 'feedAction'],
			['GET', '/{slug:[a-zA-Z\-]+}', ArticleController::class, 'articleAction'],
		],
	],
	'twig'      => [
		'debug' => false,
	],
], $localConfig);
