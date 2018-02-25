<?php

namespace SXFRating\Pub\Controller;

use XF\Pub\Controller\AbstractController;
use XF\Mvc\ParameterBag;

class Rating extends AbstractController
{
	public function actionIndex(ParameterBag $params)
	{
		$visitor = \XF::visitor();
		
		if (!$visitor->canViewRating())
		{
			return $this->noPermission();
		}
		
		$type = $this->filter('type', 'str');

		if ($type)
		{
			return $this->rerouteController(__CLASS__, 'view', $params);
		}
		
		$repo = $this->getRatingRepo();
		
		$ratings = array_filter($repo->getRatingsForList(), function($rating)
		{
			if ($rating->is())
			{
				return true;
			}
		});

		$viewParams = [
			'ratings' => $ratings
		];
		
		return $this->view(null, 'sxfr_rating_list', $viewParams);
	}
	
	public function actionView(ParameterBag $params)
	{
		$repo = $this->getRatingRepo();
		
		$rating = $repo->getRating($this->filter('type', 'str'));
		
		if (!$rating->is())
		{
			return $this->notFound();
		}
		
		$viewParams = [
			'rating' => $rating
		];
		
		return $this->view(null, 'sxfr_rating_view', $viewParams);
	}
	
	protected function getRatingRepo()
	{
		return $this->repository('SXFRating:Rating');
	}
}