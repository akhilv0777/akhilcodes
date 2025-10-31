function previewImage(event) {
  var reader = new FileReader();
  reader.onload = function () { document.getElementById('profile-image').src = reader.result; }
  reader.readAsDataURL(event.target.files[0]);
}

function validateForm() {
  const passwordError = document.getElementById('password-error').style.display === 'none';
  const confirmPasswordError = document.getElementById('confirm-password-error').style.display === 'none';
  return passwordError && confirmPasswordError;
}

let metaTagCount = 1;
document.getElementById("add-meta-tag").addEventListener("click", () => {
  metaTagCount++;
  const metaTagContainer = document.getElementById("meta-tags-container");
  const newMetaTagDiv = document.createElement("div");
  newMetaTagDiv.className = "meta-tag mb-3";
  newMetaTagDiv.id = `meta-tag-${metaTagCount}`;
  newMetaTagDiv.innerHTML =
    `<label for="meta-type-${metaTagCount}" class="form-label">Meta Type:</label>
        <input type="text" class="form-control" id="meta-type-${metaTagCount}" placeholder="Enter Meta Type">
        <label for="meta-value-${metaTagCount}" class="form-label">Meta Value:</label>
        <input type="text" class="form-control" id="meta-value-${metaTagCount}" placeholder="Enter Meta Value">
        <button type="button" class="btn btn-danger btn-sm mt-2 remove-meta-tag">Remove</button>`;
  metaTagContainer.appendChild(newMetaTagDiv);
  newMetaTagDiv.querySelector(".remove-meta-tag").addEventListener("click", () => {
    newMetaTagDiv.remove();
  });
});
ClassicEditor.create(document.querySelector('#editor'))


// ---------------------------------------------------------------------------------
$(document).ready(function () {
  $('#passwordResetForm').on('submit', function (e) {
    e.preventDefault();
    if (!validateForm()) {
      return;
    }
    $.ajax({
      url: 'code/users.php',
      type: 'PUT',
      data: $(this).serialize(),
      dataType: 'json',
      success: function (response) {
        if (response.success) {
          alert('Password reset successfully.');
          // window.location.href = 'login.php';
        } else {
          alert('Failed to reset password. Please try again.');
        }
      },
      error: function (xhr, status, error) {
        console.error('AJAX error:', status, error);
        alert('An unexpected error occurred. Please try again later.');
      }
    });
  });














  // $('.delete-contact').click(function () {
  //   if (!confirm('Are you sure you want to delete?')) {
  //     return false;
  //   }
  //   var key = $(this).data('key');
  //   $.ajax({
  //     url: 'code/personal_details.php',
  //     type: 'POST',
  //     data: {
  //       key: key
  //     },
  //     dataType: 'json',
  //     success: function (response) {
  //       if (response.success) {
  //         alert('Contact deleted successfully.');
  //         window.location.reload();
  //       } else {
  //         alert('Failed to delete contact. Please try again.');
  //       }
  //     },
  //   });
  // });
});

document.getElementById('add-contact-btn').addEventListener('click', function () {
  const contactDetailsList = document.getElementById('contact-details-list');
  const newContactDetail = document.createElement('div');
  newContactDetail.classList.add('row', 'contact-detail');
  newContactDetail.innerHTML =
    `<div class="col-md-5 col-12 mb-3">
    <label>Contact Type</label>
    <input type="text" name="contact_type[]" class="form-control" placeholder="e.g. WhatsApp, Skype, etc.">
  </div>
  <div class="col-md-5 col-12 mb-3">
    <label>Contact Value</label>
    <input type="text" name="contact_value[]" class="form-control" placeholder="e.g. your phone number or ID">
  </div>
  <div class="col-md-2 col-12 mb-3 d-flex align-items-end">
    <button type="button" class="btn btn-danger delete-btn">Delete</button>
  </div>`;
  newContactDetail.querySelector('.delete-btn').addEventListener('click', function () {
    newContactDetail.remove();
  });
  contactDetailsList.appendChild(newContactDetail);
});