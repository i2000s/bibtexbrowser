<?php
/* This is the Local customized code for bibtexbrowser.php utility. */
/* Some good instructions and useful codes of modifying it can be found at http://blog.spd.gr/2012/04/bibtexbrowser-music-for-publication_09.html */
/* Code has been used from https://github.com/g-oikonomou/bibtexbrowser/ */

    include('pubstyle.php');
    define('BIBLIOGRAPHYSTYLE','PhysicsBibliographyStyle');
    // define('BIBTEXBROWSER_USE_PROGRESSIVE_ENHANCEMENT',false);
    define('BIBLIOGRAPHYSECTIONS','my_sectioning');
    define('BIBTEXBROWSER_CSS', '/bibtexbrowser.css');
    define('BIBTEXBROWSER_URL',''); //Get the individual bib pages embedded.
    define('METADATA_EPRINTS',true);
    define('ABBRV_TYPE','year');
    define('BIBTEXBROWSER_LAYOUT','table');
    // define('BIBTEXBROWSER_EMBEDDED_WRAPPER', 'CustomWrapper');
    // define('BIBTEXBROWSER_DEFAULT_TEMPLATE','CustomHTMLTemplate');
    // Define the hiden field from showing from the public bibtex source code.
    define('BIBTEXBROWSER_BIBTEX_VIEW','reconstructed');
    define('BIBTEXBROWSER_BIBTEX_VIEW_FILTEREDOUT','timestamp|owner|grantnumber|grouptag|publicationdate|acceptancedate|submissiondate');
    define('BIBTEXBROWSER_AUTHOR_LINKS','homepage'); // This supposes to link homepages to CQuIC authors if defined in the AuthorLinks.bib file.
    // Show page numbering.
    bibtexbrowser_configure('BIBTEXBROWSER_DEFAULT_DISPLAY','PagedDisplay');
    bibtexbrowser_configure('PAGE_SIZE','25');
    // Change the default frame.
    define('BIBTEXBROWSER_DEFAULT_FRAME','all');

    function my_sectioning() {
        return
            array(
                // Articles
                array(
                  'query' => array(Q_TYPE=>'article'),
                  'title' => 'Journal Articles'
                ),
                // Conference and Workshop papers
                array(
                  'query' => array(Q_TYPE=>'inproceedings'),
                  'title' => 'Conference and Workshop Papers'
                ),
                // Books / InBook / InCollection
                array(
                  'query' => array(Q_TYPE=>'book|inbook|incollection'),
                  'title' => 'Books and Book Chapters'
                ),
                // Theses
                array(
                  'query' => array(Q_TYPE=>'phdthesis|mastersthesis|bachelorsthesis'),
                  'title' => 'Theses'
                ),
                // Presentations
                array(
                  'query' => array(Q_TYPE=>'presentation'),
                  'title' => 'Selected Presentations'
                ),
                // others
                array(
                  'query' => array(Q_TYPE=>'misc|techreport'),
                  'title' => 'Other Publications'
                )
            );
    }

    function update_query($query, $suffix=NULL) {
        $args = explode('&', $query);
        foreach($args as $key => $val) {
            $comp = strtolower($val);
            /* Only remove exact match for year, leave year=foo alone */
            if(strpos($comp, 'academic') !== False || strpos($comp, 'all') !== False || strpos($comp, 'astext') !== False || $comp == 'year') {
                unset($args[$key]);
            }
        }
        if($suffix != NULL) { $args[] = $suffix; }
        $query = implode("&amp;", $args);
        if(strlen($query) > 0) { $query = '?' . $query; }
        return $query;
    }

    class CustomYearMenu  {
        function CustomYearMenu() {
            if (!isset($_GET[Q_DB])) {die('Did you forget to call setDB() before instantiating this class?');}
            $yearIndex = $_GET[Q_DB]->yearIndex();
            ?>
            <div id="yearmenu" class="filterbox toolbox">
                <span class="this">Years:</span>
                <div class="filterlist" id="yearlist">
                <?php
                echo '<span><a '.makeHref(array(Q_YEAR=>'.*')).'>All</a></span>'."\n";
                foreach($yearIndex as $year) {
                    echo '<span><a '.makeHref(array(Q_YEAR=>$year)).'>'.$year.'</a></span>'."\n";
                }
                ?>
            </div>
            </div>
            <?php
        }
    }
?>
<?php
    class CustomAuthorsMenu {
        function CustomAuthorsMenu() {
            if (!isset($_GET[Q_DB])) {die('Did you forget to call setDB() before instantiating this class?');}
            $authorIndex = $_GET[Q_DB]->authorIndex();
            ?>
            <select id="authorlist">
                <?php
                foreach($authorIndex as $author) {
                    echo '<option value="publib.php?'.createQueryString(array(Q_AUTHOR=>$author)).'">'.$author.'</option>\n';
                }
                ?>
            </select>
            <button onclick="load_author()">Search</button>
            <script language="javascript">
                function load_author() {
                    window.location = $('#authorlist').val();
                }
            </script>
            <?php
        }
    }
