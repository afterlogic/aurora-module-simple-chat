<?php

class SimpleChatModule extends AApiModule
{
	public $oApiChatManager = null;
	
	public function init() 
	{
		$this->oApiChatManager = $this->GetManager();
		
		$this->setObjectMap('CUser', array(
				'EnableModule' => array('bool', true)
			)
		);
	}
	
	/**
	 * Obtains settings of the Simple Chat Module.
	 * 
	 * @return array
	 */
	public function GetAppData()
	{
		$oUser = \CApi::getAuthenticatedUser();
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
		$iUserId = \CApi::getAuthenticatedUserId();
		if (0 < $iUserId)
		{
			$oCoreDecorator = \CApi::GetModuleDecorator('Core');
			$oUser = $oCoreDecorator->GetUser($iUserId);
			$oUser->{$this->GetName().'::EnableModule'} = $EnableModule;
			$oCoreDecorator->UpdateUserObject($oUser);
		}
		return true;
	}
	
	public function GetPostsCount()
	{
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
		$iUserId = \CApi::getAuthenticatedUserId();
		$this->oApiChatManager->CreatePost($iUserId, $Text, $Date);
		return true;
	}	
}
