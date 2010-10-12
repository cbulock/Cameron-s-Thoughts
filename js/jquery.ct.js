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
  },

  //Entries
  getEntry : function(id, opt) {
   return this.apiClient('getEntry',[id],opt);
  },
  postEntry : function(opt) {
   return this.apiClient('postEntry',null,opt);
  },
  prevEntry : function(id, opt) {
   return this.apiClient('prevEntry',[id],opt);
  },
  nextEntry : function(id, opt) {
   return this.apiClient('nextEntry',[id],opt);
  },
  lastEntry : function(opt) {
   return this.apiClient('lastEntry',null,opt);
  },
  getNewEntries : function(opt) {
   return this.apiClient('getNewEntries',null,opt);
  },
  getMonthlyEntries : function(month,year,opt) {
   return this.apiClient('getMonthlyEntries',[month],[year],opt);//don't think this will work, the reqs should be one array
  },

  //Comments
  commentCount : function(postid, opt) {
   return this.apiClient('commentCount',[postid],opt);
  },
  commentCountText : function(count, opt) {
   return this.apiClient('commentCountText',[count],opt);
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
  getCatEntries : function(catid, opt) {
   return this.apiClient('getCatEntries',[catid],opt);
  },

  //Authentication
  getUser : function(value, opt) {
   return this.apiClient('getUser',[value],opt);
  },
  login : function(username, opt) {
   return this.apiClient('login',[username],opt);
  },
  logout : function() {
   return this.apiClient('logout');
  },
  getAuthUser : function() {
   return this.apiClient('getAuthUser');
  },

  //Status
  getLatestStatus : function() {
   return this.apiClient('getLatestStatus');
  }

 };
})(jQuery);
