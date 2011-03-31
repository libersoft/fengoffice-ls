<?php
Hook::register("html_to_pdf");

function html_to_pdf_render_page_actions($object, &$ignored) {
	if ($object instanceof ProjectFile && $object->getTypeString() == 'text/html') {
		add_page_action(
			lang('export to pdf'),
			get_url('html_to_pdf', 'export', array('id' => $object->getId())),
			'ico-html_to_pdf',
			'_blank'
		);
	}
}

function html_to_pdf_autoload_stylesheets($ignored, &$css) {
	$css[] = 'plugins/html_to_pdf.css';
}
?>