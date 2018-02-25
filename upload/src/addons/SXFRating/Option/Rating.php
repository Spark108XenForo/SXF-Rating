<?php

namespace SXFRating\Option;

use XF\Option\AbstractOption;

class Rating extends AbstractOption
{
	public static function render(\XF\Entity\Option $option, array $htmlParams)
	{
		/** @var \SXFRating\Repository\Rating $ratingRepo */
		$ratingRepo = \XF::repository('SXFRating:Rating');
		
		$types = $ratingRepo->getTypes();
		
		$choices = [];
		$value = [];
		
		foreach (array_keys($types) as $type)
		{
			$rating = $ratingRepo->getRating($type);
			
			$choices[$type] = $rating->title;
		}
		
		return self::getCheckBoxRow($option, $htmlParams, $choices, $option->option_value);
	}
}