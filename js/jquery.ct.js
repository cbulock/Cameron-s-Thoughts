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
   return $.parseJSON($.ajax({
    type: type,
    async: false,
    url: url,
    data: opt,
    dataType: 'json'
   }).responseText);
  },

  //Entries
  getEntry : function(id, opt) {
   return this.apiClient('getEntry',[id],opt);
  },
  prevEntry : function(id, opt) {
   return this.apiClient('prevEntry',[id],opt);
  },
  nextEntry : function(id, opt) {
   return this.apiClient('nextEntry',[id],opt);
  },
  lastEntry : function(opt) {
   return this.apiClient('lastEntry',NULL,opt);
  },

  //Comments
  commentCount : function(postid, opt) {
   return this.apiClient('commentCount',[postid],opt);
  },
  getComments : function(postid, opt) {
   return this.apiClient('getComments',[postid],opt);
  },
  postComment : function(postid, opt) {
   return this.apiClient('postComment',[postid],opt);
  },

  //Categories
  getCatID : function(entryid, opt) {
   return this.apiClient('getCatID',[entryid],opt);
  },
  getCat : function(catid, opt) {
   return this.apiClient('getCat',[catid],opt);
  },

  //Authentication
  getUser : function(value, opt) {
   return this.apiClient('getUser',[value],opt);
  },
  login : function(opt) {
   return this.apiClient('getCat',NULL,opt);
  },
  logout : function() {
   return this.apiClient('logout');
  },
  getAuthUser : function() {
   return this.apiClient('getAuthUser');
  },

 };
})(jQuery);
