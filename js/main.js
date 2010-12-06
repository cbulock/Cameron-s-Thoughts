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
 contactFormListener();

 /*pageStyling*/
 autoResize();
 roundedAvatars();

});

//snipper() from http://raphaeljs.com/spin-spin-spin.html
function spinner(holderid, R1, R2, count, stroke_width, colour) {
 var sectorsCount = count || 12,
 color = colour || "#fff",
 width = stroke_width || 15,
 r1 = Math.min(R1, R2) || 35,
 r2 = Math.max(R1, R2) || 60,
 cx = r2 + width,
 cy = r2 + width,
 r = Raphael(holderid, r2 * 2 + width * 2, r2 * 2 + width * 2),
                    
 sectors = [],
 opacity = [],
 beta = 2 * Math.PI / sectorsCount,
 
 pathParams = {stroke: color, "stroke-width": width, "stroke-linecap": "round"};
 Raphael.getColor.reset();
 for (var i = 0; i < sectorsCount; i++) {
  var alpha = beta * i - Math.PI / 2,
  cos = Math.cos(alpha),
  sin = Math.sin(alpha);
  opacity[i] = 1 / sectorsCount * i;
  sectors[i] = r.path([["M", cx + r1 * cos, cy + r1 * sin], ["L", cx + r2 * cos, cy + r2 * sin]]).attr(pathParams);
  if (color == "rainbow") {
   sectors[i].attr("stroke", Raphael.getColor());
  }
 }
 var tick;
 (function ticker() {
  opacity.unshift(opacity.pop());
  for (var i = 0; i < sectorsCount; i++) {
   sectors[i].attr("opacity", opacity[i]);
  }
  r.safari();
  tick = setTimeout(ticker, 1000 / sectorsCount);
 })();
 return function () {
  clearTimeout(tick);
  r.remove();
 };
}

throbber = ({
 show : function() {
  if ($('#throbber').length==0) {
   $('body').prepend('<div id="throbber"></div>');
   spinner("throbber", 18, 30, 8, 8, "#fff");
  }
  $('#throbber').show();
 },
 hide : function() {
  $('#throbber').hide();
 }
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

function contactFormListener() {
 $('#contact').click(function(event) {
  event.preventDefault();
  showContactForm();
 });
}

function showContactForm() {
 if ($('#contact_form').length==0) {
  snippetLoad('contact_form', function() {
   $('body').prepend(arguments[0]);
   $('#contact_form').slideDown();
   contactFormBoxListener();
   window.location.hash = '#contact_form';
  });
 }
}

function contactFormBoxListener() {
 $('#contact').submit(function(event){
   event.preventDefault();
  opt = {
   name : $('#contact_name').val(),
   email : $('#contact_email').val(),
   message : $('#contact_message').val()
  };
  if(call('sendMessage',null,opt)) {
   $('#contact_form').slideUp();
   window.location.hash = '';
  }
 });
}

function snippetLoad(snip, callback) {
 throbber.show();
 $.ajax({
  url: '/snip/'+snip,
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
