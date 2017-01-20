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

window.showComments = function () {
  $('a[href="#comments"]').tab('show');
};

window.revealComment = function (docId) {
  // TODO: not sure the annotation subcomments were really in this format
  var noteReplyHash = window.location.hash.match(/^#annsubcomment_([0-9]+)-([0-9]+)$/);
  var noteHash = window.location.hash.match(/^#annotation_([0-9]+)$/);
  var commentHash = window.location.hash.match(/^#comment_([0-9]+)-?([0-9]+)?$/);
  var hash = jQuery.Deferred();
  var lookupNewId = function (oldId) {
    return $.get('/documents/'+docId+'/comments/'+oldId, null, null, 'json')
       .done(function (data) {
         hash.resolve(data.id);
       });
  };

  hash.done(function (id) {
    // look in comment pane for hash
    var comments = $('#comments').find('#'+id);
    if (comments.length) {
      showComments();
      comments[0].scrollIntoView();
    }

    var noteHighlight = $('#content').find('#'+id);
    if (noteHighlight.length) {
      noteHighlight[0].scrollIntoView();
    }

    // TODO: signal annotation plugin to look for hash (in the main document content?)
  });

  if (commentHash) {
    // TODO if commentHash[2] is set, is that our real target?
    lookupNewId(commentHash[1]);
  } else if (noteHash) {
    lookupNewId(noteHash[1]);
  } else if (noteReplyHash) {
    lookupNewId(noteReplyHash[1]);
  } else {
    hash.resolve(window.location.hash.slice(1));
  }
};
