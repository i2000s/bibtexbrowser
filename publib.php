<?php
    $_GET['library']=1;
    $_GET['bib']='CQuICmembers.bib';
    $_GET['all']=1;

    /* arguments below are those we want to ignore */
    unset($_GET['frameset']);
    unset($_GET['menu']);

    include('bibtexbrowser.php');

    setDB();
/* ?> */

// <div id="bodyText">
// <?php
//    include('wp-content/themes/vantage-cquic/content.php');
    new Dispatcher();
?>
<!-- </div> -->