<?php

namespace SXFRating\Rating;

class UserPoint extends AbstractRating
{
	public function getTitle()
	{
		return \XF::phrase('sxfr_userpoint');
	}
	
	public function getIcon()
	{
		return 'fa-trophy';
	}
	
	public function render()
	{
		$limit = $this->getLimit();
		
		$users = $this->finder('XF:User')->order('trophy_points', 'DESC')->limit($limit)->fetch();
		
		$list = array_values($users->toArray());
		$newList = [];
		
		for ($i = 0; $i < count($list); $i++)
		{
			$newList[$i + 1] = $list[$i];
		}
		
		$viewParams = [
			'users' => $newList
		];
		
		return $this->renderer('sxfr_userpoint', $viewParams);
	}
}