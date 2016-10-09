<?php

use Opsbears\Refactor\Module\ApplicationModule;
use Opsbears\Refactor\Templating\RegexReplaceFilter;
use Opsbears\Refactor\Templating\StaticUrlFunction;
use Opsbears\Refactor\Web\ArticleController;
use Opsbears\Refactor\Web\AuthorController;
use Opsbears\Refactor\Web\CategoryController;
use Opsbears\Refactor\Web\ErrorController;
use Opsbears\Refactor\Web\SeriesController;
use Opsbears\Refactor\Web\SitemapController;
use Opsbears\Refactor\Web\StartPageController;
use Opsbears\Refactor\Web\TextController;
use Piccolo\Templating\TemplatingModule;

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
	'refactor' => [
		'datadir' => __DIR__ . '/../data/',
		'cachedir' => __DIR__ . '/../cache/',
		'production' => true
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
			['GET', '/privacy', TextController::class, 'privacyAction'],
			['GET', '/terms', TextController::class, 'tosAction'],
			['GET', '/imprint', TextController::class, 'imprintAction'],
			['GET', '/contact', TextController::class, 'contactAction'],
			['GET', '/page/{page:[0-9]+}', StartPageController::class, 'pageAction'],
			['GET', '/feed', StartPageController::class, 'feedAction'],
			['GET', '/feed/instant', StartPageController::class, 'instantFeedAction'],
			['GET', '/series', SeriesController::class, 'indexAction'],
			['GET', '/series/{slug:[a-zA-Z\-]+}', SeriesController::class, 'seriesAction'],
			['GET', '/series/{slug:[a-zA-Z\-]+}/page/{page:[0-9]+}', SeriesController::class, 'pageAction'],
			['GET', '/series/{slug:[a-zA-Z\-]+}/feed', SeriesController::class, 'feedAction'],
			['GET', '/author', AuthorController::class, 'indexAction'],
			['GET', '/author/{slug:[a-zA-Z\-]+}', AuthorController::class, 'authorAction'],
			['GET', '/author/{slug:[a-zA-Z\-]+}/page/{page:[0-9]+}', AuthorController::class, 'pageAction'],
			['GET', '/author/{slug:[a-zA-Z\-]+}/feed', AuthorController::class, 'feedAction'],
			['GET', '/category', CategoryController::class, 'indexAction'],
			['GET', '/category/{slug:[a-zA-Z\-]+}', CategoryController::class, 'categoryAction'],
			['GET', '/category/{slug:[a-zA-Z\-]+}/page/{page:[0-9]+}', CategoryController::class, 'pageAction'],
			['GET', '/category/{slug:[a-zA-Z\-]+}/feed', CategoryController::class, 'feedAction'],
			['GET', '/sitemap.xml', SitemapController::class, 'sitemapAction'],
			['GET', '/{slug:[a-zA-Z\-]+}', ArticleController::class, 'articleAction'],
			['GET', '/{slug:[a-zA-Z\-]+}/amp', ArticleController::class, 'ampArticleAction'],
			['GET', '/{slug:[a-zA-Z\-]+}/instant', ArticleController::class, 'instantArticleAction'],
		],
	],
	'templating' => [
		TemplatingModule::CONFIG_FUNCTIONS => [
			StaticUrlFunction::class
		],
		TemplatingModule::CONFIG_FILTERS => [
			RegexReplaceFilter::class
		],
	],
	'twig'      => [
		'debug' => false,
	],
], $localConfig);
