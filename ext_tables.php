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

if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

if(TYPO3_MODE == 'BE') {
	$maintenancePath = t3lib_extMgm::extPath($_EXTKEY);
	$maintenanceRelPath = t3lib_extMgm::extRelPath($_EXTKEY);

		// Register toolbar item
	$GLOBALS['TYPO3_CONF_VARS']['typo3/backend.php']['additionalBackendItems'][] = $maintenancePath . 'Resources/Private/ToolbarItem/register.php';

		// Register reports check
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['reports']['tx_reports']['status']['providers']['Maintenance'][] = 'Tx_Maintenance_Reports_Configuration';

		// Register AJAX (ExtDirect) calls
	t3lib_extMgm::registerExtDirectComponent(
		'TYPO3.Maintenance.ToolbarItem',
		t3lib_extMgm::extPath($_EXTKEY) . 'Classes/ExtDirect/ToolbarItem.php:Tx_Maintenance_ExtDirect_ToolbarItem',
		NULL,
		'admin'
	);

		// Global config
	$GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$_EXTKEY] = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$_EXTKEY]);
	$GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$_EXTKEY]['classPrefix'] = 'Tx_Maintenance_Configuration_';
	$GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$_EXTKEY]['config'] = array(
		array(
			'class' => 'DevIPMask',
			'value' => $GLOBALS['TYPO3_CONF_VARS']['SYS']['devIPmask'],
			'options' => array('ip' => t3lib_div::getIndpEnv('REMOTE_ADDR')),
		),
		array(
			'class' => 'PageUnavailableHandling',
			'value' => $GLOBALS['TYPO3_CONF_VARS']['FE']['pageUnavailable_handling'],
			'options' => array(),
		),
		array(
			'class' => 'PageUnavailableHandlingStatheader',
			'value' => $GLOBALS['TYPO3_CONF_VARS']['FE']['pageUnavailable_handling_statheader'],
			'options' => array(),
		),
	);

		// Register the sprite icons
	$icons = array(
		'toolbaritem-active' => $maintenanceRelPath . 'Resources/Public/Images/toolbaritem-active.png',
		'toolbaritem-inactive' => $maintenanceRelPath . 'Resources/Public/Images/toolbaritem-inactive.png',
	);
	t3lib_SpriteManager::addSingleIcons($icons, $_EXTKEY);
}
?>