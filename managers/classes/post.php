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
 * @property int $UserId
 * @property string $Text
 *
 * @package SimpleChat
 * @subpackage Classes
 */
class CSimpleChatPost extends \Aurora\System\EAV\Entity
{
	protected $aStaticMap = array(
		'UserId'	=> array('int', 0),
		'Text'		=> array('text', ''),
		'Date'		=> array('datetime', '')
	);
}
