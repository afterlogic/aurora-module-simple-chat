<?php
/**
 * @copyright Copyright (c) 2017, Afterlogic Corp.
 * @license AGPL-3.0
 *
 * This code is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License, version 3,
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License, version 3,
 * along with this program. If not, see <http://www.gnu.org/licenses/>
 */

namespace Aurora\Modules\SimpleChat;

/**
 * @package Modules
 */
class Module extends \Aurora\System\Module\AbstractModule
{
	public $oApiChatManager = null;
	
	public function init() 
	{
		$this->oApiChatManager = $this->GetManager();
		
		$this->extendObject('CUser', array(
				'EnableModule' => array('bool', true)
			)
		);
	}
	
	/**
	 * Obtains list of module settings for authenticated user.
	 * 
	 * @return array
	 */
	public function GetSettings()
	{
		\Aurora\System\Api::checkUserRoleIsAtLeast(\EUserRole::Anonymous);
		
		$oUser = \Aurora\System\Api::getAuthenticatedUser();
		if (!empty($oUser) && $oUser->Role === \EUserRole::NormalUser)
		{
			return array(
				'EnableModule' => $oUser->{$this->GetName().'::EnableModule'}
			);
		}
		
		return null;
	}
	
	/**
	 * Updates settings of the Simple Chat Module.
	 * 
	 * @param boolean $EnableModule indicates if user turned on Simple Chat Module.
	 * @return boolean
	 */
	public function UpdateSettings($EnableModule)
	{
		\Aurora\System\Api::checkUserRoleIsAtLeast(\EUserRole::NormalUser);
		
		$iUserId = \Aurora\System\Api::getAuthenticatedUserId();
		if (0 < $iUserId)
		{
			$oCoreDecorator = \Aurora\System\Api::GetModuleDecorator('Core');
			$oUser = $oCoreDecorator->GetUser($iUserId);
			$oUser->{$this->GetName().'::EnableModule'} = $EnableModule;
			$oCoreDecorator->UpdateUserObject($oUser);
		}
		return true;
	}
	
	public function GetPostsCount()
	{
		\Aurora\System\Api::checkUserRoleIsAtLeast(\EUserRole::Customer);
		
		return $this->oApiChatManager->GetPostsCount();
	}
	
	/**
	 * Obtains posts of Simple Chat Module.
	 * 
	 * @param int $Offset uses for obtaining a partial list.
	 * @param int $Limit uses for obtaining a partial list.
	 * @return array
	 */
	public function GetPosts($Offset, $Limit)
	{
		\Aurora\System\Api::checkUserRoleIsAtLeast(\EUserRole::Customer);
		
		$aPosts = $this->oApiChatManager->GetPosts($Offset, $Limit);
		return array(
			'Offset' => $Offset,
			'Limit' => $Limit,
			'Collection' => $aPosts
		);
	}

	/**
	 * Creates a new post for authenticated user.
	 * 
	 * @param string $Text text of the new post.
	 * @param string $Date date of the new post.
	 * @return boolean
	 */
	public function CreatePost($Text, $Date)
	{
		\Aurora\System\Api::checkUserRoleIsAtLeast(\EUserRole::NormalUser);
		
		$iUserId = \Aurora\System\Api::getAuthenticatedUserId();
		$this->oApiChatManager->CreatePost($iUserId, $Text, $Date);
		return true;
	}	
}
