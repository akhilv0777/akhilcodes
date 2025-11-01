$(function () {
  $('#contactForm').on('submit', function (e) {
    e.preventDefault();
    let isValid = true;
    $('#contactForm input').each(function () {
      $(this).toggleClass('is-invalid', !$(this).val().trim())
        .toggleClass('is-valid', !!$(this).val().trim());
      if (!$(this).val().trim()) isValid = false;
    });
    if (!isValid) return alert('Please fill in all required fields.');
    $.ajax({
      url: 'admin/code/contact-form.php',
      type: 'POST',
      data: $(this).serialize(),
      dataType: 'json',
      success: function (res) {
        alert(res.status === 'success' ? res.message : 'Failed to add contact. Please try again.');
        if (res.status === 'success') {
          $('#contactForm').trigger('reset').find('input').removeClass('is-valid');
        }
      },
      error: function (xhr, status, error) {
        console.error('AJAX error:', status, error);
        alert('An unexpected error occurred. Please try again later.');
      }
    });
  });

  fetch('admin/code/homepage.php')
    .then(response => response.json())
    .then(data => {
      $('#email').text(data.email);
      $('#headerName').text(data.username);
    })
    .catch(error => {console.error('Error:', error);
  });

});