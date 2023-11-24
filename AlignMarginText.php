<?php
/**
 * Clase que agrega margen y define longitud de un texto alineado a la izquierda
 * Soporte para PHP >= 5.4
 * @authorLuis Aguilar
 *
 */

class AlignMarginText
{

  public static function addspleft($input,$width,$caracter = " "){
      if($width<=0){
      $width=1;
    }
      $len_txt=strlen(utf8_decode($input));
      if($len_txt==0){
        $len_txt=1;
      }
      $diff=$width-($len_txt+1);
      if($diff<=0){
        $diff=0;
      }
      if($len_txt<=$width){
        $texto_repeat=str_repeat($caracter,$diff);
        $texto_salida=$texto_repeat.$input.$caracter;
      }else{
        $texto_salida=substr($input,0,$width).$caracter;
      }
      return $texto_salida;
  }
  public static function addspright($input,$width,$caracter = " "){
        if($width<=0){
          $width=1;
        }
      $len_txt=strlen(utf8_decode($input));
      if($len_txt==0){
      $len_txt=1;
      }
      $diff=$width-($len_txt);
      if($diff<=0){
        $diff=0;
      }
    	if($len_txt<=$width){
        $texto_repeat=str_repeat($caracter,$diff);
        $texto_salida=$input.$texto_repeat;
      }else{
    		$texto_salida=substr($input,0,$width);
    	}
      return $texto_salida;
  }
  public static function addspcent($input,$width,$caracter = " "){
    if($width<=0){
      $width=1;
    }
      $len_txt=strlen(utf8_decode($input));
    $diff=$width-$len_txt;
    if($diff<=0){
      $diff=0;
    }
    if($len_txt<=$width){
      $texto_salida=str_pad($input, $width, $caracter, STR_PAD_BOTH);;
    }else{
      $texto_salida=substr($input,0,$width);
    }
    return $texto_salida;

  }

	private static function setmargin($texto,$caracter = " ",$long,$margin){
		//$len_txt=strlen($texto);
		  $len_txt=strlen(utf8_decode($input));
		$texto_repeat=str_repeat($caracter,$margin);
		$texto_salida=$texto_repeat.$texto;
		return $texto_salida;
	}



  public static  function wordwrap1( $text, $width = '80',  $break = '\n', $lines = '10', $cut = 0 ) {
          $wrappedarr = array();
          $wrappedtext = wordwrap( $text, $width, $break , false );
          $wrappedtext = trim( $wrappedtext );
          $arr = explode( $break, $wrappedtext );
          return $arr;
          //return an array
  }
  public static function wordwrap2($string, $width = 80, $break = "\n") {
    // split on problem words over the line length
    $pattern = sprintf('/([^ ]{%d,})/', $width);
    $output = '';
    $words = preg_split($pattern, $string, -1, PREG_SPLIT_NO_EMPTY |     PREG_SPLIT_DELIM_CAPTURE);
      foreach ($words as $word) {
          if (false !== strpos($word, ' ')) {
              // normal behaviour, rebuild the string
              $output .= $word;
          } else {
              // work out how many characters would be on the current line
              $wrapped = explode($break, wordwrap($output, $width, $break));
              $count = $width - (strlen(end($wrapped)) % $width);

              // fill the current line and add a break
              $output .= substr($word, 0, $count) . $break;

              // wrap any remaining characters from the problem word
              $output .= wordwrap(substr($word, $count), $width, $break, true);
          }
      }
      //wrap over lines too long
      return $output;
  }

  public static function wordwrap3($input, $chars, $lines = false)
  {
    # the simple case - return wrapped words
    if(!$lines) return wordwrap($input, $chars, "\n");

    # truncate to maximum possible number of characters
    $retval = substr($input, 0, $chars * $lines);

    # apply wrapping and return first $lines lines
    $retval = wordwrap($retval, $chars, "\n");
    preg_match("/(.+\n?){0,$lines}/", $retval, $regs);
    return $regs[0];
    //limit the number of lines
  }
  public static function multiCol($string, $numcols)
  {
    $collength = ceil(strlen($string) / $numcols) + 3;
    $retval = explode("\n", wordwrap(strrev($string), $collength));
    if(count($retval) > $numcols) {
      $retval[$numcols-1] .= " " . $retval[$numcols];
      unset($retval[$numcols]);
    }
    $retval = array_map("strrev", $retval);
    return array_reverse($retval);
  }

