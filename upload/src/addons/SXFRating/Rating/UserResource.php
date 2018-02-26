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
		
		$users = $this->userFinder()->limit($limit)->fetch()->toArray();
		$users = array_values($users);
		
		$viewParams = [
			'users' => $users
		];
		
		$visitor = \XF::visitor();
		
		if ($visitor->user_id)
		{
			$users = $this->userFinder()->fetch()->toArray();
			$users = array_values($users);
			
			foreach ($users as $key => $user)
			{
				if ($user->user_id == $visitor->user_id)
				{
					$viewParams['visitor'] = [
						'user' => $user,
						'key' => $key + 1
					];
					
					break;
				}
			}
		}
		
		return $this->renderer('sxfr_userresource', $viewParams);
	}
	
	protected function userFinder()
	{
		return $this->finder('XF:User')->order('xfrm_resource_count', 'DESC');
	}
}