?>
<?php
    function getPublisherDisclaimer($entry) {
        $pre = '<div>';
        $post = '</div>';

        $publisher = $entry->getField('publisher');
        $pos = stripos($publisher, 'ieee');
        if($pos !== false) {
            return $pre.'© '.$entry->getYear().' IEEE. Personal use of this material is permitted. Permission from IEEE must be obtained for all other uses, in any current or future media, including reprinting/republishing this material for advertising or promotional purposes, creating new collective works, for resale or redistribution to servers or lists, or reuse of any copyrighted component of this work in other works.' . $post;
        }

        /* Check if publisher contains 'springer' */
        $pos = stripos($publisher, 'springer');
        if($pos !== false) {
            return $pre . 'The original publication is available at <a href="http://www.springerlink.com">http://www.springerlink.com</a>' . $post;
        }

        /* Check if publisher contains 'APS' */
        $pos = stripos($publisher, 'APS');
        if($pos !== false) {
            return $pre . 'The most up-to-date information can be found at <a href="http://www.aps.org/">http://www.aps.org/</a>' . $post;
        }
        $pos = stripos($publisher, 'American Physical Society');
        if($pos !== false) {
            return $pre . 'The most up-to-date information can be found at <a href="http://www.aps.org/">http://www.aps.org/</a>' . $post;
        }
    }
?>

<?php
    Class CustomWrapper {
        function CustomWrapper(&$content, $metatags=array()) {
            //header ("Content-Type:application/xhtml+xml; charset=utf-8");
            echo '<?xml version="1.0" encoding="UTF-8"?>'."\n";
?>
            <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
            <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
                <head>
                    <meta http-equiv="Content-type" content="application/xhtml+xml; charset=utf-8" />
                    <link href="wp-content/themes/vantage-cquic/style.css" rel="stylesheet" type="text/css" />
                    <link href="bibtexbrowser.css" rel="stylesheet" type="text/css" />
                    <script language="javascript" src="wp-content/themes/vantage-cquic/js/1.7.2.jquery.min.js"></script>
                    <title>
                    <?php
                    if ($content instanceof BibEntryDisplay) {
                        echo $content->getTitle();
                    } else {
                        echo 'CQuIC - Publications';
                    }
                    ?>
                    </title>
                    <!--<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />-->
                    <meta name="keywords" content="CQuIC, quantum information, quantum computing, quantum control, quantum measurement, quantum metrology, quantum optics" />

                     <?php
                    /* Add content meta-data, if any */
                    foreach($metatags as $item) {
                        list($name,$value) = $item;
                        echo '<meta name="'.$name.'" content="'.$value.'"/>'."\n";
                    }
                    ?>
                </head>
                <body>
                    <?php
                    if ($content instanceof BibEntryDisplay) {
                        echo "<h1>".$content->getTitle()."</h1>";
                    } else {
                    ?>
                    <div class="content">
                        <h1 class="logo">CQuIC - Publications</h1>

                        <b>Note</b>: <em>This material is presented to ensure timely dissemination of scholarly and technical work. Copyright and all rights therein are retained by authors or by other copyright holders. All persons copying this information are expected to adhere to the terms and constraints invoked by each author's copyright. In most cases, these works may not be reposted without the explicit permission of the copyright holder. Free preprint copies of CQuIC publications may be downloaded from <a href="http://arxiv.org/" target="_blank">arXiv.org</a>.</em>
                        <br/><br/>

                    </div>
                    <?php }
                ?>
                <div class="searchbox">
                    <button onclick="window.location = 'publib.php?academic'">Show All</button>
                    <button onclick="window.location = '<?php echo update_query($_SERVER['QUERY_STRING'], 'academic'); ?>'">Sort by Type</button>
                    <button onclick="window.location = '<?php echo update_query($_SERVER['QUERY_STRING'], 'year'); ?>'">Sort by Year</button>
                    <!-- <button onclick="window.location = '<?php echo update_query($_SERVER['QUERY_STRING'], 'astext'); ?>'">Raw Bib</button> -->
                </div>
                <div class="search">
                    <form action="publib.php?academic" method="get">
                        <input type="text" name="<?php echo Q_SEARCH; ?>" class="input_box" id="searchtext" />
                        <input type="hidden" name="<?php echo Q_FILE; ?>" value="<?php echo $_GET[Q_FILE]; ?>" />
                        <input type="submit" value="search" class="input_box" />
                    </form>
                </div>
                <div class="author_search">
                    <?php new CustomAuthorsMenu(); ?>
                </div>
                <div id="bodyText">
                <?php
                    $content->display();
                ?>
                </div>
                <!-- Google analysis code can be customized below. -->
                <!-- <script type="text/javascript">

                    var _gaq = _gaq || [];
                    _gaq.push(['_setAccount', 'UA-11111111111-1']); // set a Google Analysis account number here to track visiting.
                    _gaq.push(['_trackPageview']);

                    (function() {
                        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
                        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
                        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
                    })();

                </script> -->
                </body>
            </html>
            <?php
        }
    }
?>

<?php
//   function CustomHTMLTemplate(&$content) {
        // require( 'wp-blog-header.php');
//        include ( TEMPLATEPATH . '/content.php');
//        echo '<div id="bodyText">';
//          $content->display();
//        echo '</div>';
//    } -->
?>
