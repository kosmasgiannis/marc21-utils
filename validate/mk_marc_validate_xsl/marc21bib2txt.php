<?php
include_once('maintxt.php');
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

echo "LDR=NM,,http://www.loc.gov/marc/bibliographic/bdleader.html"."\n";
echo "001=NM,,http://www.loc.gov/marc/bibliographic/bd001.html"."\n";
echo "003=NO,,http://www.loc.gov/marc/bibliographic/bd003.html"."\n";
echo "005=NO,,http://www.loc.gov/marc/bibliographic/bd005.html"."\n";
echo "006=RO,,http://www.loc.gov/marc/bibliographic/bd006.html"."\n";
echo "007=RO,,http://www.loc.gov/marc/bibliographic/bd007.html"."\n";
echo "008=NM,,http://www.loc.gov/marc/bibliographic/bd008.html"."\n";
echo $lxml;
echo "853=RO,aNbNcNdNeNfNgNhNiNjNkNlNmNnNoNpNtNuRvRwNxNyRzR2R3N6N8R,http://www.loc.gov/marc/bibliographic/hd853.html"."\n";
echo "854=RO,aNbNcNdNeNfNgNhNiNjNkNlNmNnNoNpNtNuRvRwNxNyRzR2R3N6N8R,http://www.loc.gov/marc/bibliographic/hd854.html"."\n";
echo "855=RO,aNbNcNdNeNfNgNhNiNjNkNlNmNnNoNpNtNuRvRwNxNyRzR2R3N6N8R,http://www.loc.gov/marc/bibliographic/hd855.html"."\n";
echo "863=RO,aNbNcNdNeNfNgNhNiNjNkNlNmNnNpNqNsRtNwNxRzR6N8N,http://www.loc.gov/marc/bibliographic/hd863.html"."\n";
echo "864=RO,aNbNcNdNeNfNgNhNiNjNkNlNmNnNoNpNqNsRtNwNxRzR6N8N,http://www.loc.gov/marc/bibliographic/hd864.html"."\n";
echo "865=RO,aNbNcNdNeNfNgNhNiNjNkNlNmNnNoNpNqNsRtNvRwNxRzR6N8N,http://www.loc.gov/marc/bibliographic/hd865.html"."\n";
echo "866=RO,aNxRzR2N6N8R,http://www.loc.gov/marc/bibliographic/hd866.html"."\n";
echo "867=RO,aNxRzR2N6N8R,http://www.loc.gov/marc/bibliographic/hd867.html"."\n";
echo "868=RO,aNxRzR2N6N8R,http://www.loc.gov/marc/bibliographic/hd868.html"."\n";
echo "876=RO,aNbRcRdReRhRjRlRpRrRtNxRzR3N6N8N,http://www.loc.gov/marc/bibliographic/hd876.html"."\n";
echo "877=RO,aNbRcRdReRhRjRlRpRrRtNxRzR3N6N8N,http://www.loc.gov/marc/bibliographic/hd877.html"."\n";
echo "878=RO,aNbRcRdReRhRjRlRpRrRtNxRzR3N6N8N,http://www.loc.gov/marc/bibliographic/hd878.html"."\n";
echo "880=RO,aRbRcRdReRfRgRhRiRjRkRlRmRnRoRpRqRrRsRtRuRvRwRxRyRzR0R1R2R3R4R5R6N7R8R9R,http://www.loc.gov/marc/bibliographic/bd880.html"."\n";
echo "886=RO,aNbNcRdReRfRgRhRiRjRkRlRmRnRoRpRqRrRsRtRuRvRwRxRyRzR0R1R2N3R4R5R6R7R8R9R,http://www.loc.gov/marc/bibliographic/bd886.html"."\n";

echo "090=RO,aRbRcRdReRfRgRhRiRjRkRlRmRnRoRpRqRrRsRtRuRvRwRxRyRzR0R1R2R3R4R5R6R7R8R9R,"."\n";
echo "099=RO,aRbRcRdReRfRgRhRiRjRkRlRmRnRoRpRqRrRsRtRuRvRwRxRyRzR0R1R2R3R4R5R6R7R8R9R,"."\n";
echo "590=RO,aRbRcRdReRfRgRhRiRjRkRlRmRnRoRpRqRrRsRtRuRvRwRxRyRzR0R1R2R3R4R5R6R7R8R9R,http://www.loc.gov/marc/bibliographic/bd59x.html"."\n";
echo "591=RO,aRbRcRdReRfRgRhRiRjRkRlRmRnRoRpRqRrRsRtRuRvRwRxRyRzR0R1R2R3R4R5R6R7R8R9R,http://www.loc.gov/marc/bibliographic/bd59x.html"."\n";
echo "592=RO,aRbRcRdReRfRgRhRiRjRkRlRmRnRoRpRqRrRsRtRuRvRwRxRyRzR0R1R2R3R4R5R6R7R8R9R,http://www.loc.gov/marc/bibliographic/bd59x.html"."\n";
echo "593=RO,aRbRcRdReRfRgRhRiRjRkRlRmRnRoRpRqRrRsRtRuRvRwRxRyRzR0R1R2R3R4R5R6R7R8R9R,http://www.loc.gov/marc/bibliographic/bd59x.html"."\n";
echo "594=RO,aRbRcRdReRfRgRhRiRjRkRlRmRnRoRpRqRrRsRtRuRvRwRxRyRzR0R1R2R3R4R5R6R7R8R9R,http://www.loc.gov/marc/bibliographic/bd59x.html"."\n";
echo "595=RO,aRbRcRdReRfRgRhRiRjRkRlRmRnRoRpRqRrRsRtRuRvRwRxRyRzR0R1R2R3R4R5R6R7R8R9R,http://www.loc.gov/marc/bibliographic/bd59x.html"."\n";
echo "596=RO,aRbRcRdReRfRgRhRiRjRkRlRmRnRoRpRqRrRsRtRuRvRwRxRyRzR0R1R2R3R4R5R6R7R8R9R,http://www.loc.gov/marc/bibliographic/bd59x.html"."\n";
echo "597=RO,aRbRcRdReRfRgRhRiRjRkRlRmRnRoRpRqRrRsRtRuRvRwRxRyRzR0R1R2R3R4R5R6R7R8R9R,http://www.loc.gov/marc/bibliographic/bd59x.html"."\n";
echo "598=RO,aRbRcRdReRfRgRhRiRjRkRlRmRnRoRpRqRrRsRtRuRvRwRxRyRzR0R1R2R3R4R5R6R7R8R9R,http://www.loc.gov/marc/bibliographic/bd59x.html"."\n";
echo "599=RO,aRbRcRdReRfRgRhRiRjRkRlRmRnRoRpRqRrRsRtRuRvRwRxRyRzR0R1R2R3R4R5R6R7R8R9R,http://www.loc.gov/marc/bibliographic/bd59x.html"."\n";
echo "936=RO,aRbRcRdReRfRgRhRiRjRkRlRmRnRoRpRqRrRsRtRuRvRwRxRyRzR0R1R2R3R4R5R6R7R8R9R,"."\n";
echo "964=RO,aRbRcRdReRfRgRhRiRjRkRlRmRnRoRpRqRrRsRtRuRvRwRxRyRzR0R1R2R3R4R5R6R7R8R9R,"."\n";

?>
