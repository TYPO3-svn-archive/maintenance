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

Ext.ns('TYPO3', 'TYPO3.configuration');

/**
 * ToolbarItem javascript class to make the icon work as expected.
 * For example start the ExtDirect calls to set localconf settings, etc.
 *
 * @author Andy Grunwald <andygrunwald@gmail.com>
 * @package EXT:maintenance
 * @subpackage ToolbarItem
 */
var Tx_Maintenance_ToolbarItem = Class.create({
	iconStorage: {
		spinner: (new Element('span').addClassName('spinner')),
		active: 't3-icon-maintenance-toolbaritem-active',
		inactive: 't3-icon-maintenance-toolbaritem-inactive',
		oldIcon: null
	},
	toolbarItemIcon: null,

	/**
	 * Registers for resize event listener and executes on DOM ready.
	 * Creates an onClick event on the maintenance icon to switch the maintenance mode.
	 *
	 * @return	void
	 */
	initialize: function() {
		Ext.onReady(function() {
			this.toolbarItemIcon = $$('#tx-maintenance-toolbaritem .toolbar-item span.t3-icon')[0];
			Event.observe($$('#tx-maintenance-toolbaritem .toolbar-item')[0], 'click', this.switchMaintenanceMode.bind(this));
		}, this);
	},

	/**
	 * Toggles the maintenance mode.
	 * Send the ExtDirect-Request to switch maintenance mode.
	 * If the request is finished, a flashmessage will occur.
	 *
	 * @return	void
	 */
	switchMaintenanceMode: function(event) {
			// Set spinner icon
		this.iconStorage.oldIcon = this.toolbarItemIcon.replace(this.iconStorage.spinner);

			// Switch maintenance mode via ExtDirect call
		TYPO3.Maintenance.ToolbarItem.switchMaintenanceMode({}, (function(response) {

				// Flash message
			var severity = null;
			if(response.result === true) {
				severity = TYPO3.Severity.ok;

			} else {
				severity = TYPO3.Severity.error;
			}
			TYPO3.Flashmessage.display(severity, response.options.title, response.options.message, 10);

				// Set correct icon
			if(response.options.pageUnavailableForce == 1) {
				this.iconStorage.oldIcon.addClassName(this.iconStorage.active).removeClassName(this.iconStorage.inactive);
			} else {
				this.iconStorage.oldIcon.addClassName(this.iconStorage.inactive).removeClassName(this.iconStorage.active);
			}
			this.iconStorage.spinner.replace(this.iconStorage.oldIcon);
		}).bind(this));

		if(event) {
			Event.stop(event);
		}
	}
});

var TYPO3BackendMaintenanceToolbarItem = new Tx_Maintenance_ToolbarItem();