<?php require 'includes/header.php' ?>
<link rel="stylesheet" href="assets/bundles/datatables/datatables.min.css">
<section class="section">
  <div class="section-body">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h4>All Contact Enquiry</h4>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table dataTable">
                <thead>
                  <tr>
                    <th scope="col"><input type="checkbox" id="select-all"></th>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Subject</th>
                    <th scope="col">Message</th>
                    <th scope="col">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $query = "SELECT * FROM contact_form";
                  $result = $conn->query($query);
                  if ($result) :
                    $i = 1;
                    while ($row = $result->fetch_assoc()) :  ?>
                      <tr>
                        <td scope="row"><input type="checkbox" name="selected_ids[]" class="select-item" value="<?php echo $row['id']; ?>"></td>
                        <td scope="row"><?php echo $i++; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td><?php echo $row['subject']; ?></td>
                        <td><?php echo $row['message']; ?></td>
                        <td>
                          <button class="btn btn-outline-primary reply-button"
                            data-email="<?php echo $row['email']; ?>"
                            data-message_id="<?php echo $row['id']; ?>"
                            data-toggle="modal"
                            data-target="#exampleModalCenter">
                            <i class="fa fa-reply" aria-hidden="true"></i> Reply
                          </button>
                        </td>
                      </tr>
                    <?php endwhile; ?>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
            <button type="submit" name="contact_delete" value="contact_delete" class="btn btn-sm btn-danger" id="delete-btn">Delete Selected</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content ">
      <form class="composeForm" method="POST" action="code/contact_form.php" enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalCenterTitle">Reply</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="message_id" value="" id="message_id">
          <div class="form-group">
            <input type="text" id="email_address" class="form-control" readonly value="" name="to" placeholder="TO">
          </div>
          <textarea name="message" id="editor"></textarea>
          <div class="compose-editor mt-3">
            <input type="file" name="files[]" class="default" multiple>
          </div>
        </div>
        <div class="modal-footer ">
          <button type="submit" name="send_reply" class="btn btn-info btn-border-radius waves-effect">Send</button>
        </div>
        <script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>
        <script>
          ClassicEditor.create(document.querySelector('#editor'));
        </script>
      </form>
    </div>
  </div>
</div>
<?php require 'includes/footer.php' ?>
<script src="assets/bundles/datatables/datatables.min.js"></script>
<script>
  jQuery(document).ready(function($) {
    $('.dataTable').DataTable({
      "lengthMenu": [
        [10, 25, 50, -1],
        [10, 25, 50, "All"]
      ]
    });
    $('#select-all').on('click', function() {
      $('.select-item').prop('checked', this.checked);
    });
    $('#delete-btn').on('click', function(e) {
      var selectedIds = [];
      $('.select-item:checked').each(function() {
        selectedIds.push($(this).val());
      });
      if (selectedIds.length === 0) {
        alert("Please select at least one item to delete.");
        e.preventDefault();
        return;
      }
      if (confirm("Are you sure you want to delete the selected records?")) {
        $.ajax({
          url: 'code/contact_form.php',
          method: 'POST',
          data: {
            selected_ids: selectedIds,
            contact_delete: 'contact_delete'
          },
          success: function(response) {
            if (response == 'success') {
              alert('Selected records deleted successfully!');
            } else {
              alert('Error deleting records.');
            }
            location.reload();
          }
        });
      } else {
        alert('Deletion cancelled.');
      }
    });
    // Populate modal with email address
    $('.reply-button').on('click', function() {
      var email = $(this).data('email'); // Get email from button
      $('#email_address').val(email); // Set email to modal input
      var message_id = $(this).data('message_id'); // Get email from button
      $('#message_id').val(message_id); // Set email to modal input
    });
  });
</script>