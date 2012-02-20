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
 * Configuration class for pageUnavailable_handling-Setting
 * E.g. [FE][pageUnavailable_handling] = http://domain.tld/maintenance.html
 *
 * @author Andy Grunwald <andygrunwald@gmail.com>
 * @package EXT:maintenance
 * @subpackage Configuration
 */
class Tx_Maintenance_Configuration_PageUnavailableHandling extends Tx_Maintenance_Configuration_AbstractConfiguration {

	/**
	 * Do the check of this configuration parameter.
	 * Is this valid? Is this okay?
	 * The result will be stored in $this->result.
	 *
	 * @return	void
	 */
	public function checkValue() {
		if (gettype($this->value) == 'boolean' || !strcmp($this->value, 1)) {
				// Standard TYPO3 error page ... not very cool :(
			$this->setResultForBoolean($this->value);

		} elseif (t3lib_div::isFirstPartOfStr($this->value, 'USER_FUNCTION:')) {
				// User function is configured ... cool :)
			$this->setResultForUserFunction($this->value);

		} elseif (t3lib_div::isFirstPartOfStr($this->value,'READFILE:')) {
				// File is configured ... cool or not cool? We will see
			$this->setResultForReadfile($this->value);

		} elseif (t3lib_div::isFirstPartOfStr($this->value,'REDIRECT:')) {
				// Redirect is configured ... cool :)
			$this->setResultForRedirect($this->value);

		} elseif (strlen($this->value)) {
				// URL / String is configured ... cool :)
			$this->setResultForString($this->value);

		} else {
				// Standard TYPO3 error page ... not very cool :(
			$this->setResultForNothingConfigured($this->value);
		}
	}

	/**
	 * Sets the result of validation check if there was only a "1" configured
	 *
	 * @param	string	$code	Configured value
	 * @return	void
	 */
	protected function setResultForBoolean($code) {
		$this->setResult(TRUE, self::WARNING, 20);
	}

	/**
	 * Sets the result of validation check if there was nothing configured
	 *
	 * @param	string	$code	Configured value
	 * @return	void
	 */
	protected function setResultForNothingConfigured($code) {
		$this->setResult(TRUE, self::WARNING, 120);
	}

	/**
	 * Sets the result of validation check if there was only a string without a prefix configured.
	 * E.g. a URL or something like this
	 *
	 * @param	string	$code	Configured value
	 * @return	void
	 */
	protected function setResultForString($code) {
		$this->setResult(TRUE, self::OK, 100);
	}

	/**
	 * Sets the result of validation check if there was a user function configured
	 *
	 * @param	string	$code	Configured value
	 * @return	void
	 */
	protected function setResultForUserFunction($code) {
		$funcRef = trim(substr($code, 14));
		if($funcRef) {

				// @todo Check if the configured function is available / callable
			$this->setResult(TRUE, self::OK, 40);
		}
	}

	/**
	 * Sets the result of validation check if there was a string with "READFILE"-prefix configured
	 *
	 * @param	string	$code	Configured value
	 * @return	void
	 */
	protected function setResultForReadfile($code) {
		$readFile = t3lib_div::getFileAbsFileName(trim(substr($code, 9)));
		$readFileWithPathSite = str_replace(PATH_site, '', $readFile);
		$pathInfo = pathinfo($readFileWithPathSite);

		if (@is_file($readFile))	{
				// File is configured ... cool :)
			$this->setResult(TRUE, self::OK, 60);

		} else {
				// File not available ... not very cool :(
			$path = (!$pathInfo['dirname']) ? '/': $pathInfo['dirname'];
			$this->setResult(FALSE, self::ERROR, -20);
		}
	}

	/**
	 * Sets the result of validation check if there was a string with "REDIRECT"-prefix configured
	 *
	 * @param	string	$code	Configured value
	 * @return	void
	 */
	protected function setResultForRedirect($code) {
			// @todo check URL ... is it valid? etc.
		$url = trim(substr($code, 9));
		$this->setResult(TRUE, self::OK, 80);
	}
}
?>