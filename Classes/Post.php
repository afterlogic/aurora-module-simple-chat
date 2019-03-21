<?php
/**
 * This code is licensed under AGPLv3 license or AfterLogic Software License
 * if commercial version of the product was purchased.
 * For full statements of the licenses see LICENSE-AFTERLOGIC and LICENSE-AGPL3 files.
 */

namespace Aurora\Modules\SimpleChat\Classes;

/**
 * @license https://www.gnu.org/licenses/agpl-3.0.html AGPL-3.0
 * @license https://afterlogic.com/products/common-licensing AfterLogic Software License
 * @copyright Copyright (c) 2019, Afterlogic Corp.
 *
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
