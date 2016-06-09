<?php

/* -AFTERLOGIC LICENSE HEADER- */

/**
 * CApiSimpleChatMainManager class summary
 *
 * @package SimpleChat
 */

class CApiSimpleChatMainManager extends AApiManager
{
	/**
	 * @var CApiEavManager
	 */
	public $oEavManager = null;
	
	/**
	 * 
	 * @param CApiGlobalManager &$oManager
	 * @param string $sForcedStorage
	 * @param AApiModule $oModule
	 */
	public function __construct(CApiGlobalManager &$oManager, $sForcedStorage = '', AApiModule $oModule = null)
	{
		parent::__construct('main', $oManager, $oModule);
		
		$this->oEavManager = \CApi::GetCoreManager('eav', 'db');

		$this->incClass('post');
	}
	
	/**
	 * Obtains count of all posts.
	 * 
	 * @return int
	 */
	public function GetPostsCount()
	{
		return $this->oEavManager->getObjectsCount('CSimpleChatPost', array());
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
			$aResults = $this->oEavManager->getObjects(
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
				$oCoreDecorator = \CApi::GetModuleDecorator('Core');
				foreach($aResults as $oItem)
				{
					if (!isset($aUsers[$oItem->UserId]))
					{
						$oUser = $oCoreDecorator->GetUser($oItem->UserId);
						if ($oUser)
						{
							$aUsers[$oItem->UserId] = $oUser->Name;
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
		catch (CApiBaseException $oException)
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
			if (!$this->oEavManager->saveObject($oNewPost))
			{
				throw new CApiManagerException(Errs::UsersManager_UserCreateFailed);
			}
		}
		catch (CApiBaseException $oException)
		{
			$bResult = false;
			$this->setLastException($oException);
		}
		return $bResult;
	}
}
