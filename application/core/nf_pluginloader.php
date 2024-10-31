<?php

class NfPluginLoader extends Model{
	
	function nf_activate() {
		$this->nf_activatePlugin();
        $nf_file = NF_FCPATH . 'js/manifest.json';
        $nf_current = file_get_contents($nf_file);
        file_put_contents(ABSPATH.'manifest.json', $nf_current);
        $nf_file2 = NF_FCPATH . 'js/mainsw.js';
        $nf_current2 = file_get_contents($nf_file2);
        file_put_contents(ABSPATH.'mainsw.js', $nf_current2);
	}

	function nf_deactivate() {
		$this->nf_deactivatePlugin();
	}

}

?>