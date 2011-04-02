<?php
include_once('mainxsl.php');
include_once('bibfields.php');

global $mxml;
global $lxml;
global $scf;
$scf = array();
$mxml = '';
$lxml = '';
setup_scf_array();

foreach ($scf as $k => $v) {
$ret = scraping_lc_marc21($v);
}

echo file_get_contents("head.xs_",true);
echo $mxml;
echo file_get_contents("middle.xs_",true);
echo $lxml;
echo file_get_contents("tail.xs_",true);

?>
