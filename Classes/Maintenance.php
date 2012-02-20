<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2012 Andy Grunwald <andygrunwald@gmail.com>
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *  A copy is found in the textfile GPL.txt and important notices to the license
 *  from the author is found in LICENSE.txt distributed with these scripts.
 *
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * ToolbarItem class to show a icon in the toolbar.
 *
 * @author Andy Grunwald <andygrunwald@gmail.com>
 * @package EXT:maintenance
 * @subpackage ToolbarItem
 */
class Tx_Maintenance_Maintenance implements backend_toolbarItem {

	/**
	 * Reference back to the backend object
	 *
	 * @var	TYPO3backend
	 */
	protected $backendReference = NULL;

	/**
	 * The extension key
	 *
	 * @var	string
	 */
	protected $extensionKey = 'maintenance';

	/**
	 * Constructor that receives a back reference to the backend
	 * and do some initial work (javascript inclusion, etc.).
	 *
	 * @param	TYPO3backend	TYPO3 backend object reference
	 */
	public function __construct(TYPO3backend &$backendReference = NULL) {
		$this->backendReference = $backendReference;
		$this->addResourcesToBackend();
		$this->includeLanguageFile();
	}

	/**
	 * Checks whether the user has access to this toolbar item.
	 * Only admin user has access to this toolbar item.
	 *
	 * @return	boolean		true if user has access, false if not
	 */
	public function checkAccess() {
		return $GLOBALS['BE_USER']->isAdmin();
	}

	/**
	 * Renders the toolbar item.
	 *
	 * @return	string	the toolbar item rendered as HTML string
	 */
	public function render() {
		$title = $GLOBALS['LANG']->getLL('toolbaritem-inactive', TRUE);
		$iconName = 'extensions-maintenance-toolbaritem-inactive';

		if($GLOBALS['TYPO3_CONF_VARS']['FE']['pageUnavailable_force']) {
			$title = $GLOBALS['LANG']->getLL('toolbaritem-active', TRUE);
			$iconName = 'extensions-maintenance-toolbaritem-active';
		}

			// toolbar item icon
		$toolbarItem[] = '<a href="#" class="toolbar-item">';
		$toolbarItem[] = t3lib_iconWorks::getSpriteIcon($iconName, array('title' => $title));
		$toolbarItem[] = '</a>';

		return implode(LF, $toolbarItem);
	}

	/**
	 * Returns additional attributes for the list item in the toolbar
	 *
	 * @return	string	list item HTML attibutes
	 */
	public function getAdditionalAttributes() {
		return ' id="tx-maintenance-toolbaritem"';
	}

	/**
	 * Loads the locallang file of the extension
	 *
	 * @return	void
	 */
	protected function includeLanguageFile() {
		$GLOBALS['LANG']->includeLLFile('EXT:' . $this->extensionKey . '/Resources/Private/Language/locallang.xml');
	}

	/**
	 * Adds the necessary JavaScript to the backend
	 *
	 * @return	void
	 */
	protected function addResourcesToBackend() {
		$publicPath = t3lib_extMgm::extRelPath($this->extensionKey) . 'Resources/Public/';

		$this->backendReference->addJavascriptFile($publicPath . 'JavaScript/ToolbarItem.js');
		$this->backendReference->addCssFile('ToolbarItem.css', $publicPath . 'CSS/ToolbarItem.css');
	}
}
?>