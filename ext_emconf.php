<?php

########################################################################
# Extension Manager/Repository config file for ext "maintenance".
#
# Auto generated 21-02-2012 16:14
#
# Manual updates:
# Only the data in the array - everything else is removed by next
# writing. "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Maintenance',
	'description' => 'Switch your TYPO3 installation into maintenance mode with only one click!
In maintenance mode you can easily deploy new features, make bigger update operations or update the whole system without active user on the system.',
	'category' => 'misc',
	'author' => 'Andy Grunwald',
	'author_email' => 'andygrunwald@gmail.com',
	'shy' => '',
	'dependencies' => '',
	'conflicts' => '',
	'priority' => '',
	'module' => '',
	'state' => 'stable',
	'internal' => '',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 0,
	'lockType' => '',
	'author_company' => '',
	'version' => '1.0.0',
	'constraints' => array(
		'depends' => array(
			'php' => '5.0.0-5.4.99',
			'typo3' => '4.5.0-4.7.99',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:21:{s:9:"ChangeLog";s:4:"9437";s:10:"README.txt";s:4:"1c49";s:16:"ext_autoload.php";s:4:"3e74";s:21:"ext_conf_template.txt";s:4:"1705";s:12:"ext_icon.gif";s:4:"9fc2";s:14:"ext_tables.php";s:4:"5b0f";s:23:"Classes/Maintenance.php";s:4:"5b78";s:47:"Classes/Configuration/AbstractConfiguration.php";s:4:"03d4";s:35:"Classes/Configuration/DevIPMask.php";s:4:"be08";s:49:"Classes/Configuration/PageUnavailableHandling.php";s:4:"967d";s:59:"Classes/Configuration/PageUnavailableHandlingStatheader.php";s:4:"6883";s:33:"Classes/ExtDirect/ToolbarItem.php";s:4:"226e";s:33:"Classes/Reports/Configuration.php";s:4:"f230";s:40:"Resources/Private/Language/locallang.xml";s:4:"9775";s:42:"Resources/Private/ToolbarItem/register.php";s:4:"7925";s:36:"Resources/Public/CSS/ToolbarItem.css";s:4:"cd7b";s:46:"Resources/Public/Images/toolbaritem-active.png";s:4:"1bc2";s:48:"Resources/Public/Images/toolbaritem-inactive.png";s:4:"cbb6";s:42:"Resources/Public/JavaScript/ToolbarItem.js";s:4:"ba13";s:14:"doc/manual.pdf";s:4:"f439";s:14:"doc/manual.sxw";s:4:"c0fe";}',
	'suggests' => array(
	),
);

?>