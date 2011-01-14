<?php

class FaxController extends ApplicationController {

	function __construct() {
		parent::__construct();
		prepare_company_website_controller($this, 'website');
	}

	function init() {
		ajx_current("panel", "fax", null, null, true);
		ajx_replace(true);
	}

	function index() {
		if (isset($error))
			tpl_assign('error', $error);
		tpl_assign('message', 'FAX');
	}

	function send() {
		$file_ids = array_var($_GET, 'ids');
		$phonenumber = array_var($_GET, 'phonenumber');

		if (!empty($file_ids) && is_numeric($phonenumber)) {

			foreach (json_decode($file_ids) as $file_id) {

				$file_content = ProjectFiles::findById($file_id)->getFileContent();
				$file_path = ROOT . "/tmp/$file_id";

				file_put_contents($file_path, $file_content);

				$files[] = $file_path;
			}

			$this->sendfax($phonenumber, implode(' ', $files));

		} else {
			flash_error('faxsend: input parameters failure!');
		}

		if ($debug) {
			tpl_assign('debug', $command);
		} else {
			ajx_current("empty");
		}
	}

	function sendfax($phonenumber, $files) {
		$command = "sendfax -n -d $phonenumber $files";
		exec($command, $command_output, $command_returnvalue);

		if ($command_returnvalue == 0) {
			flash_success('faxsend: success!');
			return true;
		} else {
			flash_error('faxsend: failure!');
			return false;
		}
	}

	function contact() {
		$file_id = array_var($_GET, 'id');
		$objects = array_var($_GET, 'objects');

		$file = ProjectFiles::findById($file_id);
		$file_path = ROOT . "/tmp/$file_id";
		file_put_contents($file_path, $file->getFileContent());

		$object = split(":", $objects);
		$phonenumber = $object[0]::findById($object[1])->getEmail();
		switch ($manager = $object[0]) {
			case 'Companies':
				$phonenumber = $manager::findById($object[1])->getFaxNumber();
				$controller = "company";
				$action = "edit_client";
				break;
			case 'Contacts':
				$phonenumber = $manager::findById($object[1])->getWFaxNumber();
				$controller = "contact";
				$action = "edit";
				break;
			default:
				$phonenumber = false;
		}

		if ($phonenumber) {
			$this->sendfax($phonenumber, $file_path) &&
					ApplicationLogs::createLog($file, $file->getWorkspaces(), ApplicationLogs::ACTION_FAX, false, null, true, $objects);
		} else {
			ajx_extra_data(array(
				'error' => array(
					'code' => 'nofax',
					'extra' => array(
						'controller' => $controller,
						'action' => $action,
						'id' => $object[1]
					)
				)
			));
			flash_error('Manca il numero di fax del contatto scelto');
		}

		ajx_current('empty');
	}
}

?>