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
<textarea rows='50' cols='140' name='response_box' id='response_box'></textarea>
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
