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
 * ExtDirect AJAX class for ToolbarItem functionality.
 *
 * @author Andy Grunwald <andygrunwald@gmail.com>
 * @package EXT:maintenance
 * @subpackage ExtDirect
 */
class Tx_Maintenance_ExtDirect_ToolbarItem {

	/**
	 * Extension key
	 *
	 * @var string
	 */
	protected $extensionKey = 'maintenance';

	/**
	 * Namespace for t3lib_registry
	 *
	 * @var string
	 */
	protected $registryNamespace = 'tx_maintenance';

	/**
	 * Ajax dispatcher method.
	 * Switches the mode from normal to maintenance and back.
	 *
	 * @param	array	$parameter	Parameter from ExtDirect call
	 * @return	array				Result with detailled information about the maintainence mode switch
	 */
	public function switchMaintenanceMode($parameter) {
		$localLangPrefix = 'LLL:EXT:maintenance/Resources/Private/Language/locallang.xml:toolbaritem.extDirect.result.';

		$errors = $this->checkRequirementsForModeSwitch();
		if(count($errors) > 0) {
			return array(
				'result' => FALSE,
				'code' => -100,
				'options' => array(
					'title' => $GLOBALS['LANG']->sL($localLangPrefix . '-100.title'),
					'message' => $GLOBALS['LANG']->sL($localLangPrefix . '-100.message'),
					'errors' => $errors,
				),
			);
		}

		$pageUnavailableForce = $GLOBALS['TYPO3_CONF_VARS']['FE']['pageUnavailable_force'];
		$linesToWrite = array(
			'FE' => array(
				'pageUnavailable_force' => (($pageUnavailableForce) ? 0: 1),
			)
		);
		$linesToWrite = $this->mergeAdminOnlySettingIfNecessary($linesToWrite, $pageUnavailableForce);
		$this->writeLinesToLocalConf($linesToWrite);

		if($linesToWrite['FE']['pageUnavailable_force'] === 1) {
			$code = 100;

		} else {
			$code = 200;
		}

		return array(
			'result' => TRUE,
			'code' => $code,
			'options' => array(
				'title' => $GLOBALS['LANG']->sL($localLangPrefix . $code . '.title'),
				'message' => $GLOBALS['LANG']->sL($localLangPrefix . $code . '.message'),
				'pageUnavailableForce' => $linesToWrite['FE']['pageUnavailable_force']
			),
		);

	}

	/**
	 * Checks the requirements to switch the maintenance mode.
	 * If one check failed, the mode won`t be switched.
	 *
	 * @return array
	 */
	protected function checkRequirementsForModeSwitch() {
		$errors = array();
		$classPrefix = $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['maintenance']['classPrefix'];
		$configArray = $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['maintenance']['config'];

		foreach($configArray as $config) {
			$className = $classPrefix . $config['class'];
			$configurationObj = t3lib_div::makeInstance($className, $config['value'], $config['options']);
			$configurationObj->checkValue();
			$result = $configurationObj->getResult();

			if($result['result'] === FALSE) {
				$errors[] = $config['class'];
			}
		}

		return $errors;
	}

	/**
	 * Merge the admin only setting into lines to write to localconf.php if necessary.
	 * This is only necessary if this setting is configured in extension manager.
	 *
	 * @param	array	$linesToWrite			Lines of localconf.php
	 * @param	boolean	$pageUnavailableForce	TYPO3_CONF_VARS['FE']['pageUnavailable_force'] setting
	 * @return	array
	 */
	protected function mergeAdminOnlySettingIfNecessary(array $linesToWrite, $pageUnavailableForce) {
		$installToolSetting = trim($GLOBALS['TYPO3_CONF_VARS']['BE']['adminOnly']);
		$extensionManagerSetting = trim($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$this->extensionKey]['adminOnly']);

			// If extension manager setting is not set OR
			// values in install tool and extension manager are equal, nothing to do, exit here
		if(!$extensionManagerSetting || (!strcmp($installToolSetting, $extensionManagerSetting) && !$pageUnavailableForce)) {
			return $linesToWrite;
		}

		$registry = t3lib_div::makeInstance('t3lib_Registry');
		$registryKey = 'adminOnlyInstallTool';

			// If pageUnavailable_force is active, restore adminOnly value
		if($pageUnavailableForce) {

			$adminOnlyValue = $registry->get($this->registryNamespace, $registryKey, 0);
			$linesToWrite['BE']['adminOnly'] = $adminOnlyValue;

			// If pageUnavailable_force is inactive, set configured adminOnly value
		} else {
			$registry->set($this->registryNamespace, $registryKey, $installToolSetting);
			$linesToWrite['BE']['adminOnly'] = $extensionManagerSetting;
		}

		return $linesToWrite;
	}

	/**
	 * Writes the new settings for maintenance mode to localconf.
	 *
	 * @param	array	$linesToWrite	Array of lines to write back to localconf.php. Possibly
	 * @return	mixed					If $inlines is not an array it will return an array with the lines from localconf.php. Otherwise it will return a status string, either "continue" (updated) or "nochange" (not updated)
	 */
	protected function writeLinesToLocalConf(array $linesToWrite) {
		$installObj = t3lib_div::makeInstance('t3lib_install');
		$installObj->allowUpdateLocalConf = TRUE;
		$installObj->updateIdentity = 'EXT:' . $this->extensionKey;
		$localconfLines = $installObj->writeToLocalconf_control();

		foreach($linesToWrite as $scope => $settings) {
			foreach($settings as $setting => $value) {
				if (strcmp($GLOBALS['TYPO3_CONF_VARS'][$scope][$setting], $value)) {
					$installObj->setValueInLocalconfFile($localconfLines, '$TYPO3_CONF_VARS[\'' . $scope . '\'][\'' . $setting . '\']', $value);
				}
			}
		}

		return $installObj->writeToLocalconf_control($localconfLines);
	}
}
?>