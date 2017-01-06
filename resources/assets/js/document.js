window.loadAnnotations = function (elem, docId) {
  var ann = $(elem).annotator();
  ann.annotator('addPlugin', 'Unsupported');
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
