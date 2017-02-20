<?php
/**
 * @copyright Copyright (c) 2016, Afterlogic Corp.
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
 * @property int $UserId
 * @property string $Text
 *
 * @package SimpleChat
 * @subpackage Classes
 */
class CSimpleChatPost extends AEntity
{
	public function __construct($sModule)
	{
		parent::__construct(get_class($this), $sModule);

		$this->setStaticMap(array(
			'UserId'	=> array('int', 0),
			'Text'		=> array('text', ''),
			'Date'		=> array('datetime', '')
		));
	}
	
	public static function createInstance($sModule = 'SimpleChat')
	{
		return new CSimpleChatPost($sModule);
	}
}
