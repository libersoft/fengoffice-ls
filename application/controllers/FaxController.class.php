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
    $file_id = array_var($_GET,'id');

    $file = ProjectFiles::findById($file_id);

    $file_hash = $file->getLastRevision()->getRepositoryId();
    $file_path = FileRepository::getBackend()->getFilePath($file_hash);

    if (isset($error))
      tpl_assign('error', $error);
    tpl_assign('message', "faxsend -n -d xxxxxxxx $file_path");
  }

// index
}
?>