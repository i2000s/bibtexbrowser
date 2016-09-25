<?php
    $_GET['library']=1;
    $_GET['bib']='ICIQmembers.bib';
    $_GET['all']=1;

    /* arguments below are those we want to ignore */
    unset($_GET['frameset']);
    unset($_GET['menu']);

    include('bibtexbrowser.php');

    setDB();
?>

<!-- <div id="bodyText">
<?php
// echo '<?xml version="1.0" encoding="UTF-8"?>'."\n";
?>
//    include('wp-content/themes/vantage-cquic/content.php');
/*    <form action="?Academic" method="get">
    <div class="sortbox toolbox">
        <a href="#" onclick="toggle();return false;">Raw List / Grouped<br /></a>
        <a href="?Academic">By Type<br /></a>
        <a href="?Year">By Year<br /></a>
        <div class="search">
            <input type="text" name="search" class="input_box" id="searchtext"/>
            <input type="hidden" name="bib" value="CQuICmembers.bib"/>
            <input type="submit" value="search" class="input_box"/>
        </div>
    </div>
    </form>
-->

<?php
    new Dispatcher();
?>
<!-- </div> -->
