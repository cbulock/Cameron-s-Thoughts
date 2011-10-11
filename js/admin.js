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
 editComment : function() {
  commentData = {
   text : $('#commentText').attr('value')
  };
  if (call('editComment',[$('#commentId').attr('value')],commentData)) info.add('Comment Saved');
 },
 deleteComment : function() {
  if (confirm("Delete comment?")) {
   if (call('deleteComment',[$('#commentId').attr('value')])) info.add('Comment Deleted');
  }
 }
});

function autoResize() {
 $('textarea.smallSpace').autoResize({
  extraSpace : 0,
  minHeight: 100
 });
  $('textarea.largeSpace').autoResize({
  extraSpace : 0,
  minHeight: 200
 });
}
