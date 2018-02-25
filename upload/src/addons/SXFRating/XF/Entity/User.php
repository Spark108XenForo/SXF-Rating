<?php

namespace SXFRating\XF\Entity;

class User extends XFCP_User
{
	public function canViewRating()
	{
		return $this->hasPermission('sxfr', 'viewRating');
	}
}