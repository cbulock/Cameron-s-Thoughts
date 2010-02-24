<?php
require_once('../var.inc');
require_once(INCLUDE_DIR.'main.inc');
?>
<html>
<script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js'></script>
<script type='text/javascript' src='api.js'></script>
<form>
Method:
<select name='method' id='method'>
<option value=''></option>
<?php echo displayMethods();?>
</select><br/>
<div id='param_boxes'></div>
Response:<br/>
<textarea rows='15' cols='40' name='response' id='response'></textarea>
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
