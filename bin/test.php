#!/usr/bin/php
<?php

\date_default_timezone_set('UTC');

require_once(__DIR__ . '/../vendor/autoload.php');

$markdownReader = new \Opsbears\Refactor\Boundary\Markdown\MarkdownReader();
$markdownReader->read(__DIR__ . '/../data/articles/felreertett-programnyelvek-php.md');
