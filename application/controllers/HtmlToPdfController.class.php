<?php

if (!defined('HTMLDOC_COMMAND')) define('HTMLDOC_COMMAND', 'htmldoc');
if (!defined('HTMLDOC_ARGS')) define('HTMLDOC_ARGS', "--webpage -f '{dest}' '{src}'");

class HtmlToPdfController extends ApplicationController {
	
	function export() {
		$this->setLayout("html");
		
		$id = array_var($_GET, 'id', 0);
		$file = ProjectFiles::findById($id);
		if (!$file instanceof ProjectFile) {
			tpl_assign('error_title', lang('file dnx'));
			return;
		}
		
		$genid = gen_id();
		$src = ROOT . "/tmp/$genid.html";
		$dest = ROOT . "/tmp/$genid.pdf";
		file_put_contents($src, $file->getFileContent());
		@unlink($dest);

		$args = str_replace(array('{dest}', '{src}'), array($dest, $src), HTMLDOC_ARGS);
		$output = array();
		exec(HTMLDOC_COMMAND . " $args", $output);
		
		if (!is_file($dest)) {
			tpl_assign('error_title', lang("error exporting to pdf"));
			tpl_assign('error_body', join("<br/>", $output));
			return;
		}

		download_file($dest, 'application/pdf', $file->getFilename() . '.pdf');
		@unlink($dest);
		@unlink($src);
		die();
	}
}
?>