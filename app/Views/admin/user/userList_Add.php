<?php
/**
 * @var \CodeIgniter\view\View $this
 */
$this->extend('App\Views\admin\layouts\index');

$groups = $data['groups'];
?>

<?php $this->section('main-content'); ?>

<!-- Main content -->
<section class="content">
  <div class="container-fluid">

    <?= empty($message) ? '' : $message ?>
    
    <div class="row">
    <div class="col-md-2"></div>
    <div class="col-md-8">
        <div class="card">
            <form class="needs-validation" novalidate action="<?= $actionUrl ?>" role="form" method="POST">
                <div class="card-body">
                    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="first_name">First Name</label>
                                <input required type="text" name="first_name" id="first_name" class="form-control" placeholder="Enter First Name">
                                <div class="valid-feedback">
                                    Looks good!
                                </div>
                                <div class="invalid-feedback">
                                    Please provide a valid First Name.
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="last_name" >Last Name</label>
                                <input id="last_name" name="last_name" type="text" class="form-control" placeholder="Enter Last Name" >
                                <div class="valid-feedback">
                                    Looks good!
                                </div>
                                <div class="invalid-feedback">
                                    Please provide a valid Last Name.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="identity">Username</label>
                        <input required type="text" class="form-control" id="identity" name="identity" placeholder="Username">
                        <div class="valid-feedback">
                            Looks good!
                        </div>
                        <div class="invalid-feedback">
                            Please provide a valid Username.
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email">Email address</label>
                        <input required type="email" class="form-control" id="email" name="email" placeholder="Enter Email address">
                        <div class="valid-feedback">
                            Looks good!
                        </div>
                        <div class="invalid-feedback">
                            Please provide a valid Email address.
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="company">Company or Organization</label>
                        <input required type="text" class="form-control" id="company" name="company" placeholder="Enter Company or Organization">
                        <div class="valid-feedback">
                            Looks good!
                        </div>
                        <div class="invalid-feedback">
                            Please provide a valid Company or Organization.
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="tel" name="phone" id="phone" class="form-control" placeholder="0812XXXXXXXX">
                        <div class="valid-feedback">
                            Looks good!
                        </div>
                        <div class="invalid-feedback">
                            Please provide a valid Phone number.
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input required type="password" minlength="8" name="password" id="password" class="form-control" placeholder="Enter New Password (Minimum 8)">
                        <div class="valid-feedback">
                            Looks good!
                        </div>
                        <div class="invalid-feedback">
                            Please provide a valid Password.
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password_confirm">Password Confirm</label>
                        <input required type="password" minlength="8" name="password_confirm" id="password_confirm" class="form-control" placeholder="Password Confirm">
                        <div class="valid-feedback">
                            Looks good!
                        </div>
                        <div class="invalid-feedback">
                            Please provide a valid Password Confirm.
                        </div>
                    </div>
                    <?php if ($ionAuth->isAdmin()): ?>
                        <div class="form-group">
                        <label for="groups">User Groups</label>
                        <?php foreach ($groups as $group):?>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="groups[]" value="<?php echo $group['id'];?>">
                                <label class="form-check-label"><?php echo cleanValue($group['name']);?></label>
                            </div>
                        <?php endforeach?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="card-footer">
                    <a href="<?= $backWardUrl ?>" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
    <div class="col-md-2"></div>
    </div>

  </div> <!-- /.container-fluid -->
</section>
<!-- /.content -->


<?php $this->endSection(); ?>

<?php $this->section('main-script'); ?>

<script>
    $.ajaxSetup( {
        headers: {
            _CSRF_HEADER: _CSRF_NAME,
        }
    });

    (function() {
        'use strict';
        window.addEventListener('load', function() {
            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.getElementsByClassName('needs-validation');
            // Loop over them and prevent submission
            var validation = Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
                if (form.checkValidity() === false) {
                event.preventDefault();
                event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
            });
        }, false);
    })();
</script>

<?php $this->endSection(); ?> 