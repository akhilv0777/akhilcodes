<?php require 'includes/header.php' ?>

<section class="section">
  <div class="section-body">
    <div class="row mt-sm-4">
      <div class="col-12 col-md-12 col-lg-12">
        <div class="card">
          <div class="padding-20">
            <form method="post" action="code/personal_details.php" class="needs-validation">
              <div class="card-header">
                <h4>Resume</h4>
              </div>
              <div class="card-body">
                <!-- Education Section -->
                <div class="card mb-3">
                  <div class="card-header">
                    <h3>Education</h3>
                  </div>
                  <div class="card-body">
                    <div id="education-list">
                      <!-- Existing Education Details -->
                      <div class="row education-detail">
                        <?php if (isset($resumeDetails['education'])): ?>
                          <?php foreach ($resumeDetails['education'] as $edu): ?>
                            <div class="form-group col-md-6 col-12">
                              <label>
                                <?php echo ucwords($edu['type']); ?>
                                <input type="hidden" value="<?php echo $edu['type']; ?>" name="education_type[]">
                                <input type="date" name="education_start[]" value="<?php echo $edu['start']; ?>" /> to
                                <input type="date" name="education_end[]" value="<?php echo $edu['end']; ?>" />
                              </label>
                              <a onclick="return confirm('Are you sure you want to delete?')" href="javascript:void(0);"
                                class="btn btn-link text-danger delete-resume"
                                data-resume_category="education"
                                data-resume_type="<?php echo $edu['type']; ?>">Remove</a>
                              <input type="text" value="<?php echo $edu['value']; ?>" name="education_value[]"
                                class="form-control" placeholder="e.g., Bachelor">
                            </div>
                          <?php endforeach; ?>
                        <?php endif; ?>
                      </div>
                      <!-- Add New Education Fields -->
                      <div class="row education-detail">
                        <div class="form-group col-md-3 col-12">
                          <label>Education Type</label>
                          <input type="text" name="education_type[]" class="form-control" placeholder="e.g., Bachelor">
                        </div>
                        <div class="form-group col-md-4 col-12">
                          <label>Education Value</label>
                          <input type="text" name="education_value[]" class="form-control" placeholder="e.g., B.Sc Computer Science">
                        </div>
                        <div class="form-group col-md-2 col-12">
                          <label>Start Date</label>
                          <input type="date" name="education_start[]" class="form-control" placeholder="e.g., Java, Python">
                        </div>
                        <div class="form-group col-md-2 col-12">
                          <label>End Date</label>
                          <input type="date" name="education_end[]" class="form-control" placeholder="e.g., Java, Python">
                        </div>
                        <div class="form-group col-md-1 col-12 d-flex" style="align-items: flex-end;">
                          <button type="button" class="btn btn-success" id="add-education-btn">Add More</button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Experience Section -->
                <div class="card mb-3">
                  <div class="card-header">
                    <h3>Experience</h3>
                  </div>
                  <div class="card-body">
                    <div id="experience-list">
                      <!-- Existing Experience Details -->
                      <div class="row experience-detail">
                        <?php if (isset($resumeDetails['experience'])): ?>
                          <?php foreach ($resumeDetails['experience'] as $edu): ?>
                            <div class="form-group col-md-6 col-12">
                              <label>
                                <?php echo ucwords($edu['type']); ?>
                                <input type="hidden" value="<?php echo $edu['type']; ?>" name="experience_type[]">
                                <input type="date" name="experience_start[]" value="<?php echo $edu['start']; ?>" /> <span class="mx-4">to</span>
                                <?php $dateValue = ($edu['end'] !== 'Present' && isset($edu['end'])) ? $edu['end'] : ''; ?>
                                <input type="radio" name="check" id="present" <?= $edu['end'] == 'Present' ? 'checked' : ''; ?> />
                                <label for="present">Present</label>
                                <input type="radio" name="check" id="date" <?= $edu['end'] !== 'Present' ? 'checked' : ''; ?> />
                                <label for="date">Date</label>
                                <input type="text" id="experience_end" name="experience_end[]" value="<?= $edu['end'] == 'Present' ? 'Present' : $dateValue; ?>" <?= $edu['end'] == 'Present' ? 'readonly' : ''; ?> />
                                <script>
                                  document.querySelectorAll('input[name="check"]').forEach(radio => {
                                    radio.addEventListener('change', () => {
                                      const input = document.getElementById('experience_end');
                                      input.type = radio.id === 'present' ? 'text' : 'date';
                                      input.value = radio.id === 'present' ? 'Present' : input.value;
                                      input.readOnly = radio.id === 'present';
                                    });
                                  });
                                </script>
                              </label>
                              <a onclick="return confirm('Are you sure you want to delete?')" href="javascript:void(0);"
                                class="btn btn-link text-danger delete-resume"
                                data-resume_category="experience"
                                data-resume_type="<?php echo $edu['type']; ?>">Remove</a>
                              <input type="text" value="<?php echo $edu['value']; ?>" name="experience_value[]"
                                class="form-control" placeholder="e.g., 5 years at Company X">
                            </div>
                          <?php endforeach; ?>
                        <?php endif; ?>
                      </div>

                      <!-- Add New Experience Fields -->
                      <div class="row experience-detail">
                        <div class="form-group col-md-3 col-12">
                          <label>Experience Type</label>
                          <input type="text" name="experience_type[]" class="form-control" placeholder="e.g., Job Title">
                        </div>
                        <div class="form-group col-md-4 col-12">
                          <label>Experience Value</label>
                          <input type="text" name="experience_value[]" class="form-control" placeholder="e.g., 5 years at Company X">
                        </div>
                        <div class="form-group col-md-2 col-12">
                          <label>Start Date</label>
                          <input type="date" name="experience_start[]" class="form-control" placeholder="e.g., Java, Python">
                        </div>
                        <div class="form-group col-md-2 col-12">
                          <label>
                            <input type="radio" name="end" id="date-radio" checked> Date
                            <input type="radio" name="end" id="present-radio"> Present
                          </label>
                          <input type="date" class="form-control" name="experience_end[]" id="end-date" readonly>
                          <script>
                            const dateRadio = document.getElementById('date-radio');
                            const presentRadio = document.getElementById('present-radio');
                            const endDateInput = document.getElementById('end-date');

                            function toggleDateInput() {
                              endDateInput.type = presentRadio.checked ? 'text' : 'date';
                              endDateInput.value = presentRadio.checked ? 'Present' : '';
                              endDateInput.readOnly = presentRadio.checked;
                            }
                            dateRadio.addEventListener('change', toggleDateInput);
                            presentRadio.addEventListener('change', toggleDateInput);
                            toggleDateInput();
                          </script>
                        </div>
                        <div class="form-group col-md-1 col-12 d-flex" style="align-items: flex-end;">
                          <button type="button" class="btn btn-success" id="add-experience-btn">Add More</button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Skills Section -->
                <div class="card mb-3">
                  <div class="card-header">
                    <h3>Skills</h3>
                  </div>
                  <div class="card-body">
                    <div id="skills-list">
                      <!-- Existing Skills Details -->
                      <div class="row skills-detail">
                        <?php if (isset($resumeDetails['skills'])): ?>
                          <?php foreach ($resumeDetails['skills'] as $edu): ?>
                            <div class="form-group col-md-6 col-12">
                              <label>
                                <?php echo ucwords($edu['type']); ?>
                                <input type="hidden" value="<?php echo $edu['type']; ?>" name="skills_type[]">
                              </label>
                              <a onclick="return confirm('Are you sure you want to delete?')" href="javascript:void(0);"
                                class="btn btn-link text-danger delete-resume"
                                data-resume_category="skills"
                                data-resume_type="<?php echo $edu['type']; ?>">Remove</a>
                              <input type="number" id="skills_value" min="0" max="100" value="<?php echo $edu['value']; ?>" name="skills_value[]"
                                class="form-control" placeholder="e.g. 20%" />
                            </div>
                          <?php endforeach; ?>
                        <?php endif; ?>
                      </div>

                      <!-- Add New Skills Fields -->
                      <div class="row skills-detail">
                        <div class="form-group col-md-5 col-12">
                          <label>Skills Type</label>
                          <input type="text" name="skills_type[]" class="form-control" placeholder="e.g., Java, Python">
                        </div>
                        <div class="form-group col-md-6 col-12">
                          <label>Skills Value</label>
                          <input type="number" name="skills_value[]" class="form-control" min="0" max="100" placeholder="e.g. 20%">
                        </div>
                        <div class="form-group col-md-1 col-12 d-flex" style="align-items: flex-end;">
                          <button type="button" class="btn btn-success" id="add-skills-btn">Add More</button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- Save Changes Button -->
              <div class="card-footer text-right">
                <button name="resume_details" type="submit" class="btn btn-primary">Save Changes</button>
              </div>
            </form>

            <script>
              document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('add-education-btn').addEventListener('click', function() {
                  let educationList = document.getElementById('education-list');
                  let newEducation = document.createElement('div');
                  newEducation.classList.add('row', 'education-detail');
                  newEducation.innerHTML = `
            <div class="form-group col-md-3 col-12">
                <label>Education Type</label>
                <input type="text" name="education_type[]" class="form-control" placeholder="e.g., Bachelor">
            </div>
            <div class="form-group col-md-4 col-12">
                <label>Education Value</label>
                <input type="text" name="education_value[]" class="form-control" placeholder="e.g., B.Sc Computer Science">
            </div>
            <div class="form-group col-md-2 col-12">
                 <label>Start Date</label>
                <input type="date" name="education_start[]" class="form-control" placeholder="e.g., Java, Python">
            </div>
            <div class="form-group col-md-2 col-12">
                <label>End Date</label>
                <input type="date" name="education_end[]" class="form-control" placeholder="e.g., Java, Python">
            </div>
            <div class="form-group col-md-1 col-12 d-flex" style="align-items: flex-end;">
                <button type="button" class="btn btn-danger remove-btn">Remove</button>
            </div>`;
                  educationList.appendChild(newEducation);
                  newEducation.querySelector('.remove-btn').addEventListener('click', function() {
                    educationList.removeChild(newEducation);
                  });
                });
                document.getElementById('add-experience-btn').addEventListener('click', function() {
                  let experienceList = document.getElementById('experience-list');
                  let newExperience = document.createElement('div');
                  newExperience.classList.add('row', 'experience-detail');
                  newExperience.innerHTML = `
            <div class="form-group col-md-3 col-12">
                <label>Experience Type</label>
                <input type="text" name="experience_type[]" class="form-control" placeholder="e.g., Job Title">
            </div>
            <div class="form-group col-md-4 col-12">
                <label>Experience Value</label>
                <input type="text" name="experience_value[]" class="form-control" placeholder="e.g., 5 years at Company X">
            </div>
                 <div class="form-group col-md-2 col-12">
                <label>Start Date</label>
                <input type="date" name="experience_start[]" class="form-control" placeholder="e.g., Java, Python">
            </div>
            <div class="form-group col-md-2 col-12">
                <label>End Date</label>
                <input type="date" name="experience_end[]" class="form-control" placeholder="e.g., Java, Python">
            </div>
            <div class="form-group col-md-1 col-12 d-flex" style="align-items: flex-end;">
                <button type="button" class="btn btn-danger remove-btn">Remove</button>
            </div>`;
                  experienceList.appendChild(newExperience);
                  newExperience.querySelector('.remove-btn').addEventListener('click', function() {
                    experienceList.removeChild(newExperience);
                  });
                });
                document.getElementById('add-skills-btn').addEventListener('click', function() {
                  let skillsList = document.getElementById('skills-list');
                  let newSkills = document.createElement('div');
                  newSkills.classList.add('row', 'skills-detail');
                  newSkills.innerHTML = `
            <div class="form-group col-md-5 col-12">
                <label>Skills Type</label>
                <input type="text" name="skills_type[]" class="form-control" placeholder="e.g., Java, Python" >
            </div>
            <div class="form-group col-md-6 col-12">
                <label>Skills Value</label>
                <input type="number" name="skills_value[]" class="form-control"  min="0" max="100" placeholder="e.g.20%" >
            </div>
            <div class="form-group col-md-1 col-12 d-flex" style="align-items: flex-end;">
                <button type="button" class="btn btn-danger remove-btn">Remove</button>
            </div>`;
                  skillsList.appendChild(newSkills);
                  newSkills.querySelector('.remove-btn').addEventListener('click', function() {
                    skillsList.removeChild(newSkills);
                  });
                });
              });
            </script>

          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<?php require 'includes/footer.php' ?>
<script>
  $(document).ready(function() {
    $('.delete-resume').click(function() {
      var resume_category = $(this).data('resume_category');
      var resume_type = $(this).data('resume_type');
      $.ajax({
        url: 'code/personal_details.php',
        type: 'POST',
        data: {
          resume_category: resume_category,
          resume_type: resume_type
        },
        dataType: 'json',
        success: function(response) {
          if (response.success) {
            alert('Resume deleted successfully.');
            window.location.reload();
          } else {
            alert('Error deleting resume: ' + response.message);
          }
        },
      });
    });
  });
</script>