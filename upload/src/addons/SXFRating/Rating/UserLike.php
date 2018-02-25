<?php

namespace SXFRating\Rating;

class UserLike extends AbstractRating
{
	public function getTitle()
	{
		return \XF::phrase('sxfr_userlike');
	}
	
	public function getIcon()
	{
		return 'fa-star-half-o';
	}
	
	public function render()
	{
		$limit = $this->getLimit();
		
		$users = $this->finder('XF:User')->order('like_count', 'DESC')->limit($limit)->fetch();
		
		$list = array_values($users->toArray());
		$newList = [];
		
		for ($i = 0; $i < count($list); $i++)
		{
			$newList[$i + 1] = $list[$i];
		}
		
		$viewParams = [
			'users' => $newList
		];
		
		return $this->renderer('sxfr_userlike', $viewParams);
	}
}