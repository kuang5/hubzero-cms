<?php
/**
 * @copyright	Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Joomla! udpate notification plugin
 *
 * @package		Joomla.Plugin
 * @subpackage	Quickicon.Joomla
 * @since		2.5
 */
class plgQuickiconExtensionupdate extends JPlugin
{
	/**
	 * Constructor
	 *
	 * @param       object  $subject The object to observe
	 * @param       array   $config  An array that holds the plugin configuration
	 *
	 * @since       2.5
	 */
	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
		$this->loadLanguage();
	}

	/**
	 * Returns an icon definition for an icon which looks for extensions updates
	 * via AJAX and displays a notification when such updates are found.
	 *
	 * @param  $context  The calling context
	 *
	 * @return array A list of icon definition associative arrays, consisting of the
	 *				 keys link, image, text and access.
	 *
	 * @since       2.5
	 */
	public function onGetIcons($context)
	{
		if ($context != $this->params->get('context', 'mod_quickicon') || !User::authorise('core.manage', 'com_installer'))
		{
			return;
		}

		$cur_template = JFactory::getApplication()->getTemplate();
		$ajax_url = Request::base().'index.php?option=com_installer&view=update&task=update.ajax';
		$script = "var plg_quickicon_extensionupdate_ajax_url = '$ajax_url';\n";
		$script .= 'var plg_quickicon_extensionupdate_text = {"UPTODATE" : "'.
			Lang::txt('PLG_QUICKICON_EXTENSIONUPDATE_UPTODATE', true).'", "UPDATEFOUND": "'.
			Lang::txt('PLG_QUICKICON_EXTENSIONUPDATE_UPDATEFOUND', true).'", "ERROR": "'.
			Lang::txt('PLG_QUICKICON_EXTENSIONUPDATE_ERROR', true)."\"};\n";
		$document = JFactory::getDocument();
		$document->addScriptDeclaration($script);
		$document->addScript(Request::base().'../media/plg_quickicon_extensionupdate/extensionupdatecheck.js');

		return array(array(
			'link' => 'index.php?option=com_installer&view=update',
			'image' => 'header/icon-48-extension.png',
			'text' => Lang::txt('PLG_QUICKICON_EXTENSIONUPDATE_CHECKING'),
			'id' => 'plg_quickicon_extensionupdate'
		));
	}
}
