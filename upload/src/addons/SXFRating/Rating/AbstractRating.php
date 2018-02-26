<?php

namespace SXFRating\Rating;

abstract class AbstractRating
{
	/**
	 * @var App
	 */
	protected $app;
	
	abstract public function render();
	
	public function __construct(\XF\App $app)
	{
		$this->app = $app;
	}
	
	public function getTitle()
	{
		return \XF::phrase('sxfa_rating');
	}
	
	public function getIcon()
	{
		return '';
	}
	
	public function getTemplate()
	{
		return '';
	}
	
	public function getLimit()
	{
		$limit = $this->app->request->filter('limit', 'int');
		
		if (!$limit)
		{
			$limit = 50;
		}
		
		return $limit;
	}
	
	public function complete($responseType = 200)
	{
		$view = $this->render();
		
		$view->setViewParam('xf', $this->app->getGlobalTemplateData());
		$view->setViewParam('title', $this->getTitle());
		$view->setViewParam('limit', $this->getLimit());
		
		return $view->render();
	}
	
	/**
	 * @param string $templateName
	 * @param array $viewParams
	 *
	 * @return \SXFRating\Rating\RatingRenderer
	 */
	protected function renderer($templateName = '', array $viewParams = [])
	{
		$app = $this->app;
		$class = $app->extendClass('SXFRating\Rating\RatingRenderer');

		return new $class($app->templater(), 'public:' . $templateName, $viewParams);
	}
	
	/**
	 * @return App
	 */
	public function app()
	{
		return $this->app;
	}

	/**
	 * @return \XF\Db\AbstractAdapter
	 */
	public function db()
	{
		return $this->app->db();
	}

	/**
	 * @return \XF\Mvc\Entity\Manager
	 */
	public function em()
	{
		return $this->app->em();
	}

	/**
	 * @param string $repository
	 *
	 * @return \XF\Mvc\Entity\Repository
	 */
	public function repository($repository)
	{
		return $this->app->repository($repository);
	}

	/**
	 * @param $finder
	 *
	 * @return \XF\Mvc\Entity\Finder
	 */
	public function finder($finder)
	{
		return $this->app->finder($finder);
	}

	/**
	 * @param string $finder
	 * @param array $where
	 * @param string|array $with
	 *
	 * @return null|\XF\Mvc\Entity\Entity
	 */
	public function findOne($finder, array $where, $with = null)
	{
		return $this->app->em()->findOne($finder, $where, $with);
	}

	/**
	 * @param string $class
	 *
	 * @return \XF\Service\AbstractService
	 */
	public function service($class)
	{
		return call_user_func_array([$this->app, 'service'], func_get_args());
	}
}