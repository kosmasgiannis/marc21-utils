<?php

function txt2xsl($f) {
global $mxml, $lxml;

$lines = file($f);
foreach ($lines as $line) {
  preg_match_all("/(([^,]*),)/",$line,$m);
  $tag_def = $m[2][0];
  $ind1 = $m[2][1];
  $ind2 = $m[2][2];
  $fields = $m[2][3];
  $rf = '';
  $nrf = '';
  $tag = preg_replace('/=.*/','',$tag_def);
  if (($tag == 'LDR') || (intval($tag) < 10)) continue;
  $tagr = preg_replace('/.*=/','',$tag_def);
  $repeatable = substr($tagr, 0, 1);
  for ($i=0; $i<=strlen($fields); $i=$i+2) {
   $f = substr($fields,$i, 2);
   if (substr($f,1,1) == 'R') $rf .= substr($f,0,1);
   else $nrf .= substr($f,0,1);
  }
  if ($repeatable == 'N') {
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
  $lxml .= '  <xsl:template match="marc:datafield[@tag='.$tag.']">'."\n";
  $lxml .= '    <xsl:call-template name="validateDatafield">'."\n";
  $lxml .= '      <xsl:with-param name="sCodesR">'.$rf.'</xsl:with-param>'."\n";
  $lxml .= '      <xsl:with-param name="sCodesNR">'.$nrf.'</xsl:with-param>'."\n";
  $lxml .= '      <xsl:with-param name="i1Values" xml:space="preserve">'.$ind1.'</xsl:with-param>'."\n";
  $lxml .= '      <xsl:with-param name="i2Values" xml:space="preserve">'.$ind2.'</xsl:with-param>'."\n";
  $lxml .= '    </xsl:call-template>'."\n";
  $lxml .= '  </xsl:template>'."\n";
}
}
?>
