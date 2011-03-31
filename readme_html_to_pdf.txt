Export HTML to PDF plugin for OpenGoo
=====================================
	version 1.0 - 2009/05/26
	(c) 2009 Ignacio de Soto <ignacio.desoto@opengoo.org>
	Thanks to Damián Pintos.

About
=====

	This plugin adds an "Export to PDF" action on HTML documents' view that will download
	a PDF version of the document. This plugin requires htmldoc installed on the server
	or a similar tool to convert HTML documents to PDF.


Requirements
============

	- OpenGoo 1.4+
	- htmldoc or a similar program to convert HTML files to PDF
		- http://www.htmldoc.org

	
Installation
============

	1- Unzip the plugin into your OpenGoo installation
	
	2- If the command to run htmldoc is other than 'htmldoc' define a constant
		HTMLDOC_COMMAND on 'config/config.php' specifying the path to htmldoc.
	    - e.g. 1:
	    
	    	define('HTMLDOC_COMMAND', '/usr/bin/htmldoc');
	    	
	    - e.g. 2:
	    
	    	define('HTMLDOC_COMMAND', 'C:\htmldoc\htmldoc.exe');
	
	
	You can use a different tool than htmldoc, as long as this tool reads an HTML
	file from a specified path and writes a PDF file to a specified path. Assuming
	you want to use a program called 'html2pdf' that expects two arguments (input and output)
	you would have to define these two constants on 'config/config.php' to make it work:
	
		define('HTMLDOC_COMMAND', '/path/to/html2pdf');
		define('HTMLDOC_ARGS', '-input {src} -output {dest}');


License
=======
	This software is licensed under the GPL 3.0 or later.
