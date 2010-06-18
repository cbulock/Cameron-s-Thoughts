<?php
require_once('../var.inc');
require_once(INCLUDE_DIR.'main.inc');
?>
<html>
<script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js'></script>
<script type='text/javascript' src='api.js'></script>
<form>
Method:
<select name='method_menu' id='method_menu'>
<option value=''></option>
<?php echo displayMethods();?>
</select><br/>
<div id='req_param_boxes'></div>
<div id='param_boxes'></div>
<input type='button' value='Send' id='submit' /><br/>
Response:<br/>
<textarea rows='20' cols='120' name='response_box' id='response_box'></textarea>
</form>
</html>


<?php
function displayMethods() {
 global $ct;
 $output = NULL;
 $methods = $ct->getAPIMethods();
 foreach ($methods as $method) {
  $output .= '<option value="'.$method['id'].'">'.$method['value'].'</option>';
 }
 return $output;
}

function jsonIndent($json) { 
 $result    = '';
 $pos       = 0;
 $strLen    = strlen($json);
 $indentStr = '  ';
 $newLine   = "\n";
 for($i = 0; $i <= $strLen; $i++) {
  // Grab the next character in the string
  $char = substr($json, $i, 1); 
  // If this character is the end of an element, 
  // output a new line and indent the next line
  if($char == '}' || $char == ']') {
   $result .= $newLine;
   $pos --;
   for ($j=0; $j<$pos; $j++) {
    $result .= $indentStr;
   }
  }
  // Add the character to the result string
  $result .= $char;
  // If the last character was the beginning of an element, 
  // output a new line and indent the next line
  if ($char == ',' || $char == '{' || $char == '[') {
   $result .= $newLine;
   if ($char == '{' || $char == '[') {
    $pos ++;
   }
   for ($j = 0; $j < $pos; $j++) {
    $result .= $indentStr;
   }
  }
 }
 return $result;
}
