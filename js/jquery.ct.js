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

  //spinner() from http://raphaeljs.com/spin-spin-spin.html
  spinner : function (holderid, R1, R2, count, stroke_width, colour) {
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

 };
})(jQuery);
