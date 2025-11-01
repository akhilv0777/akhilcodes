<?php require 'header.php' ?>
<link rel="stylesheet" href="assets/bundles/datatables/datatables.min.css">
<section class="section">
  <div class="section-body">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h4>Add Projects</h4>
            <div class="card-header-action">
              <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#"><i class="fas fa-plus"></i></a>
            </div>
          </div>
          <div class="collapse" id="mycard-collapse">
            <form action="code/projects.php" method="post" enctype="multipart/form-data">
              <div class="card-body">
                <div class="form-group row mb-4">
                  <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Title</label>
                  <div class="col-sm-12 col-md-7">
                    <input type="text" class="form-control" name="title">
                  </div>
                </div>
                <div class="form-group row mb-4">
                  <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Category</label>
                  <div class="col-sm-12 col-md-7">
                    <select class="form-control" name="category" id="categorySelect">
                      <option value="" disabled selected>Select Category</option>
                      <?php
                      // $query = "SELECT * FROM projects";
                      // $result = $conn->query($query);
                      // if ($result->num_rows > 0) :
                      //   while ($row = $result->fetch_assoc()) :
                      //     $details = unserialize($row['details']);
                      //     if ($details['category'] == 'other') :
                      //       echo '<option value="' . $details['other_category'] . '">' . $details['other_category'] . '</option>';
                      //     else :
                      //       echo '<option value="' . $details['category'] . '">' . $details['category'] . '</option>';
                      //     endif;
                      //   endwhile;
                      // endif;
                      ?>
                      <option value="other">Other</option>
                    </select>
                  </div>
                </div>
                <div class="form-group row mb-4" id="otherFields" style="display: none;">
                  <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Add Category</label>
                  <div class="col-sm-12 col-md-7">
                    <div class="input-group pt-2">
                      <div class="input-group-prepend">
                        <span class="input-group-text">Name</span>
                      </div>
                      <input type="text" class="form-control" name="category_name">
                    </div>
                  </div>
                </div>

                <div class="form-group row mb-4">
                  <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Content</label>
                  <div class="col-sm-12 col-md-7">
                    <textarea id="editor" name="content"></textarea>
                  </div>
                </div>
                <div class="form-group row mb-4">
                  <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Choose File</label>
                  <div class="col-sm-12 col-md-7">
                    <div class="input-group pt-2">
                      <div class="input-group-prepend">
                        <span class="input-group-text">Thumbnail *</span>
                      </div>
                      <input type="file" class="form-control" name="thumbnail">
                      <div class="input-group-prepend">
                        <span class="input-group-text">Images * (Multiple)</span>
                      </div>
                      <input type="file" class="form-control" multiple name="images[]">
                    </div>
                  </div>
                </div>

                <div class="form-group row mb-4">
                  <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
                  <div class="col-sm-12 col-md-7">
                    <button name="project" value="project" class="btn btn-primary">Add Projects</button>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
        <div class="card">
          <div class="card-header">
            <h4>Basic DataTables</h4>
          </div>
          <div class="card-body">
            <form action="code/projects.php" method="post">
              <div class="table-responsive">
                <table class="table dataTable">
                  <thead>
                    <tr>
                      <th scope="col"><input type="checkbox" id="select-all"></th>
                      <th scope="col">#</th>
                      <th scope="col">Title</th>
                      <th scope="col">Category</th>
                      <th scope="col">Content</th>
                      <th scope="col">Thumbnail</th>
                      <th scope="col">Images</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    /*
                    $query = "SELECT * FROM projects";
                    $result = $conn->query($query);
                    if ($result->num_rows > 0) :
                      $i = 1;
                      while ($row = $result->fetch_assoc()) :
                        $details = unserialize($row['details']);
                        $images = unserialize($row['images']);  ?>
                        <tr>
                          <td scope="row"><input type="checkbox" name="selected_ids[]" class="select-item" value="<?php echo $row['id']; ?>"></td>
                          <td scope="row"><?php echo $i++; ?></td>
                          <td>
                            <?php echo $details['title']; ?>
                            <div class="table-links">
                              <a href="../project_details.php?project_id=<?php echo $row['id']; ?>">View</a>
                            </div>
                          </td>
                          <td>
                            <?php
                            if ($details['category'] == 'other') {
                              echo $details['other_category'];
                            } else {
                              echo $details['category'];
                            } ?>
                          </td>
                          <td>
                            <?php echo (count($words = explode(" ",  $details['content'])) > 14) ? implode(" ", array_slice($words, 0, 14)) . ".." :  $details['content']; ?>
                          </td>
                          <td>
                            <?php
                            if (!empty($images['thumbnail'])) {
                              echo "<img width='35' data-toggle='tooltip' class='rounded-circle' src='uploads/projects/" . $images['thumbnail'] . "' alt='Thumbnail' >";
                            }
                            ?>
                          </td>
                          <td>
                            <?php
                            if (!empty($images['images'])) {
                              foreach ($images['images'] as $image) {
                                echo "<img width='35'  data-toggle='tooltip' class='rounded-circle'  src='uploads/projects/" . $image . "' alt='Image'>";
                              }
                            } ?>
                          </td>
                        </tr>
                      <?php endwhile; ?>
                    <?php endif;
                    */
                    ?>
                  </tbody>
                </table>
              </div>
              <button type="submit" name="project_delete" onclick="return confirm('Are you sure?');" value="project_delete" class="btn btn-sm btn-danger" id="delete-btn">Delete Selected</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>
<script>
  ClassicEditor
    .create(document.querySelector('#editor'))
</script>
<?php require 'footer.php' ?>
<script>
  $('#categorySelect').change(function() {
    if ($(this).val() === 'other') {
      $('#otherFields').show();
    } else {
      $('#otherFields').hide();
    }
  });
</script>
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
    });
  });
</script>