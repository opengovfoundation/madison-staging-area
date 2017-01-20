window.loadAnnotations = function (contentElem, annotationContainerElem, docId, userId, discussionClosed) {
  var ann = $(contentElem).annotator({
    readOnly: !!userId,
    discussionClosed: discussionClosed
  });
  ann.annotator('addPlugin', 'Unsupported');
  ann.annotator('addPlugin', 'Madison', {
    docId: docId,
    userId: userId,
    annotationContainerElem: annotationContainerElem
  });
  ann.annotator('addPlugin', 'Store', {
    annotationData: {
      '_token': window.Laravel.csrfToken,
      'uri': window.location.pathname,
      'comments': []
    },
    prefix: '/documents/' + docId + '/comments',
    urls: {
      create: '',
      read: '/:id?only_notes=true',
      update: '/:id',
      destroy: '/:id',
      search: '/search'
    }
  });
};

window.toggleCommentReplies = function(commentId) {
  var $commentDiv = $('#comment_' + commentId);
  var $commentReplyDiv = $commentDiv.find('.comment-replies');
  if ($commentReplyDiv) {
    $commentReplyDiv.toggleClass('hide');
  }
};

window.revealComment = function () {
  // TODO: check for old permalink format
  var subCommentHash = window.location.hash().match(/^annsubcomment_([0-9]+)-([0-9]+)$/);
  var annotationHash = window.location.hash().match(/^annotation_([0-9]+)$/);
  var commentHash = window.location.hash().match(/^comment_([0-9]+)-?([0-9]+)?$/);

  // TODO: make request to get new str_id for old permalink

  // TODO: look in comment pane for hash
  // TODO: signal annotation plugin to look for hash (in the main document content?)
}
