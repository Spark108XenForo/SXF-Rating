<?php

namespace SXFRating\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

class Rating extends Entity
{
	public static function getStructure(Structure $structure)
	{
		$structure->table = 'xf_sxfr_rating';
		$structure->shortName = 'SXFRating:Rating';
		$structure->primaryKey = 'rating_id';
		$structure->columns = [
			'rating_id' => ['type' => self::STR, 'maxLength' => 50, 'required' => true]
		];
		
		return $structure;
	}
}