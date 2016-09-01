<?php

namespace Opsbears\Refactor\Boundary;

use Opsbears\Refactor\Boundary\Objects\SeriesList;

class GetSeriesResponse {
	/**
	 * @var SeriesList
	 */
	private $series;

	public function __construct(SeriesList $series) {
		$this->series = $series;
	}

	public function getSeries(): SeriesList {
		return $this->series;
	}
}