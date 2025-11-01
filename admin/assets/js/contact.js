jQuery(document).ready(function ($) {
  $('.dataTable').DataTable({ "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]] });

  $('#select-all').on('click', function () {
    $('.select-item').prop('checked', this.checked);
  });

  $('#delete-btn').on('click', function (e) {
    var selectedIds = [];
    $('.select-item:checked').each(function () {
      selectedIds.push($(this).val());
    });
    if (selectedIds.length === 0) {
      alert("Please select at least one item to delete.");
      e.preventDefault();
      return;
    }
    if (confirm("Are you sure you want to delete the selected records?")) {
      $.ajax({
        url: 'code/admin-contact.php',
        method: 'POST',
        data: {
          selected_ids: selectedIds,
          contact_delete: 'contact_delete'
        },
        dataType: 'json',
        success: function (response) {
          if (response.status == 'success') {
            alert(response.message);
          } else {
            alert(response.message);
          }
          location.reload();
        }
      });
    } else {
      alert('Deletion cancelled.');
    }
  });
  // Populate modal with email address
  $('.reply-button').on('click', function () {
    var email = $(this).data('email');
    $('#email_address').val(email);
    var message_id = $(this).data('message_id');
    $('#message_id').val(message_id);
  });
});