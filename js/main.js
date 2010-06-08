$(document).ready(function() {
 initListeners();
 snippetPreload(['login_box']);
 autoResize();
});

function initListeners() {
 loginListener();
 logoutListener();
 commentLoginListener();
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
  if($.ct.login($('#username').val(),opt)) {
   location.reload();
  }
  else {
   $('#login_alert').html('Login failed');
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
