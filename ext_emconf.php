<?php

########################################################################
# Extension Manager/Repository config file for ext "maintenance".
#
# Auto generated 16-02-2012 21:31
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
	'dependencies' => 'cms',
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
			'php' => '5.0.0-5.3.99',
			'typo3' => '4.5.0-4.7.99',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:5:{s:9:"ChangeLog";s:4:"600a";s:10:"README.txt";s:4:"ee2d";s:12:"ext_icon.gif";s:4:"1bdc";s:19:"doc/wizard_form.dat";s:4:"0847";s:20:"doc/wizard_form.html";s:4:"14b5";}',
);

?>