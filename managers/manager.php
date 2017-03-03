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
 * 
 * @package Modules
 */

/**
 * CApiSimpleChatManager class summary
 *
 * @package SimpleChat
 */

class CApiSimpleChatManager extends \Aurora\System\AbstractManager
{
	/**
	 * @var CApiEavManager
	 */
	public $oEavManager = null;
	
	/**
	 * 
	 * @param \Aurora\System\GlobalManager &$oManager
	 * @param string $sForcedStorage
	 * @param \Aurora\System\Module\AbstractModule $oModule
	 */
	public function __construct(\Aurora\System\GlobalManager &$oManager, $sForcedStorage = '', \Aurora\System\Module\AbstractModule $oModule = null)
	{
		parent::__construct('', $oManager, $oModule);
		
		$this->oEavManager = \Aurora\System\Api::GetSystemManager('eav', 'db');

		$this->incClass('post');
	}
	
	/**
	 * Obtains count of all posts.
	 * 
	 * @return int
	 */
	public function GetPostsCount()
	{
		return $this->oEavManager->getEntitiesCount('CSimpleChatPost', array());
	}
	
	/**
	 * Obtains posts of Simple Chat Module.
	 * 
	 * @param int $Offset uses for obtaining a partial list.
	 * @param int $Limit uses for obtaining a partial list.
	 * @return array
	 */
	public function GetPosts($Offset = 0, $Limit = 0)
	{
		$aResult = array();
		try
		{
			$aResults = $this->oEavManager->getEntities(
				'CSimpleChatPost', 
				array(
					'UserId', 'Text', 'Date'
				),
				$Offset,
				$Limit,
				array()
			);
			
			$aUsers = array();

			if (is_array($aResults))
			{
				$oCoreDecorator = \Aurora\System\Api::GetModuleDecorator('Core');
				foreach($aResults as $oItem)
				{
					if (!isset($aUsers[$oItem->UserId]))
					{
						$oUser = $oCoreDecorator->GetUser($oItem->UserId);
						if ($oUser)
						{
							$aUsers[$oItem->UserId] = $oUser->PublicId;
						}
					}
					if (isset($aUsers[$oItem->UserId]))
					{
						$aResult[] = array(
							'userId' => $oItem->UserId,
							'name' => $aUsers[$oItem->UserId],
							'text' => $oItem->Text,
							'date' => $oItem->Date
						);
					}
				}
			}
		}
		catch (\Aurora\System\Exceptions\BaseException $oException)
		{
			$aResult = false;
			$this->setLastException($oException);
		}
		return $aResult;
	}
	
	/**
	 * Creates a new post for user.
	 * 
	 * @param int $iUserId id of user that creates the new post.
	 * @param string $sText text of the new post.
	 * @param string $sDate date of the new post.
	 * @return boolean
	 */
	public function CreatePost($iUserId, $sText, $sDate)
	{
		$bResult = true;
		try
		{
			$oNewPost = new \CSimpleChatPost($this->GetModule()->GetName());
			$oNewPost->UserId = $iUserId;
			$oNewPost->Text = $sText;
			$oNewPost->Date = $sDate;
			if (!$this->oEavManager->saveEntity($oNewPost))
			{
				throw new \Aurora\System\Exceptions\ManagerException(Errs::UsersManager_UserCreateFailed);
			}
		}
		catch (\Aurora\System\Exceptions\BaseException $oException)
		{
			$bResult = false;
			$this->setLastException($oException);
		}
		return $bResult;
	}
}
