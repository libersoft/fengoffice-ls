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

	function sendfax($phonenumbers, $file_path) {
		$command = "sendfax " . HYLAFAX_PARAMS . " -o " . HYLAFAX_LOGIN . " -h " . HYLAFAX_HOST;

		foreach ($phonenumbers as $phonenumber) {
			$command .= " -d " . HYLAFAX_NUMBER_PREFIX . $phonenumber;
		}

		$command .= ' ' . $file_path;

		exec($command, $command_output, $command_returnvalue);

		return $command_returnvalue == 0;
	}

	function contact() {
		$file_id = array_var($_GET, 'id');
		$objects = array_var($_GET, 'objects');

		$file = ProjectFiles::findById($file_id);
		$file_path = ROOT . "/tmp/$file_id";
		file_put_contents($file_path, $file->getFileContent());

		$phonenumbers = array();
		$object_strings = array();
		$nofax = array();
		foreach (split(",", $objects) as $object_string) {
			$object = split(":", $object_string);
			switch ($manager = $object[0]) {
				case 'Companies':
					$company = $manager::findById($object[1]);
					$phonenumber = $company->getFaxNumber();
					$controller = "company";
					$action = "edit_client";
					$name = $company->getName();
					break;
				case 'Contacts':
					$contact = $manager::findById($object[1]);
					$phonenumber = $contact->getWFaxNumber();
					$controller = "contact";
					$action = "edit";
					$name = $contact->getFirstname() . ' ' . $contact->getLastname();
					break;
				default:
					$phonenumber = false;
			}

			if ($phonenumber) {
				$phonenumbers[] = $phonenumber;
				$object_strings[] = $object_string;
			} else {
				$nofax[] = array(
					'controller' => $controller,
					'action' => $action,
					'id' => $object[1],
					'name' => $name
				);
			}
		}

		// Gestione dei contatti senza numero di fax
		switch (count($nofax)) {
			case 0:
				foreach ($object_strings as $object_str) {
					ApplicationLogs::createLog($file, $file->getWorkspaces(), ApplicationLogs::ACTION_FAX, false, null, true, $object_str);
				}
				$hylafax_return = $this->sendfax($phonenumbers, $file_path);
				$hylafax_return ? flash_success("Fax accodati con successo!") : flash_error("Errore di HylaFAX");
				break;
			case 1:
				ajx_extra_data(array(
					'error' => array(
						'code' => 'nofax',
						'extra' => $nofax[0]
					)
				));
				flash_error('Manca il numero di fax del contatto scelto');
				break;
			default:
				$names = ": ";
				foreach ($nofax as $k => $contact) {
					if ($k != 0) {
						$names .= ", ";
					}

					$names .= $contact['name'];
				}
				flash_error("Manca il numero di fax dei contatti" . $names);
		}

		ajx_current('empty');
	}
}

?>