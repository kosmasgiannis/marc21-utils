<?php
include_once('mainxsl.php');
include_once('authfields.php');

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

echo file_get_contents("head_auth.xs_",true);
echo $mxml;
echo file_get_contents("middle_auth.xs_",true);
echo $lxml;
echo file_get_contents("tail_auth.xs_",true);

?>
