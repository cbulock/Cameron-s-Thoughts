(function($){
 $.ct = {

  apiClient : function (method, req, opt) {
   type = 'GET';
   if (opt) {
    type = 'POST';
   }
   url = '/api/' + method;
   if (req) {
    for (i=0;i<=req.length-1;i++) {
     url = url+'/'+req[i];
    }
   }
   url = url+'.json';
   response = $.parseJSON($.ajax({
    type: type,
    async: false,
    url: url,
    data: opt,
    dataType: 'json'
   }).responseText);
   if (response.error) {
    throw {name:response.error_number, message:response.error};
   }
   return response;
  },

  call : function(method, req, opt) {
   req = req || null;
   opt = opt || null;
   return this.apiClient(method, req, opt);
  }

 };
})(jQuery);
