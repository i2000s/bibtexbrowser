<?php
    function PhysicsBibliographyStyle(&$bibentry) {
      $title = $bibentry->getTitle();
      $type = $bibentry->getType();

      // later on, all values of $entry will be joined by a comma
      $entry=array();

      // title
      // usually in bold: .bibtitle { font-weight:bold; }
      $title = '<span class="bibtitle"  itemprop="name">&quot;'.$title.'&quot;</span>';
      if ($bibentry->hasField('url')) $title = ' <a'.(BIBTEXBROWSER_BIB_IN_NEW_WINDOW?' target="_blank" ':'').' href="'.$bibentry->getField('url').'">'.$title.'</a>';


      // author
      if ($bibentry->hasField('author')) {
        $coreInfo = ' <span class="bibauthor">'.$bibentry->getFormattedAuthorsImproved().'</span>, ' . $title ;}
      else $coreInfo = $title;

      // core info usually contains title + author
      $entry[] = $coreInfo;

      // now the book title
      $booktitle = '';
      if ($type=="inproceedings") {
          $booktitle = '<span itemprop="isPartOf">'.$bibentry->getField(BOOKTITLE).'</span>'; }
      if ($type=="incollection") {
          $booktitle = __('Chapter in').' '.'<span itemprop="isPartOf">'.$bibentry->getField(BOOKTITLE).'</span>';}
      if ($type=="inbook") {
          $booktitle = __('Chapter in').' '.$bibentry->getField('chapter');}
      if ($type=="article") {
          $booktitle = '<span itemprop="isPartOf">'.$bibentry->getField("journal").'</span>';}

      //// we may add the editor names to the booktitle
      $editor='';
      if ($bibentry->hasField(EDITOR)) {
        $editor = $bibentry->getFormattedEditors();
      }
      if ($editor!='') $booktitle .=' ('.$editor.')';
      // end editor section

      // is the booktitle available
      if ($booktitle!='') {
        $entry[] = '<span class="bibbooktitle">'.$booktitle.'</span>';
      }


      $publisher='';
      if ($type=="phdthesis") {
          $publisher = __('PhD thesis').', '.$bibentry->getField(SCHOOL);
      }
      if ($type=="mastersthesis") {
          $publisher = __('Master\'s thesis').', '.$bibentry->getField(SCHOOL);
      }
      if ($type=="bachelorsthesis") {
          $publisher = __('Bachelor\'s thesis').', '.$bibentry->getField(SCHOOL);
      }
      if ($type=="techreport") {
          $publisher = __('Technical report');
          if ($bibentry->hasField("number")) {
              $publisher .= ' '.$bibentry->getField("number");
          }
          $publisher .= ', '.$bibentry->getField("institution");
      }

      if ($type=="misc") {
          $publisher = $bibentry->getField('howpublished');
      }

      if ($bibentry->hasField("publisher")) {
        $publisher = $bibentry->getField("publisher");
      }
      /* Omit publisher for journal articles. */
      if ($type=="article") { $publisher=''; }

      if ($publisher!='') $entry[] = '<span class="bibpublisher">'.$publisher.'</span>';

      /* Volume and issue number (some journals use Issue while some use Number.) */
      if ($bibentry->hasField('volume')) { //$entry[] =  __('volume').' '.$bibentry->getField("volume");  
         /* if ($bibentry->hasField('number')) {$entry[] = '<span itemprop="volumenumber">'.$bibentry->getField("volume").'</span>'.'(<span itemprop="issuenumber">'.$bibentry->getField("number").'</span>)';}
         elseif ($bibentry->hasField('issue')) {$entry[] = '<span itemprop="volumenumber">'.$bibentry->getField("volume").'</span>'.'(<span itemprop="issuenumber">'.$bibentry->getField("issue").'</span>)';}
         else $entry[] =  __('volume').' '.$bibentry->getField("volume");  */
         $entry[] = '<span itemprop="volume"><b>'.$bibentry->getField("volume").'</b></span>';
        }

        
      if ($bibentry->hasField(YEAR)) {
            if ($bibentry->hasField('pages')) {
                $entry[] = '<span itemprop="pagenumbers">'.$bibentry->getField("pages").'</span> <span itemprop="datePublished">('.$bibentry->getYear().')</span>';
            }
            else 
                $entry[] = '<span itemprop="datePublished">('.$bibentry->getYear().')</span>';
      }
      else {
            if ($bibentry->hasField('pages')) {
                $entry[] = '<span itemprop="pagenumbers">'.$bibentry->getField("pages").'</span>';}
      }


      // DOI link.
      // if ($bibentry->hasField('doi')) {$entry[] = '<span itemprop="doilink"><a href=http://doi.org/"'.$bibentry->getField('doi').'">DOI:'.$bibentry->getField("doi").'</a></span>';}

      $result = implode(", ",$entry).'.';

      // some comments (e.g. acceptance rate)?
      if ($bibentry->hasField('comment')) {
          $result .=  " <span class=\"bibcomment\">(".$bibentry->getField("comment").")</span>";
      }
      if ($bibentry->hasField('note')) {
          $result .=  " (".$bibentry->getField("note").")";
      }

      // add the Coin URL
      $result .=  $bibentry->toCoins();

      return '<span itemscope itemtype="http://schema.org/ScholarlyArticle">'.$result.'</span>';
    }
?>