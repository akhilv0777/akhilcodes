<?php
require 'header.php';
?>
<section class="section">
  <div class="section-body">
    <div class="row mt-sm-4">
      <div class="col-12 col-md-12 col-lg-5">
        <div class="card author-box">
          <div class="card-body">
            <div class="author-box-center">
              <form id="profilePictureForm" enctype="multipart/form-data">
                <div class="position-relative" onclick="document.getElementById('file-input').click();"
                  style="cursor:pointer">
                  <img id="profile-image" alt="image"
                    src="uploads/<?php // echo $user_data['profile_picture'];   
                                  ?>"
                    class="rounded-circle author-box-picture">
                  <svg style="color: black;margin-left: -25px;" xmlns="http://www.w3.org/2000/svg"
                    width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="feather feather-edit  position-absolute top-0 end-0  ">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                  </svg>
                </div>
                <input type="file" name="profile_picture" id="file-input" style="display: none;" accept="image/*" onchange="previewImage(event)">
                <div class="clearfix"></div>
                <div class="author-box-name">
                  <a href="#"><?php // echo $user_data['name'];   
                              ?></a>
                </div>
                <div class="author-box-job"><? #php  echo  ($aboutData['title']);  
                                            ?></div>
                <button type="submit" name="submit" value="profile_picture"
                  class="btn btn-success">Update</button>
              </form>
            </div>
          </div>
        </div>
        <div class="card">
          <div class="card-header">
            <h4>Change Password</h4>
          </div>
          <div class="card-body">
            <form id="passwordResetForm">
              <div class="form-group">
                <label for="old_password">Old Password</label>
                <input id="old_password" type="password" class="form-control" name="old_password">
              </div>
              <div class="form-group">
                <label for="new_password">New Password</label>
                <input id="new_password" type="password" class="form-control" name="new_password"
                  oninput="document.getElementById('password-error').style.display = !/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d).{8,}$/.test(this.value) ? 'block' : 'none'">
                <small id="password-help" style="font-size: 12px; color: grey;">
                  Password must be at least 8 characters, with uppercase, lowercase, and a number.
                </small>
                <small id="password-error" style="color: red; display: none;">Password doesn't meet the
                  requirements.</small>
              </div>
              <div class="form-group">
                <label for="password-confirm">Confirm Password</label>
                <input id="password-confirm" type="password" class="form-control"
                  name="confirm_password" oninput="document.getElementById('confirm-password-error').style.display = (this.value !== document.getElementById('new_password').value) ? 'block' : 'none'">
                <small id="confirm-password-error" style="color: red; display: none;">Passwords do not
                  match.</small>
              </div>
              <button type="submit" name="change_password" class="btn btn-primary">Reset Password</button>
              <div class="float-right">
                <a href="?forget_pass" class="text-small">
                  Reset Password?
                </a>
              </div>
            </form>
          </div>
        </div>
        <div class="card">
          <div class="card-header">
            <h4>Site Setting</h4>
          </div>
          <div class="card-body">
            <form action="code/site_settings" method="post">
              <div class="setting-section mb-3">
                <label class="form-label">Site Title</label>
                <input type="text" name="site_tittle" class="form-control">
              </div>

              <div id="favicon-section" class="setting-section mb-3">
                <label for="favicon-upload" class="form-label">Upload Favicon:</label>
                <input type="file" name="favicon" id="favicon-upload" class="form-control">
              </div>

              <div id="meta-tags-section" class="setting-section mb-3">
                <div id="meta-tags-container" class="mt-3">
                  <div class="meta-tag mb-3" id="meta-tag-1">
                    <label for="meta-type-1" class="form-label">Meta Type:</label>
                    <input type="text" class="form-control" id="meta-type-1"
                      placeholder="Enter Meta Type">
                    <label for="meta-value-1" class="form-label">Meta Value:</label>
                    <input type="text" class="form-control" id="meta-value-1"
                      placeholder="Enter Meta Value">
                  </div>
                </div>
                <button id="add-meta-tag" type="button" class="btn btn-success mt-3">Add More Meta
                  Tag</button>
                <div class="card-footer text-right">
                  <button name="submit" value="contact_details" type="submit"
                    class="btn btn-primary">Save Changes</button>
                </div>
              </div>
            </form>
          </div>
        </div>

      </div>
      <div class="col-12 col-md-12 col-lg-7">
        <div class="card">
          <div class="padding-20">
            <ul class="nav nav-tabs" id="myTab2" role="tablist">
              <li class="nav-item">
                <a class="nav-link" id="home-tab2" data-toggle="tab" href="#about" role="tab"
                  aria-selected="false">About</a>
              </li>
              <li class="nav-item">
                <a class="nav-link active" id="profile-tab2" data-toggle="tab" href="#settings"
                  role="tab" aria-selected="true">Setting</a>
              </li>
            </ul>
            <div class="tab-content tab-bordered" id="myTab3Content">
              <div class="tab-pane fade" id="about" role="tabpanel" aria-labelledby="home-tab2">
                <form action="code/personal_details.php" method="post" enctype="multipart/form-data">
                  <div class="row">
                    <div class="form-group col-md-6 col-12">
                      <label>Title</label>
                      <input type="text" name="title" class="form-control"
                        value="<?php // echo ($aboutData['title']); 
                                ?>">
                    </div>
                    <div class="form-group col-md-6 col-12">
                      <label>Resume</label>
                      <input type="file" name="resume" class="form-control">
                    </div>
                  </div>
                  <textarea name="about" id="editor"><?php // echo  ($aboutData['about']); 
                                                      ?></textarea>
                  <div class="card-footer text-right">
                    <button name="about_us" type="submit" class="btn btn-primary">Save
                      Changes</button>
                  </div>
                </form>
              </div>
              <div class="tab-pane fade active show" id="settings" role="tabpanel"
                aria-labelledby="profile-tab2">
                <form method="post" action="code/personal_details.php" class="needs-validation">
                  <div class="card-header">
                    <h4>Edit Profile</h4>
                  </div>

                  <div class="card-body">
                    <div class="row">
                      <div class="form-group col-md-6 col-12">
                        <label>First Name</label>
                        <input type="text" name="first_name" class="form-control"
                          value="<?php // echo  explode(" ", $user_data['name'])[0]; 
                                  ?>">
                      </div>
                      <div class="form-group col-md-6 col-12">
                        <label>Last Name</label>
                        <input type="text" name="last_name" class="form-control"
                          value="<?php // echo  explode(" ", $user_data['name'])[1]; 
                                  ?>">
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group col-md-7 col-12">
                        <label>Email</label>
                        <input type="email" class="form-control" name="email"
                          value="<?php // echo  $user_data['email']; 
                                  ?>">
                      </div>
                      <div class="form-group col-md-5 col-12">
                        <label>Phone</label>
                        <input type="tel" name="phone" class="form-control"
                          value="<?php // echo  !empty($contact_details['phone']) ? $contact_details['phone'] : ''; 
                                  ?>">
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group col-md-6 col-12">
                        <label>Date of birth</label>
                        <input type="date" name="dob" class="form-control"
                          value="<?php // echo  !empty($contact_details['dob']) ? $contact_details['dob'] : ''; 
                                  ?>">
                      </div>
                      <div class="form-group col-md-6 col-12">
                        <label>Village/Post/District</label>
                        <input type="" name="village" class="form-control"
                          value="<?php // echo  !empty($contact_details['village']) ? $contact_details['village'] : ''; 
                                  ?>">
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group col-md-12 col-12">
                        <label>City/Town</label>
                        <input type="text" name="city" class="form-control"
                          value="<?php // echo  !empty($contact_details['city']) ? $contact_details['city'] : ''; 
                                  ?>">
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group col-md-5 col-12">
                        <label>State</label>
                        <input type="text" name="state" class="form-control"
                          value="<?php // echo  !empty($contact_details['state']) ? $contact_details['state'] : ''; 
                                  ?>">
                      </div>
                      <div class="form-group col-md-7 col-12">
                        <label>ZIP Code</label>
                        <input type="text" name="zip" class="form-control"
                          value="<?php // echo  !empty($contact_details['zip']) ? $contact_details['zip'] : ''; 
                                  ?>">
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group col-md-12 col-12">
                        <label>Google Map Embed Link</label>
                        <textarea name="location" class="form-control" rows="4"><?php // echo  !empty($contact_details['location']) ? $contact_details['location'] : ''; 
                                                                                ?></textarea>
                      </div>
                    </div>

                    <?php
                    if (isset($contact_details['additional_contacts']) && !empty($contact_details['additional_contacts'])) { ?>
                      <div class="row">
                        <?php foreach ($contact_details['additional_contacts'] as  $inner_array) { ?>
                          <?php foreach ($inner_array as $key => $value) { ?>
                            <div class="form-group col-md-6 col-12">
                              <label><input readonly class="border-0" type="text"
                                  name="contact_type[]"
                                  value="<?php // echo  ucwords($key)  
                                          ?>"></label>
                              <a href="javascript:void(0);"
                                class="btn btn-link text-danger delete-contact"
                                data-key="<?php // echo  strtolower($key); 
                                          ?>">Remove</a>
                              <input type="text" name="contact_value[]" class="form-control"
                                value="<?php // echo  $value  
                                        ?>">
                            </div>
                          <?php } ?>
                        <?php } ?>
                      </div>
                    <?php   } ?>
                    <!-- Dynamic contact details list -->
                    <div id="contact-details-list">
                      <div class="row contact-detail">
                        <div class="form-group col-md-6 col-12">
                          <label>Contact Type</label>
                          <input type="text" name="contact_type[]" class="form-control"
                            placeholder="e.g. WhatsApp, Skype, etc.">
                        </div>
                        <div class="form-group col-md-6 col-12">
                          <label>Contact Value</label>
                          <input type="text" name="contact_value[]" class="form-control"
                            placeholder="e.g. your phone number or ID">
                        </div>
                      </div>

                    </div>

                    <button type="button" class="btn btn-success" id="add-contact-btn">Add More
                      Contact</button>
                  </div>
                  <div class="card-footer text-right">
                    <button name="submit" value="contact_details" type="submit"
                      class="btn btn-primary">Save Changes</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>
<?php
require 'footer.php'
?>
<script src="assets/js/profile.js"></script>