<?php

namespace SXFRating\Repository;

use XF\Mvc\Entity\Repository;

class Rating extends Repository
{
	public function getTypes($ratings = [])
	{
		$ratings = array_merge($ratings, [
		
			'user_likes' => 'SXFRating:UserLike',
			'user_message' => 'SXFRating:UserMessage',
			'user_ball' => 'SXFRating:UserPoint'
			
		]);
		
		$xfrmAddOn = \XF::em()->find('XF:AddOn', 'XFRM');
		
		if ($xfrmAddOn)
		{
			$ratings['user_resource'] = 'SXFRating:UserResource';
		}
		
		return $ratings;
	}
	
	public function getRatingsForList()
	{
		$types = $this->getTypes();
		
		$repo = $this;
		
		return array_map(function($key) use ($repo) {
			
			return $repo->getRating($key);
			
		}, array_keys($types));
	}
	
	/**
	 * @var Type
	 *
	 * return \SXFRating\Rating\Rating
	 */
	public function getRating($type)
	{
		return new \SXFRating\Rating\Rating($this->app(), $type);
	}
}