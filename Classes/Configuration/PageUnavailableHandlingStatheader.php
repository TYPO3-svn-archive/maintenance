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
 * Configuration class for pageUnavailable_handling_statheader-Setting
 * E.g. [FE][pageUnavailable_handling_statheader] = HTTP/1.0 503 Service Temporarily Unavailable
 *
 * @author Andy Grunwald <andygrunwald@gmail.com>
 * @package EXT:maintenance
 * @subpackage Configuration
 */
class Tx_Maintenance_Configuration_PageUnavailableHandlingStatheader extends Tx_Maintenance_Configuration_AbstractConfiguration {

	/**
	 * Do the check of this configuration parameter.
	 * Is this valid? Is this okay?
	 * The result will be stored in $this->result.
	 *
	 * @return	void
	 */
	public function checkValue() {
		if($this->value) {
			if($this->isHeader503Configured($this->value) === FALSE) {
				$code = 20;
			} else {
				$code = 40;
			}

			$this->setResult(TRUE, self::OK, $code);
		} else {
			$this->setResult(TRUE, self::WARNING, 60);
		}
	}

	/**
	 * Checks if there was the HTTP status code 503 as header configured.
	 * If yes, TRUE will be returned, FALSE otherwise
	 *
	 * @param	mixed	$header		Configured setting
	 * @return	bool
	 */
	protected function isHeader503Configured($header) {
		$result = FALSE;

		$headerArr = preg_split('/\r|\n/', $this->value, -1, PREG_SPLIT_NO_EMPTY);
		foreach($headerArr as $singleHeader) {
			if(strstr($singleHeader, ' 503 ')) {
				$result = TRUE;
				break;
			}
		}

		return $result;
	}
}
?>