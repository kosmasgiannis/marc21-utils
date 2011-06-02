<?php
// ----------------------------------------------------------------------------
//
// Author: Giannis Kosmas kosmasgiannis@gmail.com
//
// ----------------------------------------------------------------------------
// It runs from the command line. 
// 
// Usage :
// php mixedgrlat.php inputfile [-d]
//
// It accepts a MARC file in line format. (You may use yaz-marcdump to 
// convert it from iso2709 or marcxml).
// It assumes that characters are in UTF-8 format.
// 
// Run first in debug mode, look at the matched words and their corrections 
// and fine tune the ignore_list.
//
// You may set the list of fields that should be ignored from processing or 
// should be removed from result.
//
// The output is MARC records in line format on your screen.
// (You should redirect output to a file and use yaz-marcdump to create
// iso2709 or MARCXML).
// ----------------------------------------------------------------------------

$debug = 0;

$void_fields = array (
 '000 ',
);

$skip_fields = array (
 '099 ',
);

$ignore_list = array (
 'cύμβαcιc',
 'Ετεοκλέουc',
);

$filename = '';
if (isset($argv[1])) {
  $filename = $argv[1];
} 

if (isset($argv[2])) {
  if (($argv[2] == '-d') || ($argv[2]=='--d')) $debug = 1;
} 

$filename = realpath($filename);

if (($filename !== FALSE) && (is_readable($filename))) {
  if ($debug == 1) echo "Checking for words with mixed greek latin characters.\n";
  fix_mixed_greek_latin_marc21($filename, $skip_fields, $void_fields, $ignore_list, $debug);
} else {
 echo "ERROR : File ".$filename." does not exist.\n";
}

//---------------------------------------
//--- Do not change beyond this line. ---
//---------------------------------------

/* */
function fix_mixed_greek_latin_marc21($filename, $skip_fields, $void_fields, $ignore_list, $debug = 0) {

  $stat = 0;
  $fc = file_get_contents($filename,true);

  preg_match_all("/([^\n]+)/",$fc,$t);

  mb_internal_encoding("UTF-8");
  foreach ($t[0] as $x => $text) {

    if (array_search(substr($text,0,4),$void_fields) !== FALSE) {
      continue;
    }

    if (array_search(substr($text,0,4),$skip_fields) !== FALSE) {
      if ($debug != 1) echo "$text\n";
      continue;
    }

    if ($debug != 1) echo "$text\n";

    $mixed_result = examine_mixed_greek_latin_words($text);
    if ($mixed_result !== FALSE) {
      $corrections = array();
      $i = 0;
      foreach ($mixed_result as $v) {
        if (array_search($v, $ignore_list) === FALSE) {
          if (($debug == 1) && ($stat == 0)) echo "Listing of problematic words\n---\n";
          $stat++;
          $corrections[$i]['l'] =  mb_strlen($v);
          $corrections[$i]['m'] =  $v;
          $corrections[$i]['c'] =  fix_mixed_greek_latin_words($v);
          $i++;
        }
      }
      uasort($corrections,'mixed_sort_cmp');
  
      $i = 0;
      foreach ($corrections as $a) {
        if ($debug == 1) {
          echo $a['m']." => ".$a['c']."\n";
        }
        $patterns[$i] = '/'.$a['m'].'/'; $replacements[$i] = $a['c'];
        $i++;
      }
    
      if ($debug != 1) {
        $text = preg_replace($patterns, $replacements, $text);
        echo "$text\n";
      }
    }
  }
  if ($debug == 1) {
    if ($stat > 0) echo "---\n";
    echo $stat ." word(s) found with mixed greek latin characters.\n";
  }
}


function mixed_sort_cmp($a, $b) {
 return $b['l'] - $a['l'];
}