  public static function texto_espacios($texto,$long){
  	$countchars=0;
  	$countch=0;
  	$texto=trim($texto);
  	$len_txt=strlen($texto);
  	$latinchars = array( 'ñ','á','é', 'í', 'ó','ú','Ñ','Á','É','Í','Ó','Ú');
      foreach($latinchars as $value){
  		$countchars=substr_count($texto,$value);
          $countch= $countchars+$countch;
      }

  	if($len_txt<=$long){
  	 if($countch>0)
  		$n=($long+$countch)-$len_txt;
  	 else
  		$n=$long-$len_txt;

  		$texto_repeat=str_repeat(" ",$n);
  		$texto_salida=$texto.$texto_repeat;
  	}
  	else{
  		$long=$long-1;
  		$texto_salida=substr($texto,0,$long).".";
  	}
  	return $texto_salida;
  }
  public static function onelineleft( $text, $width = '80',$margin=0,  $caracter = " ",$break = '\n' ) {
     $text=substr($text,0,$width);
     $out=self::addspright($text,$width,$caracter);
     return  $out;
  }
  public static function leftmargin($caracter = " ",$margin){
  $texto_salida=str_repeat($caracter,$margin);
  return $texto_salida;
}
public static function rightalign($input,$caracter = " ",$width){
  return str_pad($input, $width, $caracter, STR_PAD_LEFT);
}
public static function rightaligner($input,$caracter = " ",$width){
    $len_txt=strlen($input);
    $diff=$width-$len_txt;
    if($len_txt<=$width){
      $texto_repeat=str_repeat($caracter,$diff);
      $texto_salida=$texto_repeat.$input;
    }else{
      $texto_salida=substr($input,0,$width);
    }
    return $texto_salida;
}
public static  function utf8_wordwrap($string, $width=75, $break="\n", $cut=false)
{
  if($cut) {
    // Match anything 1 to $width chars long followed by whitespace or EOS,
    // otherwise match anything $width chars long
    $search = '/(.{1,'.$width.'})(?:\s|$)|(.{'.$width.'})/uS';
    $replace = '$1$2'.$break;
  } else {
    // Anchor the beginning of the pattern with a lookahead
    // to avoid crazy backtracking when words are longer than $width
    $pattern = '/(?=\s)(.{1,'.$width.'})(?:\s|$)/uS';
    $replace = '$1'.$break;
  }
  return preg_replace($search, $replace, $string);
}
public static function pdfWrapSplit($text, $lines, $firstWidth, $secondWidth)
{
    $text = wordwrap($text, $firstWidth, "|");
    $lastPos = 1;
    for ($i=0;$i<$lines;$i++)
    {
        $lastPos = strpos($text, '|', $lastPos+1);
        if ($lastPos === FALSE)
            break;
    }
    $text = substr($text, 0, $lastPos) . "|" . wordwrap(str_replace('|',' ',substr($text, $lastPos)), $secondWidth, '|');
    $new = explode('|', $text);
    return $new;
}
/**
 * wordwrap for utf8 encoded strings
 *
 * @param string $str
 * @param integer $len
 * @param string $what
 * @return string
 * @author Milian Wolff <mail@milianw.de>
 */

public function utf8_wordwrap2($str, $width, $break = '\n', $cut = false) {
    if (!$cut) {
        $regexp = '#^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){'.$width.',}\b#U';
    } else {
        $regexp = '#^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){'.$width.'}#';
    }
    if (function_exists('mb_strlen')) {
        $str_len = mb_strlen($str,'UTF-8');
    } else {
        $str_len = preg_match_all('/[\x00-\x7F\xC0-\xFD]/', $str, $var_empty);
    }
    $while_what = ceil($str_len / $width);
    $i = 1;
    $return = '';
    while ($i < $while_what) {
        preg_match($regexp, $str,$matches);
        $string = $matches[0];
        $return .= $string.$break;
        $str = substr($str, strlen($string));
        $i++;
    }
    return $return.$str;
}

}
?>
