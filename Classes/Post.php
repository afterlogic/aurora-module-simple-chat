<?php
/**
 * @copyright Copyright (c) 2017, Afterlogic Corp.
 * @license AGPL-3.0 or AfterLogic Software License
 *
 * This code is licensed under AGPLv3 license or AfterLogic Software License
 * if commercial version of the product was purchased.
 * For full statements of the licenses see LICENSE-AFTERLOGIC and LICENSE-AGPL3 files.
 */


namespace Aurora\Modules\SimpleChat\Classes;


/**
 * @property int $UserId
 * @property string $Text
 *
 * @package SimpleChat
 * @subpackage Classes
 */
class Post extends \Aurora\System\EAV\Entity
{
	protected $aStaticMap = array(
		'UserId'	=> array('int', 0),
		'Text'		=> array('text', ''),
		'Date'		=> array('datetime', '')
	);
}
