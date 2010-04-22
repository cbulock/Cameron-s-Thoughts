(function($){
 $.ct = {
  apiClient : function (method, req, opt) {
   if (opt) {
    type = 'POST';
   }
   else {
    type = 'GET';
   }
   url = '/api/' + method;
   for (i=0;i<=req.length-1;i++) {
    url = url+'/'+req[i];
   }
   return $.ajax({
    type: type,
    async: false,
    url: url,
    data: opt,
    dataType: 'json',
    success: function(data) {
     console.log(data);
     return data;
    }
   }).responseText;
  },

  getEntry : function(id,opt) {
   return this.apiClient('getEntry',[id],opt);
  },

  commentCount : function(id) {
   return this.apiClient('commentCount',[id]);
  }
 };
})(jQuery);
