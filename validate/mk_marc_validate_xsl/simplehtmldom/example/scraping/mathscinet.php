<?php
include_once('../../simple_html_dom.php');

function scraping_mathscinet($q) {
    // create HTML DOM
    $html = file_get_html($q);
    $ret = array();
    if ($html != null) {
print_r($html);



    // get news block
    foreach($html->find('div.headlineText') as $article) {
        echo $article."\n";
        $item = array();
        $item['id'] = trim($article->find('a', 0)->plaintext);
        $item['title'] = trim($article->find('span.title', 0)->plaintext);
        $u = $article->find('div[class="sfx"] noscript a', 0);
        $item['url'] = $u->href;
        foreach ( $article->find('a[href*=IID]') as $author) {
          $name = $author->plaintext;
          $href = $author->href;
          $item['author'][] = $name;
        }
        $item['note'] = '';
        foreach ( $article->find('a[href*=ISSI]') as $note) {
          $n = $note->plaintext;
          $item['note'] .= $n . " ";
        }
// Get the rest from the openurl ;-) 

        //$item['details'] = trim($article->find('p', 0)->plaintext);
        //$es = $article->find('text');
        //$t = $es->innertext;
        //$item['text'] = $t;

        $ret[] = $item;
    }
    
 print_r($es);
    // clean up memory
    $html->clear();

}
    return $ret;
    unset($html);
}


// -----------------------------------------------------------------------------
// test it!

// "http://digg.com" will check user_agent header...
ini_set('user_agent', 'My-Application/2.5');

$ret = scraping_mathscinet('http://xxx.mpim-bonn.mpg.de/mathscinet/search/publications.html?pg4=AUCN&s4=&co4=AND&pg5=TI&s5=spline&co5=AND&pg6=PC&s6=&co6=AND&pg7=ALLF&s7=&co7=AND&Submit=Search&dr=all&yrop=eq&arg3=&yearRangeFirst=&yearRangeSecond=&pg8=ET&s8=All&review_format=html');

foreach($ret as $v) {
    print_r($v);
    echo '<br>';
}

?>
