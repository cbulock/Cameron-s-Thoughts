$(document).ready(function() {
 /*click listeners*/
 for (i in adminClickListeners) {
  $('#'+i).click(function(event) {
   event.preventDefault();
   adminClickListeners[this.id]();
  });
 }
});

adminClickListeners = ({
 postEntry : function() {
  entryData = {
   title : $('#postTitle').attr('value'),
   text : $('#postText').attr('value'),
   category : $('#postCategory').attr('value'),
   excerpt : $('#postExcerpt').attr('value'),
   keywords : $('#postKeywords').attr('value')
  };
  if (call('postEntry',[],entryData)) info.add('Entry Posted');
 },
 test : function() {
  console.log('test');
 }
});

function autoResize() {
 $('textarea#postExcerpt').autoResize({
  extraSpace : 0,
  minHeight: 100
 });
  $('textarea#postText').autoResize({
  extraSpace : 0,
  minHeight: 200
 });
}
