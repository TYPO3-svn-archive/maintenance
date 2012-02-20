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
 * Abstract configuration class, to provide basic functionality.
 * All configuration classes have to extend this abstract class.
 *
 * @author Andy Grunwald <andygrunwald@gmail.com>
 * @package EXT:maintenance
 * @subpackage Configuration
 */
abstract class Tx_Maintenance_Configuration_AbstractConfiguration {

	/**
	 * Value of configuration setting
	 *
	 * @var mixed
	 */
	protected $value = NULL;

	/**
	 * Options for configuration check
	 *
	 * @var array
	 */
	protected $options = array();

	/**
	 * Result array
	 * Contains informations about the validation check
	 *
	 * @var array
	 */
	protected $result = array();

	/**
	 * Severity: OK
	 *
	 * @see tx_reports_reports_status_Status
	 * @var integer
	 */
	const OK = 0;

	/**
	 * Severity: WARNING
	 *
	 * @see tx_reports_reports_status_Status
	 * @var integer
	 */
	const WARNING = 1;

	/**
	 * Severity: ERROR
	 *
	 * @see tx_reports_reports_status_Status
	 * @var integer
	 */
	const ERROR = 2;

	/**
	 * @param	mixed	$value		Value of configuration setting
	 * @param	array	$options	Options for configuration check
	 */
	public function __construct($value, array $options = array()) {
		$this->value = $value;
		$this->options = $options;
		$this->result = array(
			'result' => FALSE,
			'severity' => self::ERROR,
			'code' => 0,
			'data' => array(),
		);
	}

	/**
	 * Sets the result of validation check
	 *
	 * @param	boolean		$result		TRUE if the check was valid, FALSE otherwise
	 * @param	integer		$severity	OK if the check was valid, WARNING or ERROR otherwise (@see class constants)
	 * @param	integer		$code		Code to determine the specific error / message. $code > 0 = Success, $code < 0 = Error
	 * @return	void
	 */
	protected function setResult($result, $severity, $code = 0, $data = array()) {
		$this->result = array(
			'result' => (($result) ? TRUE: FALSE),
			'severity' => t3lib_div::intInRange($severity, 0, 2, 0),
			'code' => (int) $code,
			'data' => (array) $data,
		);
	}

	/**
	 * Returns the result of validation check
	 *
	 * @return array
	 */
	public function getResult() {
		return $this->result;
	}

	/**
	 * Do the check of this configuration parameter.
	 * Is this valid? Is this okay?
	 * The result will be stored in $this->result.
	 *
	 * @abstract
	 * @return	void
	 */
	abstract public function checkValue();
}
?>