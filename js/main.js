$(document).ready(function() {
 initListeners();
 snippetPreload(['login']);
});

function initListeners() {
 loginListener();
 logoutListener();
}

function loginListener() {
 $('#login').click(function(event) {
  event.preventDefault();
  $("html").prepend($.ct.snip.login);
  $("#login_box").slideDown();
 });
}

function logoutListener() {
 $('#logout').click(function(event) {
  event.preventDefault();
  $.ct.logout();
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
