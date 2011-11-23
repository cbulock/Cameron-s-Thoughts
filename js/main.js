$(document).ready(function() {
 /*Menu stuff*/
 $('nav ul li ul').hide();
 $('nav ul li').hover(function () {
  $(this).find('> ul').stop(true, true).slideDown('slow');
 }, function() {
  $(this).find('> ul').stop(true, true).slideUp('slow'); 	
 });
 /*other listeners*/
 postCommentListener();
 /*click listeners*/
 for (i in clickListeners) {
  $('#'+i).click(function(event) {
   event.preventDefault();
   clickListeners[this.id]();
  });
 }
 /*pageStyling*/
 $('#searchbox button').button({icons:{primary:'ui-icon-search'},text:false});
 autoResize();
 roundedAvatars();
 HTMLNotices();
 /*Facebook*/
 FB.init({
  appId : $('#fb-root').attr('appid'),
  cookie : true,
  xfbml : true
 });
 FB.getLoginStatus(function(response) {
  if (response.status == 'notConnected') {
   $('#signup').hide();
   $('#fb_login').show();
  }
  if (response.status == 'connected') {
   if (!call('getAuthUser')) {
    FB.api('/me', function(response) {
     opt = {pass: response.email};
     if(call('login',['fb_'+response.id],opt)) {
      location.reload();
     }
    });
   }
   else {
    $('#fb_login').show();
    $('#logout').hide();
    $('#fb_logout').click(function(){
     FB.logout(function() {
      if (call('logout')) {
       location.reload();
      }
     });
    });
   }
  }
 });
});

throbber = ({
 show : function() {
  if ($('#throbber').length===0) {
   $('body').prepend('<div id="throbber"></div>');
   $.ct.spinner("throbber", 18, 30, 8, 8, "#fff");
  }
  $('#throbber').show();
 },
 hide : function() {
  $('#throbber').hide();
 }
});

clickListeners = ({
 login : function() {
  show.loginBox();
 },
 signup : function() {
  show.signupForm();
 },
 comment_login : function() {
  show.loginBox();
 },
 logout : function() {
  call('logout');
  location.reload();
 },
 contact : function() {
  show.contactForm();
 }
});

show = ({
 facebookSignup : function() {
  if (!$.ct.facebook_signup) {
   snippetLoad('facebook_signup', function() {
    $.ct.facebook_signup = $('<div></div>').html(arguments[0]);
    $.ct.facebook_signup.dialog({
     title: "Create Account",
     height: 350,
     width: 510,
     hide: 'highlight',
     modal: true,
     close: function() {
      $(this).dialog('destroy');
      delete $.ct.facebook_signup;
      $('#facebook_signup').remove();
     }
    });
    FB.XFBML.parse();
   });
  }
 },
 signupForm : function() {
  if (!$.ct.signup_form) {
   snippetLoad('signup', function() {
    $.ct.signup_form = $('<div></div>').html(arguments[0]);
    $.ct.signup_form.dialog({
     title: "Create Account",
     height: 415,
     width: 400,
     hide: 'highlight',
     modal: true,
     buttons: {
      'Sign Up': function() {
       if ($('#pass').attr('value')==$('#pass2').attr('value')){
        opt = {
         pass : $('#pass').attr('value'),
         name : $('#fullname').attr('value'),
         email : $('#email').attr('value'),
         url : $('#url').attr('value')
        };
        if(call('createUser',[$('#username').val()],opt)) {
         if(call('login',[$('#username').val()],opt)) {
          location.reload();
         }
        }
       }
       else {
        error.add('Passwords do not match!');
       }
      }
     },
     close: function() {
      $(this).dialog('destroy');
      delete $.ct.signup_form;
      $('#signup_form').remove();
     }
    });
   });
  }
 },
 loginBox : function() {
  if (!$.ct.login_box) {
   snippetLoad('login', function() {
    signup = function() {
     $.ct.login_box.dialog('close');
     show.signupForm();
    };
    $.ct.login_box = $('<div></div>').html(arguments[0]);
    $.ct.login_box.dialog({
     title: 'Login',
     height: 260,
     width: 360,
     hide: 'highlight',
     modal: true,
     buttons: {
      'Signup': function() {
       signup();
      },
      'Login': function() {
       opt = {pass: $('#password').attr('value')};
       if(call('login',[$('#username').val()],opt)) {
        location.reload();
       }
      }
     },
     close: function() {
      $(this).dialog('destroy');
      delete $.ct.login_box;
      $('#login_box').remove();
     }
    });
    $('#login_box a').click(function(event) {
     event.preventDefault();
     signup();
    });
   });
  }
 },
 contactForm : function() {
  snippetLoad('contact', function() {
   $.ct.contact_form = $('<div></div>').html(arguments[0]);
   $.ct.contact_form.dialog({
    title: 'Contact Form',
    height: 415,
    width: 750,
    hide: 'highlight',
    modal: true,
    buttons: {
     'Send': function() {
      opt = {
       name : $('#contact_name').val(),
       email : $('#contact_email').val(),
       message : $('#contact_message').val()
      };
      if(call('sendMessage',null,opt)) {
       $(this).dialog('close');
       info.add('Message successfully sent');
      }
     }
    },
    close: function() {
     $(this).dialog('destroy');
     delete $.ct.contact_form;
     $('#contact_form').remove();
    }
   });
  });
 }
});


