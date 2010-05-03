$(document).ready(function() {
 initListeners();
 snippetPreload(['login_box']);
});

function initListeners() {
 loginListener();
 logoutListener();
}

function loginListener() {
 $('#login').click(function(event) {
  event.preventDefault();
  $("html").prepend($.ct.snip.login_box);
  $("#login_box").slideDown();
  loginboxListener();
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
 $('#login_submit').click(function(event) {
  event.preventDefault();
  opt = {pass: $('#password').attr('value')};
  $.ct.login($('#username').val(),opt);
  location.reload();
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
