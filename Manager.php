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
 * @package SimpleChat
 */
class Manager extends \Aurora\System\Managers\AbstractManager
{
	/**
	 * @var \Aurora\System\Managers\Eav
	 */
	public $oEavManager = null;
	
	/**
	 * 
	 * @param \Aurora\System\Module\AbstractModule $oModule
	 */
	public function __construct(\Aurora\System\Module\AbstractModule $oModule = null)
	{
		parent::__construct($oModule);
		
		$this->oEavManager = \Aurora\System\Managers\Eav::getInstance();
	}
	
	/**
	 * Obtains count of all posts.
	 * 
	 * @return int
	 */
	public function GetPostsCount()
	{
		return $this->oEavManager->getEntitiesCount(
			Classes\Post::class, array()
		);
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
				Classes\Post::class,
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
				foreach($aResults as $oItem)
				{
					if (!isset($aUsers[$oItem->UserId]))
					{
						$oUser = \Aurora\Modules\Core\Module::Decorator()->GetUserUnchecked($oItem->UserId);
						if ($oUser)
						{
							$aUsers[$oItem->UserId] = $oUser->PublicId;
						}
						else
						{
							$aUsers[$oItem->UserId] = '';
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
			$oNewPost = new Classes\Post(Module::GetName());
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
