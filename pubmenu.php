<?php
define('BIBTEXBROWSER_URL','puboutput.php');

$_GET['menu']=1;
$_GET['bib']='CQuICmembers.bib;AuthorLinks.bib';

echo '<style type="text/css">  '."\n";
  readfile(dirname(__FILE__).'/bibtexbrowser.css');
echo "\n".' </style>';

include('bibtexbrowser.php');
?>
