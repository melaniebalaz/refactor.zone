<?php

namespace Opsbears\Refactor\Web;

use Opsbears\Refactor\Boundary\NotFoundException;

class AffiliateController extends AbstractController {
	public function bookAction($book) {
		$target = null;
		$targets = [];
		switch ($book) {
			case 'uncle-bob-clean-code':
				$targets = [
					'default' => 'https://www.amazon.com/gp/product/0132350882/ref=as_li_tl?ie=UTF8&camp=1789&creative=9325&creativeASIN=0132350882&linkCode=as2&tag=opsbears-20&linkId=ef9a41d007d27d16d98f877a4927d0a5',
					'de' => 'https://www.amazon.de/gp/product/0132350882/ref=as_li_tl?ie=UTF8&camp=1638&creative=6742&creativeASIN=0132350882&linkCode=as2&tag=opsbears-21',
					'at' => 'https://www.amazon.de/gp/product/0132350882/ref=as_li_tl?ie=UTF8&camp=1638&creative=6742&creativeASIN=0132350882&linkCode=as2&tag=opsbears-21',
					'ch' => 'https://www.amazon.de/gp/product/0132350882/ref=as_li_tl?ie=UTF8&camp=1638&creative=6742&creativeASIN=0132350882&linkCode=as2&tag=opsbears-21',
					'uk' => 'https://www.amazon.co.uk/gp/product/0132350882/ref=as_li_tl?ie=UTF8&camp=1634&creative=6738&creativeASIN=0132350882&linkCode=as2&tag=opsbears04-21',
					'gb' => 'https://www.amazon.co.uk/gp/product/0132350882/ref=as_li_tl?ie=UTF8&camp=1634&creative=6738&creativeASIN=0132350882&linkCode=as2&tag=opsbears04-21',
					'ie' => 'https://www.amazon.co.uk/gp/product/0132350882/ref=as_li_tl?ie=UTF8&camp=1634&creative=6738&creativeASIN=0132350882&linkCode=as2&tag=opsbears04-21',
				];
				break;
		}
		if (!empty($targets)) {
			if (function_exists('geoip_country_code_by_name')) {
				$country = strtolower(geoip_country_code_by_name(
					$this->getRequest()->getServerParams()['REMOTE_ADDR']));
				if (array_key_exists($country, $targets)) {
					$target = $targets[$country];
				}
			}
			if (empty($target)) {
				$target = $targets['default'];
			}
		}

		if (!$target) {
			throw new NotFoundException();
		}
		return $this->redirectTo($target, true);
	}
}