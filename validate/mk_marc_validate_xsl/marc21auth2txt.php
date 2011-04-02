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

echo "LDR=NM,,http://www.loc.gov/marc/authority/adleader.html"."\n";
echo "001=NM,,http://www.loc.gov/marc/authority/ad001.html"."\n";
echo "003=NO,,http://www.loc.gov/marc/authority/ad003.html"."\n";
echo "005=NO,,http://www.loc.gov/marc/authority/ad005.html"."\n";
echo "008=NM,,http://www.loc.gov/marc/authority/ad008.html"."\n";

echo $lxml;

?>
