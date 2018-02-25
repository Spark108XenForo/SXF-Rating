<?php

namespace SXFRating\Rating;

class Rating implements \ArrayAccess
{
	/**
	 * @var App
	 */
	protected $app;
	
	protected $handler;
	
	protected $data;
	
	public function __construct(\XF\App $app, $type)
	{
		$this->app = $app;
		
		$data = [
			'type' => $type
		];
		
		$repo = \XF::repository('SXFRating:Rating');
		
		$types = $repo->getTypes();
		
		if (isset($types[$type]))
		{
			$class = $types[$type];
			$handler = $this->getHandler($class);
			
			if ($handler instanceof AbstractRating)
			{
				$this->handler = $handler;
				
				$data = array_merge($data, [
			
					'title' => $handler->getTitle(),
					'limit' => $handler->getLimit(),
					'icon' => $handler->getIcon(),
					
					'html' => function() use ($handler)
					{
						return $handler->complete();
					}
				
				]);
			}
		}
		
		$this->data = $data;
	}
	
	/**
	 * @return \SXFRating\Rating\AbstractRating|null
	 */
	public function getHandler($class)
	{
		$class = \XF::stringToClass($class, '%s\Rating\%s');
		if (!class_exists($class))
		{
			return null;
		}

		$class = \XF::extendClass($class);
		
		return new $class($this->app);
	}
	
	public function is()
	{
		$opt = \XF::options();
		
		if (array_search($this->type, $opt->sxfr) === false)
		{
			return false;
		}
		
		return $this->handler ? true : false;
	}
	
	public function getTitle()
	{
		return $this->title;
	}
	
	public function getLimit()
	{
		return $this->limit;
	}
	
	public function hasFaIcon()
	{
		if ($this->icon && !$this->hasIcon() && strpos($this->icon, 'fa-') === 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public function hasIcon()
	{
		if ($this->icon && file_exists($this->icon))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public function get($key)
	{
		if (isset($this->data[$key]))
		{
			$value = $this->data[$key];
			
			if (is_callable($value))
			{
				return $value();
			}
			
			return $value;
		}
		
		return null;
	}
	
	public function __get($key)
	{
		return $this->get($key);
	}

	public function offsetGet($key)
	{
		return $this->get($key);
	}
	
	public function __set($key, $value)
	{
		$this->data[$key] = $value;
	}
	
	public function offsetSet($key, $value)
	{
		$this->__set($key, $value);
	}
	
	public function __isset($key)
	{
		return isset($this->data[$key]);
	}
	
	public function offsetExists($key)
	{
		return $this->__isset($key);
	}
	
	public function __unset($key)
	{
		unset($this->data[$key]);
	}
	
	public function offsetUnset($key)
	{
		$this->__unset($key);
	}
	
	/**
	 * @return \SXFRating\Repository\Rating
	 */
	protected function getRatingRepo()
	{
		return \XF::repository('SXFRating:Rating');
	}
}