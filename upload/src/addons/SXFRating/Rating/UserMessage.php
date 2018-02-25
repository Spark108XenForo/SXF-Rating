<?php

namespace SXFRating\Rating;

class UserMessage extends AbstractRating
{
	public function getTitle()
	{
		return \XF::phrase('sxfr_usermessage');
	}
	
	public function getIcon()
	{
		return 'fa-comments-o';
	}
	
	public function render()
	{
		$limit = $this->getLimit();
		
		$users = $this->finder('XF:User')->order('message_count', 'DESC')->limit($limit)->fetch();
		
		$list = array_values($users->toArray());
		$newList = [];
		
		for ($i = 0; $i < count($list); $i++)
		{
			$newList[$i + 1] = $list[$i];
		}
		
		$viewParams = [
			'users' => $newList
		];
		
		return $this->renderer('sxfr_usermessage', $viewParams);
	}
}