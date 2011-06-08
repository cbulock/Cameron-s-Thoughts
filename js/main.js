$(document).ready(function() {
 /*Menu stuff*/
 $('ul.sub_nav').hide();
 $('ul.main_nav li').hover(function () {
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
 autoResize();
 roundedAvatars();
});

throbber = ({
 show : function() {
  if ($('#throbber').length==0) {
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
        }
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
     width: 340,
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
  opt = {text: $('#comment_text').val()}
  comment = call('postComment',[$('#postid').val()],opt)
  if(comment) {
   $('#comment_submit').fadeOut();
   $('#leave_comment').slideUp();
   snippetLoad('comment_footer',function() {
    $('#new_comment .comment_body').after(arguments[0]);
   },comment.id);
   $('#comments').html(call('commentCountText',comment.count));
  }
 });
}

function snippetLoad(snip, callback, option) {
 throbber.show();
 if (option) {
  url =  '/snip/'+snip+'/'+option;
 }
 else {
  url =  '/snip/'+snip;
 }
 $.ajax({
  url: url,
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

error = ({
 errorList : [],
 add : function(message) {
  this.errorList.push(message);
  if ($('#error_box').length==0) {
   this.showBox();
  }
 },
 showBox : function() {
  if ($('#error_box').length==0) {
   snippetLoad('error_box', function() {
    $('body').prepend(arguments[0]);
    $('#error_box button').button({icons:{primary:'ui-icon-circle-close'},text:false});
    errorList = error.get();
    if (errorList) {
     $('#error_box p').html(errorList[0]);
    }
    $('#error_box button').click(function(){
     $('#error_box').remove();
     error.clearList();
    });
    $('#error_box').slideDown();
   });
  }
 },
 get : function() {
  if (!this.errorList.length) return false;
  return this.errorList;
 },
 clearList : function() {
  this.errorList = [];
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

function exception_handler(e) {
 if(!e.message) {
  e = {name:0, message:e};
 }
 error.add(e.message);
 switch(e.name) {
  case 401: //authentication failure
   show.loginBox();
   break;
  case 1001: //blank comment
   $('#comment_submit').attr('disabled','');
   break;
 }
}
