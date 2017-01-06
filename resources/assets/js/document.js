window.loadAnnotations = function (elem, docId, userId, discussionClosed) {
  var ann = $(elem).annotator({
    readOnly: !!userId,
    discussionClosed: discussionClosed
  });
  ann.annotator('addPlugin', 'Unsupported');
  ann.annotator('addPlugin', 'Madison', {
    docId: docId,
    userId: userId
  });
  ann.annotator('addPlugin', 'Store', {
    annotationData: {
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
