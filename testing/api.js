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
    data: post,/*this doesn't seem to be taking place*/
    dataType: 'text',
    success: function(data) {
     $('#response_box').text(data);
    }
  });
 });
}
