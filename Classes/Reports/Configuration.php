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
 * Report class for configuration settings
 *
 * @author Andy Grunwald <andygrunwald@gmail.com>
 * @package EXT:maintenance
 * @subpackage Reports
 */
class Tx_Maintenance_Reports_Configuration implements tx_reports_StatusProvider {

	/**
	 * Determines the status of configuration settings for EXT:maintenance
	 *
	 * @see typo3/sysext/reports/interfaces/tx_reports_StatusProvider::getStatus()
	 * @return	array	List of statuses
	 */
	public function getStatus() {
		$localLangPrefix = 'LLL:EXT:maintenance/Resources/Private/Language/locallang.xml:report.configuration.';
		$classPrefix = $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['maintenance']['classPrefix'];
		$configArray = $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['maintenance']['config'];

		$statuses = array();
		foreach($configArray as $config) {
			$className = $classPrefix . $config['class'];
			$configurationObj = t3lib_div::makeInstance($className, $config['value'], $config['options']);
			$configurationObj->checkValue();
			$result = $configurationObj->getResult();

				// Get title
			$localLangName = $localLangPrefix . lcfirst($config['class']) . '.title';
			$title = $GLOBALS['LANG']->sL($localLangName);

				// Get message
			$localLangName = $localLangPrefix . lcfirst($config['class']) . '.result.' . $result['code'];
			$message = $GLOBALS['LANG']->sL($localLangName);

			$statuses[$config['class']] = t3lib_div::makeInstance('tx_reports_reports_status_Status',
				htmlentities($title, ENT_QUOTES),
				htmlentities($config['value'], ENT_QUOTES),
				$message,
				$result['severity']
			);
		}

		return $statuses;
	}
}
?>