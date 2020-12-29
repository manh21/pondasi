<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>System21 | Log in</title>

  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- App CSS -->
  <?php print_link_resource("assets/admin/css/app.css"); ?>

</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="<?= adminURL('admin')?>"><b>System</b>21</a>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Reset Password</p>

      <div id="infoMessage"><?php echo $message;?></div>

      <form action="<?= adminURL('auth/reset_password/' . $code)?>" method="post">
        <?= csrf_field() ?>
        <?php echo form_input($user_id);?>
        <div class="input-group mb-3">
          <input required minlength="8" type="password" id="new" name="new" class="form-control" placeholder="New Password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input required minlength="8" type="password" id="new_confirm" name="new_confirm" class="form-control" placeholder="Confirm Password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-8">
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block">Submit</button>
          </div>
          <!-- /.col -->
        </div>
      </form>
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

<!-- App JS -->
<?php print_script_resource("assets/admin/js/app.js"); ?>

</body>
</html>