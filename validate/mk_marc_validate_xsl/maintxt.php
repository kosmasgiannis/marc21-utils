<?php
include_once('simplehtmldom/simple_html_dom.php');

// -----------------------------------------------------------------------------
ini_set('user_agent', 'My-Application/2.5');

// -----------------------------------------------------------------------------

function scraping_lc_marc21($q) {
    $debug = false;
    // create HTML DOM
    $html = file_get_html($q);
    $ret = array();
    $i1values = $i2values = $rcodes = $nrcodes = '';
    $lxml = '';
    if ($html != null) {

      $field = $html->find('h1',0);
      if ($field) {
        $hhh = normalize_field($field->plaintext);
        $tag = preg_replace('/ - .*/','',$hhh);
        $tagr = preg_replace('/\).*/','',preg_replace('/.*\(/','',$hhh));

        $lxml = $tag.'=';
        if ($tagr == 'NR') {
          if ($tag == '245') {
            $lxml .= 'NM';
          } else {
            $lxml .= 'NO';
          }
        } else {
          $lxml .= 'RO';
        }
        $lxml .= ',';

        $help = $html->find('div.indicatorhead',0);
        if ( $help) {
if ($debug) echo "DIVs\n";
          $searchwithdivs = true;
        } else {
          $searchwithdivs = false;
        }
        $d1ind1 = $html->find('div.indicatorvalue',0);
        if (($d1ind1) && ($searchwithdivs == true)) {
if ($debug) echo "DIV IND1 $d1ind1->innertext\n";
          $ind1 = '';
          $x = $d1ind1->find('div.indicatorvalue');
          foreach ($x as $i) {
            $ind1 .= "$i->innertext @";
if ($debug) echo "--- IND1 $i->innertext @\n";
          }
        } else {
          $ind1 = $html->find('td',0);
if ($debug) echo "TD  IND1 $ind1->innertext\n";
          if ($ind1) {
            $ind1 = normalize_field($ind1->innertext)."\n";
if ($debug) echo "--- IND1 $ind1\n";
          } else {
            $ind1 = '';
          }
        }

        if ($ind1 != '') {
          $a = $ind1;
if ($debug) echo "--- IND1 $a\n";
          $a = preg_replace('/.*\<\/strong\>\<br\>/','',$a);
          $b = $a."x";
          while ($b != $a) {
            $b = $a;
            $a = preg_replace('/\<em\>.*\<\/em\>/U','',$a,1);
          }
          $a = preg_replace('/\<br\>/','@',$a);
          $a = preg_replace('/@@/','@',$a);
          preg_match_all("/([@]*([^ ]*) - [^\@]*)/",$a,$m);
        }

        foreach ($m[2] as $k => $v) {
         if ($v == '#') $v = " "; 
         if ($v == '0-9') $v= "0123456789"; 
         if ($v == '1-9') $v= "123456789"; 
         $i1values .= $v;
        }

        $d2ind2 = $html->find('div.indicatorvalue',1);
        if ($d2ind2 && $searchwithdivs == true) {
if ($debug) echo "DIV IND2 $d2ind2->innertext\n";
          $ind2 = '';
          $x = $d2ind2->find('div.indicatorvalue');
          foreach ($x as $i) {
            $ind2 .= "$i->innertext @";
if ($debug) echo "--- IND2 $i->innertext @\n";
          }
        } else {
          $ind2 = $html->find('td',1);
          if ($ind2) {
            $ind2 = normalize_field($ind2->innertext)."\n";
          } else {
            $ind2 = '';
          }
        }
        if ($ind2 != '') {
          $a = $ind2;
if ($debug) echo "--- IND2 $a\n";
          $a = preg_replace('/.*\<\/strong\>\<br\>/','',$a);
          $b = $a."x";
          while ($b != $a) {
            $b = $a;
            $a = preg_replace('/\<em\>.*\<\/em\>/U','',$a,1);
          }
          $a = preg_replace('/\<br\>/','@',$a);
          $a = preg_replace('/@@/','@',$a);
          preg_match_all("/([@]*([^ ]*) - [^\@]*)/",$a,$m);
        }

        foreach ($m[2] as $k => $v) {
         if ($v == '#') $v = " ";
         if ($v == '0-9') $v= "0123456789";
         if ($v == '1-9') $v= "123456789";
         $i2values .= $v;
        }

        $lxml .= $i1values.','.$i2values.',';

        $sf = "";
        $divsf = $html->find('div.subfieldvalue');
        if ($divsf) {
          foreach ($divsf as $sfi) {
            $sfi = normalize_subfielddiv($sfi->innertext);
            $sf .="$sfi <br>";
          }
        } else {
          $sf0 = $html->find('td',4);
          if ($sf0) {
            $sf .= normalize_field($sf0->plaintext);
          }
          $sf1 = $html->find('td',5);
          if ($sf1) {
            $sf .= normalize_field($sf1->plaintext);
          }
          $sf2 = $html->find('td',6);
          if ($sf2) {
            if ($sf != '') $sf .= " ";
            $sf .= normalize_field($sf2->plaintext);
          }
        }

        preg_match_all("/[\$](.)[^\$]/",$sf,$mk);
        preg_match_all("/[\(]([N,R]*)[\)]*/",$sf,$mv);
        $sfar = array();
        foreach($mk[1] as $k => $v) {
         $sfar[$v] = $mv[1][$k];
        }

        foreach ($sfar as $k => $v) {
          if ($v == 'NR') { $nrcodes .= $k; $v='N';}
          if ($v == 'R') $rcodes .= $k;
          $lxml .= $k.$v;
        }
        $lxml .= ','.$q."\n";

        echo $lxml;

      }
    // clean up memory
      $html->clear();

  }
  return $ret;
  unset($html);
}

function normalize_field($s) {
 $r = preg_replace('/\n/','',$s);
 $r = preg_replace('/\s+/', ' ',trim($r));
 return $r;
}

function normalize_subfielddiv($s) {
 $r = preg_replace('/\n/','',$s);
 $r = preg_replace('/\<div class="description"\>.*\<\/div\>/','',$r);
 $r = preg_replace('/\s+/', ' ',trim($r));
 return $r;
}

?>
