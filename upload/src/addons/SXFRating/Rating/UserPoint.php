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
		
		return $this->renderer('sxfr_userpoint', $viewParams);
	}
	
	protected function userFinder()
	{
		return $this->finder('XF:User')->order('trophy_points', 'DESC');
	}
}