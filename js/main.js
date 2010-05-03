$(document).ready(function() {
 initListeners();
 snippetPreload(['login']);
});

function initListeners() {
 login();
 logout();
}

function login() {
 $('#login').click(function(event) {
  console.log('login');
  event.preventDefault();
  //login code goes here
  location.reload();
 });
}

function logout() {
 $('#logout').click(function(event) {
  console.log('logout');
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
