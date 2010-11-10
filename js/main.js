$(document).ready(function() {
 /*Menu stuff*/
 $('ul.sub_nav').hide();
 $('ul.main_nav li').hover(function () {
  $(this).find('> ul').stop(true, true).slideDown('slow');
  }, function() {
  $(this).find('> ul').stop(true, true).slideUp('slow'); 	
 });

 /*listeners*/
 loginListener();
 logoutListener();
 postCommentListener();

 /*pageStyling*/
 autoResize();
 roundedAvatars();

});

function loginListener() {
 $('#login').click(function(event) {
  event.preventDefault();
  showLoginBox();
 });
 $('#comment_login').click(function(event) {
  event.preventDefault();
  showLoginBox();
 });
}

function showLoginBox() {
 if ($('#login_box').length==0) {
  snippetLoad('login_box', function() {
   $('body').prepend(arguments[0]);
   $('#login_box').slideDown();
   loginboxListener();
   $('#username').focus();
   window.location.hash = '#login_box';
  });
 }
}

function logoutListener() {
 $('#logout').click(function(event) {
  event.preventDefault();
  call('logout');
  location.reload();
 });
}

function loginboxListener() {
 $('#login_form').submit(function(event) {
  event.preventDefault();
  opt = {pass: $('#password').attr('value')};
   if(call('login',[$('#username').val()],opt)) {
    window.location.hash = '';
    location.reload();
  } 
 });
}

function postCommentListener() {
 $('#comment_form').submit(function(event) {
  event.preventDefault();
  $('#comment_submit').attr('disabled','disabled');
  opt = {text: $('#comment_text').val()}
  comment = call('postComment',[$('#postid').val()],opt)
  if(comment) {
   $('#comment_submit').fadeOut();
   $('#leave_comment').slideUp();
    $('#comments').html(call('commentCountText',comment.count));
  }
 });
}

function snippetLoad(snip, callback) {
 $.ajax({
  url: '/snip/'+snip,
  dataType: 'html',
  success: function(data) {
   callback(data);
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
 if ($('#error_box').length==0) {
  snippetLoad('error_box', function() {
   $('body').prepend(arguments[0]);
   $('#error_box button').button({icons:{primary:'ui-icon-circle-close'},text:false});
   $('#error_box p').html(e.message);
   $('#error_box button').click(function(){
    $('#error_box').remove();
   });
   $('#error_box').slideDown();
  });
 }
 switch(e.name) {
  case 401: //authentication failure
   showLoginBox();
   break;
  case 1001: //blank comment
   $('#comment_submit').attr('disabled','');
   break;
 }
}
