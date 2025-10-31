<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Otika - Admin Dashboard Template</title>
  <link rel="stylesheet" href="assets/css/app.min.css">
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/components.css">
  <link rel="stylesheet" href="assets/css/custom.css">
  <link rel='shortcut icon' type='image/x-icon' href='assets/img/favicon.ico' />
  <link rel="stylesheet" href="assets/bundles/izitoast/css/iziToast.min.css">
  <script src="assets/bundles/izitoast/js/iziToast.min.js"></script>
</head>

<body>
  <?php
  session_start();
  if (isset($_SESSION['user_id']) && isset($_SESSION['username'])) {
    header("Location: index.php");
  }
  ?>
  <script src="assets/js/app.min.js"></script>
  <script src="assets/js/scripts.js"></script>
  <script src="assets/js/custom.js"></script>
  <?php if (isset($_SESSION['msg'])): ?>
    <script>
      iziToast.success({
        title: 'Success',
        message: '<?php echo $_SESSION['msg']; ?>',
        position: 'topRight',
        timeout: 3000
      });
    </script>
    <?php unset($_SESSION['msg']); ?>
  <?php elseif (isset($_SESSION['err'])): ?>
    <script>
      iziToast.error({
        title: 'Error',
        message: '<?php echo $_SESSION['err']; ?>',
        position: 'topRight',
        timeout: 3000
      });
    </script>
    <?php unset($_SESSION['err']); ?>
  <?php endif; ?>
  <div class="loader"></div>
  <div id="app">
    <section class="section">
      <div class="container mt-5">
        <div class="row">
          <div
            class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
            <?php
            if (empty($_SESSION['csrf_token'])) {
              $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            }
            if (isset($_REQUEST['forget_pass'])) { ?>
              <div class="card card-primary">
                <div class="card-header">
                  <h4>Forgot Password</h4>
                </div>
                <div class="card-body">
                  <div id="email_section" class="form-group">
                    <label for="email">Enter your email address</label>
                    <input id="email" type="email" class="form-control" name="email" required autofocus>
                    <span id="email_message" class="message text-danger" style="display:none;"></span>
                    <input type="hidden" id="csrf_token" name="csrf_token"
                      value="<?php echo $_SESSION['csrf_token']; ?>">
                  </div>
                  <div id="otp_section" style="display:none;">
                    <div class="input-group mb-3">
                      <input id="otp" type="number" class="form-control" name="otp"
                        placeholder="Enter OTP" required>
                      <div class="input-group-append">
                        <button type="button" class="btn btn-warning" id="verify_otp_btn">Verify
                          OTP</button>
                      </div>
                    </div>
                    <span id="otp_message" class="message text-danger" style="display:none;"></span>
                    <div class="form-group">
                      <button type="button" class="btn btn-primary" id="resend_otp_btn">Resend
                        OTP</button>
                      <span id="resend_timer" style="display:none;">You can resend OTP in <span
                          id="countdown">00:00</span></span>
                    </div>
                  </div>
                  <div id="send_otp_section">
                    <div class="form-group">
                      <button type="button" class="btn btn-primary" id="send_otp_btn">Send
                        OTP</button>
                    </div>
                  </div>
                  <div id="reset_password_section" style="display:none;">
                    <div class="form-group">
                      <label for="new_password">New Password</label>
                      <input id="new_password" type="password" class="form-control"
                        name="new_password" required>
                      <span id="new_password_message" class="message text-danger"
                        style="display:none;"></span>
                    </div>
                    <div class="form-group">
                      <label for="confirm_password">Confirm Password</label>
                      <input id="confirm_password" type="password" class="form-control"
                        name="confirm_password" required>
                      <span id="confirm_password_message" class="message text-danger"
                        style="display:none;"></span>
                    </div>
                    <div class="form-group">
                      <button type="button" class="btn btn-success" id="reset_password_btn">Reset
                        Password</button>
                    </div>

                  </div>

                  <div class="form-group text-center">
                    <a href="login.php">Go back to Login</a>
                  </div>
                </div>
                <script>
                  $(document).ready(function() {
                    var otpSentTime = null,
                      countdownInterval = null,
                      resendWaitTime = 30;

                    function showMessage(target, message, isError = false) {
                      $(target).text(message).show().toggleClass('text-danger', isError)
                        .toggleClass('text-success', !isError);
                      setTimeout(() => $(target).hide(), 5000);
                    }

                    function startResendCountdown() {
                      var timeRemaining = resendWaitTime;
                      $('#resend_otp_btn').prop('disabled', true).hide();
                      $('#resend_timer').show();
                      countdownInterval = setInterval(() => {
                        $('#countdown').text(
                          `${Math.floor(timeRemaining / 60)}:${timeRemaining % 60}`
                        );
                        if (--timeRemaining < 0) {
                          clearInterval(countdownInterval);
                          $('#resend_otp_btn').prop('disabled', false).show();
                          $('#resend_timer').hide();
                        }
                      }, 1000);
                    }

                    function validateEmail(email) {
                      return /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/.test(email);
                    }

                    function handleOtpAction(action, messageElement) {
                      var email = $('#email').val();
                      if (!validateEmail(email)) return showMessage(messageElement,
                        'Invalid email format.', true);

                      // Disable buttons and show spinner
                      var $button = action === 'send_otp' ? $('#send_otp_btn') : $(
                        '#resend_otp_btn');
                      var originalText = $button.text();
                      $button.prop('disabled', true).html(
                        '<i class="fa fa-spinner fa-spin"></i> Please wait...');

                      $.post('code/otp_handler.php', {
                          action,
                          email,
                          csrf_token: $('#csrf_token').val()
                        }, function(r) {
                          if (r.status === 'success') {
                            otpSentTime = Date.now();
                            if (action === 'send_otp') $('#send_otp_section').hide()
                              .siblings('#otp_section').show();
                            showMessage(messageElement, action === 'send_otp' ?
                              'OTP sent!' : 'OTP resent!');
                            startResendCountdown();
                            $('#email').prop('readonly', true);
                          } else {
                            showMessage(messageElement, r.message ||
                              `Failed to ${action === 'send_otp' ? 'send' : 'resend'} OTP.`,
                              true);
                          }
                        }).fail(() => showMessage(messageElement, 'Error with the request.',
                          true))
                        .always(() => {
                          $button.prop('disabled', false).html(originalText);
                        });
                    }

                    $('#send_otp_btn').click(function() {
                      handleOtpAction('send_otp', '#email_message');
                    });
                    $('#resend_otp_btn').click(function() {
                      handleOtpAction('resend_otp', '#otp_message');
                    });

                    $('#verify_otp_btn').click(function() {
                      var otp = $('#otp').val();
                      if (!otp || Date.now() - otpSentTime > 5 * 60 * 1000)
                        return showMessage('#otp_message', 'OTP expired or missing.',
                          true);

                      var $button = $(this);
                      var originalText = $button.text();
                      $button.prop('disabled', true).html(
                        '<i class="fa fa-spinner fa-spin"></i> Verifying...');

                      $.post('code/otp_handler.php', {
                          action: 'verify_otp',
                          email: $('#email').val(),
                          otp,
                          csrf_token: $('#csrf_token').val()
                        }, function(r) {
                          showMessage('#otp_message', r.status === 'success' ?
                            'OTP verified. Reset password.' : r.message ||
                            'OTP verification failed.', r.status !==
                            'success');
                          if (r.status === 'success') {
                            $('#otp_section').hide();
                            $('#reset_password_section').show();
                          }
                        }).fail(() => showMessage('#otp_message',
                          'Error with the request.', true))
                        .always(() => {
                          $button.prop('disabled', false).html(originalText);
                        });
                    });

                    $('#reset_password_btn').click(function() {
                      var newPassword = $('#new_password').val();
                      if (newPassword.length < 8 || newPassword !== $('#confirm_password')
                        .val()) return showMessage('#new_password_message',
                        'Invalid password.', true);

                      var $button = $(this);
                      var originalText = $button.text();
                      $button.prop('disabled', true).html(
                        '<i class="fa fa-spinner fa-spin"></i> Resetting...');

                      $.post('code/otp_handler.php', {
                          action: 'reset_password',
                          email: $('#email').val(),
                          new_password: newPassword,
                          csrf_token: $('#csrf_token').val()
                        }, function(r) {
                          showMessage('#new_password_message', r.status ===
                            'success' ? 'Password reset successful!' : r
                            .message || 'Failed to reset password.', r
                            .status !== 'success');
                          if (r.status === 'success') window.location.href =
                            'login.php';
                        }).fail(() => showMessage('#new_password_message',
                          'Error with the request.', true))
                        .always(() => {
                          $button.prop('disabled', false).html(originalText);
                        });
                    });
                  });
                </script>
              </div>
            <?php } else { ?>
              <div class="card card-primary">
                <div class="card-header">
                  <h4>Admin Login</h4>
                </div>
                <div class="card-body">
                  <form method="POST" action="code/auth.php" class="needs-validation" novalidate="">
                    <input type="hidden" name="csrf_token"
                      value="<?php echo $_SESSION['csrf_token']; ?>">
                    <div class="form-group">
                      <label for="login">Username or Email</label>
                      <input id="login" type="text" class="form-control" name="login" tabindex="1"
                        autofocus>
                    </div>
                    <div class="form-group">
                      <div class="d-block">
                        <label for="password" class="control-label">Password</label>
                        <div class="float-right">
                          <a href="?forget_pass" class="text-small">
                            Forgot Password?
                          </a>
                        </div>
                      </div>
                      <input id="password" type="password" class="form-control" name="password"
                        tabindex="2">
                    </div>
                    <div class="form-group">
                      <button type="submit" name="login_button"
                        class="btn btn-primary btn-lg btn-block" tabindex="4">
                        Login
                      </button>
                    </div>
                    <a href="/"><i class="fas fa-arrow-alt-circle-left"></i> Back to home</a>
                  </form>
                </div>
              </div>
            <?php } ?>
          </div>
        </div>
      </div>
    </section>
  </div>
</body>

</html>