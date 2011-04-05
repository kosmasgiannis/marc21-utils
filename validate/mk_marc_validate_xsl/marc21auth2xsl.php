<?php
include_once('txt2xsl.php');

global $mxml;
global $lxml;
$mxml = '';
$lxml = '';

 if ($argc < 2) {
  echo "Usage   : php marc21auth2xsl.php <filename>\n";
  echo "Example : php marc21auth2xsl.php auth.stxi\n";
  exit(1);
 }
 $filename = $argv[1];

 if (!is_readable($filename)) {
  echo "Can not open file : $filename \n";
  exit(2);
 }

txt2xsl($filename);

echo file_get_contents("head_auth.xs_",true);
echo $mxml;
echo file_get_contents("middle_auth.xs_",true);
echo $lxml;
echo file_get_contents("tail_auth.xs_",true);

?>
