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
 * Configuration class for devIPmask-Setting
 * E.g. [SYS][devIPmask] = 127.0.0.1,::1,XXX.XXX.XXX.XXX
 *
 * @author Andy Grunwald <andygrunwald@gmail.com>
 * @package EXT:maintenance
 * @subpackage Configuration
 */
class Tx_Maintenance_Configuration_DevIPMask extends Tx_Maintenance_Configuration_AbstractConfiguration {

	/**
	 * Do the check of this configuration parameter.
	 * Is this valid? Is this okay?
	 * The result will be stored in $this->result.
	 *
	 * @return	void
	 */
	public function checkValue() {
			// If ip will match the devIpMask
		if(t3lib_div::cmpIP($this->options['ip'], $GLOBALS['TYPO3_CONF_VARS']['SYS']['devIPmask'])) {
			$this->setResult(TRUE, self::OK, 20, $this->options);

			// If there is no devIPmask configured
		} else if(!$GLOBALS['TYPO3_CONF_VARS']['SYS']['devIPmask']) {
			$this->setResult(TRUE, self::WARNING, 40, $this->options);

			// If there is a devIPmask configured but not matched
		} else {
			$this->setResult(TRUE, self::WARNING, 60, $this->options);
		}
	}
}
?>