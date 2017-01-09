$(function () {
  // For laracasts/flash
  $('#flash-overlay-modal').modal();
  $('div.alert').not('.alert-important').delay(3000).fadeOut(350);
});

window.loadTranslations = function (msgIds) {
  return $.get('/trans', { 'msg_ids[]': msgIds })
    .done(function (data) {
      window.trans = data;
    });
};
