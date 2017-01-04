$(function () {
  // For laracasts/flash
  $('#flash-overlay-modal').modal();
  $('div.alert').not('.alert-important').delay(3000).fadeOut(350);
});

function sponsorVerificationStatusChange(id, oldStatus, event) {
  $.ajax({
    url: '/sponsors/'+id+'/status',
    method: 'PUT',
    data: { status: event.target.value, _token: window.Laravel.csrfToken }
  })
    .done(function (data, textStatus) {
      $('div.page-header').after('<div class="alert alert-success">Status updated</div>')
    })
    .fail(function () {
      // reset select to previous status
      event.target.value = oldStatus;
      $('div.page-header').after('<div class="alert alert-danger">Status Change Failed</div>')
    })
    .always(function () {
      $('div.alert').not('.alert-important').delay(3000).fadeOut(350);
    })
}
