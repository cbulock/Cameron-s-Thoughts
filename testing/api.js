$(document).ready(function() {
 $.ct = {};
 $.ct.apilocation = '../api/';
 methodSelect();
 sendAPI();
});

function methodSelect() {
 $('#method_menu').change(function() {
  $.ajax({
   url: $.ct.apilocation+'getMethodParameters/'+$('#method_menu').attr('value')+'.json',
   dataType: 'json',
   success: function(data) {
    $('#req_param_boxes').empty();
    $('#param_boxes').empty();
    for (key in data) { 
     if (data[key].required == 0) {
      $(addParam(data[key])).appendTo('#param_boxes');
     }
     else {
      $(addParam(data[key])).appendTo('#req_param_boxes');
     }
    }
   }
  }); 
 });
}

function addParam(param) {
 output = '<label for="'+param.value+'">'+param.value+': <input id="'+param.value+'" name="'+param.value+'" />';
 if (param.required == 1) {
  output = output+'*';
 }
 output = output+'</label><br/>';
 return output;
}

function sendAPI() {
 var post = {};
 $('#submit').click(function() {
  req = $('#req_param_boxes').find('input');
  opt = $('#param_boxes').find('input');
  url = $.ct.apilocation;
  url = url+$('#method_menu option:selected').text();
  for (i=0;i<=req.length-1;i++) {
   url = url+'/'+req[i].value;
  }
  url = url+'.json';
  for (i=0;i<=opt.length-1;i++) {
    post[opt[i].name]=opt[i].value;
  }
  $.ajax({
    type: 'post',
    url: url,
    data: post,
    dataType: 'text',
    success: function(data) {
     try {
      if ($('#format').is(':checked')) {
       $('#response_box').text(FormatJSON(JSON.parse(data)));
      }
      else {
       $('#response_box').text(data);
      }
     }
     catch(sError) {
      $('#response_box').text('An error occurred:\n'+data);
     }
    }
  });
 });
}

//borrowed from http://joncom.be/code/javascript-json-formatter/
function FormatJSON(oData, sIndent) {
 if (arguments.length < 2) {
  var sIndent = "";
 }
 var sIndentStyle = "    ";
 var sDataType = RealTypeOf(oData);
 console.log(sDataType);
 // open object
 if (sDataType == "array") {
  if (oData.length == 0) {
   return "[]";
  }
  var sHTML = "[";
 } 
 else {
  var iCount = 0;
  $.each(oData, function() {
   iCount++;
   return;
  });
  if (iCount == 0) { // object is empty
   return "{}";
  }
  var sHTML = "{";
 }
 // loop through items
 var iCount = 0;
 $.each(oData, function(sKey, vValue) {
  if (iCount > 0) {
   sHTML += ",";
  }
  if (sDataType == "array") {
   sHTML += ("\n" + sIndent + sIndentStyle);
  } 
  else {
   sHTML += ("\n" + sIndent + sIndentStyle + "\"" + sKey + "\"" + ": ");
  }
  // display relevant data type
  switch (RealTypeOf(vValue)) {
   case "array":
   case "object":
    sHTML += FormatJSON(vValue, (sIndent + sIndentStyle));
   break;
   case "boolean":
   case "number":
    sHTML += vValue.toString();
   break;
   case "null":
    sHTML += "null";
   break;
   case "string":
    sHTML += ("\"" + vValue + "\"");
   break;
   default:
    sHTML += ("TYPEOF: " + typeof(vValue));
  }
  // loop
  iCount++;
 });
 // close object
 if (sDataType == "array") {
  sHTML += ("\n" + sIndent + "]");
 } 
 else {
  sHTML += ("\n" + sIndent + "}");
 }
 // return
 return sHTML;
}

function RealTypeOf(v) {
 if (typeof(v) == "object") {
  if (v === null) return "null";
  if (v.constructor == (new Array).constructor) return "array";
  if (v.constructor == (new Date).constructor) return "date";
  if (v.constructor == (new RegExp).constructor) return "regex";
  return "object";
 }
 return typeof(v);
}
