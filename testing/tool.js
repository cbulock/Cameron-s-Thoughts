$(document).ready(function() {
  getMethods();
	$('#tabs').tabs();
	$("#send").button();
	$("#add_args").button({icons:{primary:'ui-icon-plusthick'},text:false});
	$('#add_args').click(function(event){
		event.preventDefault();
		addArgs();
	});
	$("#tab-1").bind("keypress", function(event) {
		if (event.keyCode == 13) {
			send();
			return false;
		}
	});
	sendlistener();
});

function getMethods() {
	methods = $.ct.call('getAPIMethods');
	for (i in methods) {
		val = methods[i].value;
		id = methods[i].id;
		$('#method').append('<option value="'+val+'" method="'+id+'">'+val+'</option>');
	}
	$('#method').change(function() {
		loadArgs();
	});
}

function loadArgs() {
	$('.args').remove();
	args = $.ct.call('getMethodParameters',[$('#method option:selected').attr('method')]);
	for (i in args) {
		addArgs(args[i].value,args[i].required);
	} 
}

function snipLoad(snip, callback) {
	$.ajax({
		url: 'snip-'+snip+'.html',
		dataType: 'html',
		success: function(data) {
			callback(data);
		}
	});
}

function addArgs(param,req) {
	var param = param || null;
	var req = req || null;
	snipLoad('args',function() {
		$('#add_args').before(arguments[0]);
		$(".current .param").val(param);
		if (req) {
			$(".current .req").val(req);
		}
		$(".remove").button({icons:{primary:'ui-icon-minusthick'},text:false});
		$(".remove").click(function(event) {
			event.preventDefault();
			$(this).parent().remove();
		})
		$(".value").unbind('keypress');
		$(".value").bind('keypress', function() {
			if ($("#args .param").last().val() != '') {
				addArgs();
			}
		});
		$(".current").removeClass('current');
	});
}

function sendlistener() {
	$('#send').click(function(event){
		event.preventDefault();
		send();
	});
}

function send() {
	$('#results').text('Loading...');
	var req = [];
	var opt = {};
	$('.args').each(function(){
		if ($(this).children('.param').val() != '') {
			if ($(this).children('.req').val() == '0') {
				opt[$(this).children('.param').val()] = $(this).children('.value').val();
			}
			else {
				req.push($(this).children('.value').val());
			}
		}
	});
  try {
	 result = $.ct.call($('#method').val(),req,opt);
	 if (RealTypeOf(result) == 'object') {
		 $('#results').text(FormatJSON(result));
	 }
	 else {
		 $('#results').text(result);
	 }
  }
  catch(e) {
   $('#results').text('Error');
   alert('Error: '+e.message+'\nNumber: '+e.name);
  }
}

//borrowed from http://joncom.be/code/javascript-json-formatter/
function FormatJSON(oData, sIndent) {
 if (arguments.length < 2) {
  var sIndent = "";
 }
 var sIndentStyle = "  ";
 var sDataType = RealTypeOf(oData);
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
