<?php

namespace SXFRating\Rating;

class UserResource extends AbstractRating
{
	public function getTitle()
	{
		return \XF::phrase('sxfr_userresource');
	}
	
	public function getIcon()
	{
		return 'fa-database';
	}
	
	public function render()
	{
		$limit = $this->getLimit();
		
		$users = $this->finder('XF:User')->order('xfrm_resource_count', 'DESC')->limit($limit)->fetch();
		
		$list = array_values($users->toArray());
		$newList = [];
		
		for ($i = 0; $i < count($list); $i++)
		{
			$newList[$i + 1] = $list[$i];
		}
		
		$viewParams = [
			'users' => $newList
		];
		
		return $this->renderer('sxfr_userresource', $viewParams);
	}
}