function examine_mixed_greek_latin_words($text) {
  $mixed_words = array();

  preg_match_all("/([^«»¨\΄“—”’–´…∙[:cntrl:][:punct:][:space:]]+)/u",$text,$m);
  mb_internal_encoding("UTF-8");
  $greek_letters = "αβγδεζηθικλμνξοπρστυφχψωάέήίόύώϊϋΐΰςΑΒΓΔΕΖΗΘΙΚΛΜΝΞΟΠΡΣΤΥΦΧΨΩΆΈΉΊΌΎΏΪΫ";
  $numbers = "0123456789";
  foreach ($m[0] as $k => $v) {
   $greek = 0;
   $number = 0;
   $latin = 0;
   $mixed = 0;
   for ($i = 0; $i < mb_strlen($v); $i++) {
     $t =  mb_substr($v,$i,1);
     if (mb_strstr($greek_letters,$t, TRUE) !== FALSE) $greek = 1;
     else if (mb_strstr($numbers, $t,  TRUE) !== FALSE) $number = 1;
     else $latin=1;
     if (($greek == 1) && ($latin == 1 )) {
      $mixed = 1;
      break;
     }
   }
   if ($mixed == 1 ) $mixed_words[] = $v;
  }
  if (!isset($mixed_words[0])) return FALSE;
  else return $mixed_words;
}

function fix_mixed_greek_latin_words($word) {
   $greek_cnt = 0;
   $latin_cnt = 0;
   $greek_first = 0;
   $greek_last = 0;
   mb_internal_encoding("UTF-8");
   $greek_letters = "αβγδεζηθικλμνξοπρστυφχψωάέήίόύώϊϋΐΰςΑΒΓΔΕΖΗΘΙΚΛΜΝΞΟΠΡΣΤΥΦΧΨΩΆΈΉΊΌΎΏΪΫ";
   $wlen = mb_strlen($word);
   for ($i = 0; $i < $wlen; $i++) {
     $t =  mb_substr($word,$i,1);
     if (mb_strstr($greek_letters,$t, TRUE) !== FALSE) {
       if ($i == 0) $greek_first = 1;
       if ($i == $wlen-1) $greek_last = 1;
       $greek_cnt++;
     } 
     else $latin_cnt++;
   }
   //echo "$word : GC=$greek_cnt LC=$latin_cnt GF=$greek_first \n";

   if ((($greek_first == 1) && ($latin_cnt > $greek_cnt) ) || (($greek_last == 1) && ($latin_cnt > $greek_cnt)) ) {
    return change_to_latin($word);
   } elseif ((($greek_first == 0) && ($latin_cnt < $greek_cnt) ) || (($greek_last == 0) && ($latin_cnt < $greek_cnt)) ) {
    return change_to_greek($word);
   } else {
     if ($latin_cnt < $greek_cnt) {
       return change_to_greek($word);
     } elseif ($latin_cnt > $greek_cnt) {
       return change_to_latin($word);
     } else {
       return $word;
     }
   }
}

function change_to_greek($word) {
   $res = "";
   mb_internal_encoding("UTF-8");
   $greek_letters = "αβγδεζηθικλμνξοπρστυφχψωςΑΒΓΔΕΖΗΘΙΚΛΜΝΞΟΠΡΣΤΥΦΧΨΩ";
   $latin_letters = "abgdezhuiklmnjoprstyfxcvwABGDEZHUIKLMNJOPRSTYFXCV";
   $wlen = mb_strlen($word);
   for ($i = 0; $i < $wlen; $i++) {
     $t =  mb_substr($word,$i,1);
     if (mb_strstr($greek_letters,$t, TRUE) == FALSE) {
       if (($i == $wlen - 1) && ($t =="s")) $res .= "ς";
       else {
         $x = mb_strpos($latin_letters, $t);
         if ($x !== FALSE) $res .= mb_substr($greek_letters,$x,1); else $res .= $t;
       }
     } else {
       $res .=$t;
     }
   }
   return $res;
}

function change_to_latin($word) {
   $res = "";
   mb_internal_encoding("UTF-8");
   $greek_letters = "αβγδεζηθικλμνξοπρστυφχψωςΑΒΓΔΕΖΗΘΙΚΛΜΝΞΟΠΡΣΤΥΦΧΨΩ";
   $latin_letters = "abgdezhuiklmnjoprstyfxcvwABGDEZHUIKLMNJOPRSTYFXCV";
   $wlen = mb_strlen($word);
   for ($i = 0; $i < $wlen; $i++) {
     $t =  mb_substr($word,$i,1);
     if (mb_strstr($latin_letters,$t, TRUE) == FALSE) {
       $x = mb_strpos($greek_letters, $t);
       if ($x !== FALSE) $res .= mb_substr($latin_letters,$x,1); else $res .= $t;
     } else {
       $res .=$t;
     }
   }
   return $res;
}
?>
