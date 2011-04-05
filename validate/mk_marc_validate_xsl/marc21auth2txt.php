<?php
include_once('maintxt.php');
include_once('authfields.php');

global $scf;
$scf = array();
setup_scf_array();

echo "LDR=NM,,,,http://www.loc.gov/marc/authority/adleader.html"."\n";
echo "001=NM,,,,http://www.loc.gov/marc/authority/ad001.html"."\n";
echo "003=NO,,,,http://www.loc.gov/marc/authority/ad003.html"."\n";
echo "005=NO,,,,http://www.loc.gov/marc/authority/ad005.html"."\n";
echo "008=NM,,,,http://www.loc.gov/marc/authority/ad008.html"."\n";

foreach ($scf as $k => $v) {
    $ret = scraping_lc_marc21($v);
}
echo "880=RO, 1234567890, 1234567890,aRbRcRdReRfRgRhRiRjRkRlRmRnRoRpRqRrRsRtRuRvRwRxRyRzR0R1R2R3R4R5R6N7R8R9R,http://www.loc.gov/marc/authority/ad880.html"."\n";

?>
