<?php

class FaxController extends ApplicationController
{

  function __construct()
  {
    parent::__construct();
    prepare_company_website_controller($this, 'website');
  }

  function init()
  {
    ajx_current("panel", "fax", null, null, true);
    ajx_replace(true);
  }

  function index()
  {
    if (isset($error))
      tpl_assign('error', $error);
    tpl_assign('message', 'FAX');
  }

  function send()
  {
    $file_ids = array_var($_GET, 'ids');
    $phonenumber = array_var($_GET, 'phonenumber');

    foreach (json_decode($file_ids) as $file_id) {
      $file = ProjectFiles::findById($file_id);

      $file_hash = $file->getLastRevision()->getRepositoryId();
      $file_path = FileRepository::getBackend()->getFilePath($file_hash);

      $message[] = "faxsend -n -d $phonenumber $file_path";
    }

    if (isset($error))
      tpl_assign('error', $error);
    tpl_assign('message', implode('<br/>', $message));
  }

// index
}
?>