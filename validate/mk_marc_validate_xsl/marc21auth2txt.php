<?php
include_once('maintxt.php');
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

echo $lxml;

?>
