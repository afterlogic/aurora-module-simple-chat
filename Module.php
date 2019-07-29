<?php
/**
 * This code is licensed under AGPLv3 license or Afterlogic Software License
 * if commercial version of the product was purchased.
 * For full statements of the licenses see LICENSE-AFTERLOGIC and LICENSE-AGPL3 files.
 */

namespace Aurora\Modules\SimpleChat;

/**
 * @license https://www.gnu.org/licenses/agpl-3.0.html AGPL-3.0
 * @license https://afterlogic.com/products/common-licensing Afterlogic Software License
 * @copyright Copyright (c) 2019, Afterlogic Corp.
 *
 * @package Modules
 */
class Module extends \Aurora\System\Module\AbstractModule
{
	public $oApiChatManager = null;
	
	public function init() 
	{
		$this->oApiChatManager = new Manager($this);
		
		\Aurora\Modules\Core\Classes\User::extend(
			self::GetName(),
			[
				'EnableModule' => array('bool', true)
			]
		);		
	}
	
	/**
	 * Obtains list of module settings for authenticated user.
	 * 
	 * @return array
	 */
	public function GetSettings()
	{
		\Aurora\System\Api::checkUserRoleIsAtLeast(\Aurora\System\Enums\UserRole::Anonymous);
		
		$oUser = \Aurora\System\Api::getAuthenticatedUser();
		if (!empty($oUser) && $oUser->isNormalOrTenant())
		{
			return array(
				'EnableModule' => $oUser->{$this::GetName().'::EnableModule'}
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
		\Aurora\System\Api::checkUserRoleIsAtLeast(\Aurora\System\Enums\UserRole::NormalUser);
		
		$iUserId = \Aurora\System\Api::getAuthenticatedUserId();
		if (0 < $iUserId)
		{
			$oUser = \Aurora\Modules\Core\Module::Decorator()->GetUserUnchecked($iUserId);
			$oUser->{$this::GetName().'::EnableModule'} = $EnableModule;
			\Aurora\Modules\Core\Module::Decorator()->UpdateUserObject($oUser);
		}
		return true;
	}
	
	public function GetPostsCount()
	{
		\Aurora\System\Api::checkUserRoleIsAtLeast(\Aurora\System\Enums\UserRole::NormalUser);
		
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
		\Aurora\System\Api::checkUserRoleIsAtLeast(\Aurora\System\Enums\UserRole::NormalUser);
		
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
		\Aurora\System\Api::checkUserRoleIsAtLeast(\Aurora\System\Enums\UserRole::NormalUser);
		
		$iUserId = \Aurora\System\Api::getAuthenticatedUserId();
		$this->oApiChatManager->CreatePost($iUserId, $Text, $Date);
		return true;
	}	
}
