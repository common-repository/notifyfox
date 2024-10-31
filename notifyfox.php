<?php

	/*
		Plugin Name: Notifyfox
		Description: Notifyfox.
		Version: 1.0
		Author: Notifyfox
		Author URI: https://notifyfox.com
	*/

	$nf_application_path = 'application';
	$nf_plugin = 'notifyfox';
	define('NF_FCPATH', dirname(__FILE__).DIRECTORY_SEPARATOR);
	define('NF_APP', dirname(__FILE__) . '/'. $nf_application_path . DIRECTORY_SEPARATOR);
	define('NF_URL',plugins_url().'/'.strtolower($nf_plugin).'/');
	define('NF_TDOMAIN', strtolower($nf_plugin));
	define('NF_NODE_URL', 'https://app.notifyfox.com');
	define('NF_CDN_PATH', 'https://cdn.notifyfox.com');
	require_once(NF_APP.'core/Controller.php');
	new NF_Base_Controller();
	 register_activation_hook( __FILE__, 'nf_activation');
	register_deactivation_hook( __FILE__, 'nf_deactivation' );
	function nf_activation(){
		require_once(NF_APP.'core/nf_pluginloader.php');
		$loader=new NfPluginLoader();
		$loader->nf_activate();
	}
	function nf_deactivation(){
		require_once(NF_APP.'core/nf_pluginloader.php');
		$loader=new NfPluginLoader();
		$loader->nf_deactivate();
	}
?>
