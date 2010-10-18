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

// index
}
?>