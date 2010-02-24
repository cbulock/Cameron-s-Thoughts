$(document).ready(function() {
 $.ct = {};
 $.ct.apilocation = '../api/';
 methodSelect();
 sendAPI();
});

function methodSelect() {
 $('#method').change(function() {
  $.ajax({
   url: $.ct.apilocation+'getMethodParameters/'+$('#method').attr('value')+'.json',
   dataType: 'json',
   success: function(data) {
    for (key in data) { 
     $(addParam(data[key])).appendTo('#param_boxes');
    }
   }
  }); 
 });
}

function addParam(param) {
 output = '<label for="param'+param.id+'">'+param.value+': <input id="'+param.value+'" />';
 if (param.required == 1) {
  output = output+'*';
 }
 output = output+'</label><br/>';
 return output;
}

function sendAPI() {
 $('#submit').click(function() {
  url = $.ct.apilocation;
  url = url+$('#method').attr('value')+'/';
  if  ($('#roption1').attr('value').length != 0) {
   url = url+ $('#roption1').attr('value')+'/';
  }
  if  ($('#roption2').attr('value').length != 0) {
   url = url+ $('#roption2').attr('value')+'/';
  }
 alert(url);
//  $.post
 });
}
