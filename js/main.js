$(document).ready(function() {
 initListeners();
 snippetPreload(['login_box']);
 autoResize();
 pageStyling()
});

function initListeners() {
 loginListener();
 logoutListener();
 commentLoginListener();
 postCommentListener();
}

function pageStyling() {
 roundedAvatars();
}

function loginListener() {
 $('#login').click(function(event) {
  event.preventDefault();
  if ($('#login_box').length==0) {
   $('html').prepend($.ct.snip.login_box);
   $('#login_box').slideDown();
   loginboxListener();
  }
 });
}

function commentLoginListener() {
 $('#comment_login').click(function(event) {
  event.preventDefault();
  $('#login').trigger('click');
  window.location.hash = '#login_box';
 });
}

function logoutListener() {
 $('#logout').click(function(event) {
  event.preventDefault();
  $.ct.logout();
  location.reload();
 });
}

function loginboxListener() {
 $('#login_form').submit(function(event) {
  event.preventDefault();
  opt = {pass: $('#password').attr('value')};
  try {
   if($.ct.login($('#username').val(),opt)) {
    location.reload();
   }
   else {
    throw {name:'Authentication failure', message:'Login failed.'};
   }
  }
  catch(e) {
   exception_handler(e);
  } 
 });
}

function postCommentListener() {
 $('#comment_form').submit(function(event) {
  event.preventDefault();
  $('#comment_submit').attr('disabled','disabled');
  opt = {text: $('#comment_text').val()}
  try {
   if($.ct.postComment($('#postid').val(),opt)) {
    $('#comment_submit').fadeOut();
    $('#leave_comment').slideUp();
   }
   else {
    throw {name:'System error', message:'Comment failed to save.'};
   }
  }
  catch(e) {
   exception_handler(e);
  }
 });
}

function snippetPreload(snips) {
 $.ct.snip = {};
 for (i in snips) {
  snippetLoad(snips[i]);
 }
}

function snippetLoad(snip) {
 $.ajax({
  url: '/snip/'+snip,
  dataType: 'html',
  success: function(data) {
   $.ct.snip[snip] = data;
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

function exception_handler(e) {
 alert(e.message);
}
