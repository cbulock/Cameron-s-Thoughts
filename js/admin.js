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
 editEntry : function() {
  entryData = {
   entry_title : $('#postTitle').attr('value'),
   entry_text : $('#postText').attr('value'),
   entry_category_id : $('#postCategory').attr('value'),
   entry_excerpt : $('#postExcerpt').attr('value'),
   entry_keywords : $('#postKeywords').attr('value')
  };
  if (call('editEntry',[$('#entryId').attr('value')],entryData)) info.add('Entry saved');
 },
 editComment : function() {
  commentData = {
   text : $('#commentText').attr('value')
  };
  if (call('editComment',[$('#commentId').attr('value')],commentData)) info.add('Comment saved');
 },
 deleteComment : function() {
  if (confirm("Delete comment?")) {
   if (call('deleteComment',[$('#commentId').attr('value')])) info.add('Comment deleted');
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
