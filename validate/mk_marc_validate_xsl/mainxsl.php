<?php
include_once('simplehtmldom/simple_html_dom.php');

// -----------------------------------------------------------------------------
ini_set('user_agent', 'My-Application/2.5');

// -----------------------------------------------------------------------------

function scraping_lc_marc21($q) {
    global $mxml, $lxml;
    // create HTML DOM
    $html = file_get_html($q);
    $ret = array();
    $i1values = $i2values = $rcodes = $nrcodes = '';
    if ($html != null) {

      $field = $html->find('h1',0);
      if ($field) {
        $hhh = normalize_field($field->plaintext);
        $tag = preg_replace('/ - .*/','',$hhh);
        $tagr = preg_replace('/\).*/','',preg_replace('/.*\(/','',$hhh));

        if ($tagr == 'NR') {
          if ($tag == '245') {
            $mxml .= '    <xsl:if test="count(marc:datafield[@tag='. $tag . ']) != 1">' . "\n";
          } else {
            $mxml .= '    <xsl:if test="count(marc:datafield[@tag='. $tag . '])&gt;1">' . "\n";
          }
          if ($tag == '245') {
            $mxml .= '      <error type="MandatoryNonRepeatable">'."\n";
          } else {
            $mxml .= '      <error type="NonRepeatable">'."\n";
          }
          $mxml .= '        <xsl:call-template name="controlNumber"/>'."\n";
          $mxml .= '        <tag>'.$tag."</tag>\n";
          $mxml .= '      </error>'."\n";
          $mxml .= '    </xsl:if>'."\n";
        }

        $ind1 = $html->find('td',0);
        if ($ind1) {
          $a = normalize_field($ind1->innertext)."\n";
          $a = preg_replace('/.*\<\/em\>\<br\>/','',$a);
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


        $ind2 = $html->find('td',1);
        if ($ind2) {
          $a = normalize_field($ind2->innertext)."\n";
          $a = preg_replace('/.*\<\/em\>\<br\>/','',$a);
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

        $sf = "";
        $sf1 = $html->find('td',5);
        if ($sf1) {
          $sf .= normalize_field($sf1->plaintext);
        }
        $sf2 = $html->find('td',6);
        if ($sf2) {
          if ($sf != '') $sf .= " ";
          $sf .= normalize_field($sf2->plaintext);
        }

        preg_match_all("/[\$](.)[^\$]/",$sf,$mk);
        preg_match_all("/[\(]([N,R]*)[\)]/",$sf,$mv);
        $sfar = array();
        foreach($mk[1] as $k => $v) {
         $sfar[$v] = $mv[1][$k];
        }

        foreach ($sfar as $k => $v) {
          if ($v == 'NR') $nrcodes .= $k;
          if ($v == 'R') $rcodes .= $k;
        }

        $lxml .= '  <xsl:template match="marc:datafield[@tag='.$tag.']">'."\n";
        $lxml .= '    <xsl:call-template name="validateDatafield">'."\n";
        $lxml .= '      <xsl:with-param name="sCodesR">'.$rcodes.'</xsl:with-param>'."\n";
        $lxml .= '      <xsl:with-param name="sCodesNR">'.$nrcodes.'</xsl:with-param>'."\n";
        $lxml .= '      <xsl:with-param name="i1Values" xml:space="preserve">'.$i1values.'</xsl:with-param>'."\n";
        $lxml .= '      <xsl:with-param name="i2Values" xml:space="preserve">'.$i2values.'</xsl:with-param>'."\n";
        $lxml .= '    </xsl:call-template>'."\n";
        $lxml .= '  </xsl:template>'."\n";

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

?>