function postCommentListener() {
 $('#comment_form').submit(function(event) {
  event.preventDefault();
  $('#comment_submit').attr('disabled','disabled');
  opt = {text: $('#comment_text').val()};
  comment = call('postComment',[$('#postid').val()],opt);
  if(comment) {
   $('#comment_submit').fadeOut();
   $('#leave_comment').slideUp();
   snippetLoad('comment_footer',function() {
    $('#new_comment .comment_body').after(arguments[0]);
   },{'comment':comment.id});
   $('#comments').html(call('commentCountText',comment.count));
   info.add('Comment saved');
  }
 });
}

function snippetLoad(snip, callback, options) {
 throbber.show();
 $.ajax({
  url: '/snip/'+snip,
  data: options,
  dataType: 'html',
  success: function(data) {
   callback(data);
   throbber.hide();
  }
 });
}

function autoResize() {
 $('.comment_body textarea').autoResize({
  extraSpace : 0
 });
}

function roundedAvatars() {
 $(".avatar").load(function() {
  $(this).wrap(function(){
   return '<span class="' + $(this).attr('class') + '" style="background:url(' + $(this).attr('src') + ') no-repeat center center; width: ' + $(this).width() + 'px; height: ' + $(this).height() + 'px;" />';
  });
  $(this).css("opacity","0");
 });
}

notice = ({
 list : {
  error : [],
  warn : [],
  info : []
 },
 title : {
  error : 'Error!',
  warn : 'Warning:',
  info : ''
 },
 counter : 0,
 showing : false,
 add : function(message,type) {
  this.list[type].push(message);
  if (!this.showing) {
   this.showing = true;
   this.load();
  }
  else {
   this.update();
  }
 },
 load : function(type) {
  if ($('#notice_box').length===0) {
   snippetLoad('notice_box', function() {
    $('body').prepend(arguments[0]);
    $('#notice_close').button({icons:{primary:'ui-icon-circle-close'},text:true});
    $('#notice_prev').button({icons:{primary:'ui-icon-circle-triangle-w'},text:false});
    $('#notice_next').button({icons:{primary:'ui-icon-circle-triangle-e'},text:false});
    $('#notice_close').click(function(){
     $('#notice_box').remove();
     notice.showing = false;
     notice.clearList();
    });
    $('#notice_prev').click(function(){
     notice.prev();
    });
    $('#notice_next').click(function(){
     notice.next();
    });
    $('#notice_box').slideDown();
    notice.update();
   });
  }
  else {
   this.update();
  }
 },
 update : function() {
  this.counter = 1;
  this.display();
 },
 display : function() {
  c = this.current();
  counts = this.counts();
  $('#notice_nav').hide();
  if (counts.all > 1) {
   $('#notice_nav').show();
   $('#notice_count').html(this.counter+'/'+counts.all);
  }
  $('#notice_box').attr('class',c.type);
  $('#notice_box h1').html(notice.title[c.type]);
  $('#notice_box p').html(c.message);
 },
 current : function () {
  list = notice.get();
  e = list.error;
  w = list.warn;
  i = list.info;
  c = this.counter;
  if (c <= e.length) {
   return {
    type : 'error',
    message : e[c - 1]
   };
  }
  if ((c > e.length) && (c <= e.length + w.length)) {
   return {
    type : 'warn',
    message : w[c - e.length - 1]
   };
  }
  return {
   type: 'info',
    message : i[c - e.length - w.length - 1]
  };
 },
 prev : function() {
  if (this.counter > 1) {
   this.counter--;
   this.display();
  }
 },
 next : function() {
  counts = this.counts();
  if (this.counter < counts.all) {
   this.counter++;
   this.display();
  }
 },
 get : function() {
  return this.list;
 },
 counts : function() {
  list = this.get();
  lengths = {
   error : list.error.length,
   warn : list.warn.length,
   info : list.info.length
  };
  lengths.all = lengths.error + lengths.warn + lengths.info;
  return lengths;
 },
 clearList : function() {
  this.list = {
   error : [],
   warn : [],
   info : []
  };
 }
});

//Aliases to notice method
error = ({
 add : function(message) {
  notice.add(message,'error');
 }
});
warn = ({
 add : function(message) {
  notice.add(message,'warn');
 }
});
info = ({
 add : function(message) {
  notice.add(message,'info');
 }
});

function call(method,req,opt) {
 req = req || null;
 opt = opt || null;
 try {
  return $.ct.call(method,req,opt);
 }
 catch(e) {
  exception_handler(e);
 }
}

function HTMLNotices() {
 types = (['error', 'warn', 'info']);
 for (i in types) {
  $('.'+types[i]).each(function () {
   $(this).hide();
   notice.add($(this).html(),types[i]);
  });
 }
}

function exception_handler(e) {
 if(!e.message) {
  e = {name:0, message:e};
 }
 if (e.name != 2000) {//ignore API errors until the kinks are worked out with FALSE returns
  error.add(e.message);
 }
 switch(e.name) {
  case 401: //authentication failure
   show.loginBox();
   break;
  case 1001: //blank comment
   $('#comment_submit').attr('disabled','');
   break;
 }
}
