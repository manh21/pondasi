<?php
/**
 * @var \CodeIgniter\view\View $this
 */
$this->extend('App\Views\admin\layouts\index');

$group = $data['group'];
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
                    <input type="hidden" name="id" value="<?= $group->id ?>" />
                    <div class="form-group">
                        <label for="identity">Group Name</label>
                        <input required value="<?= cleanValue($group->name) ?>" type="text" class="form-control" id="group_name" name="group_name" placeholder="Group Name">
                        <div class="valid-feedback">
                            Looks good!
                        </div>
                        <div class="invalid-feedback">
                            Please provide a valid Group Name.
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email">Description</label>
                        <input required value="<?= cleanValue($group->description) ?>" type="text" class="form-control" id="group_description" name="group_description" placeholder="Description">
                        <div class="valid-feedback">
                            Looks good!
                        </div>
                        <div class="invalid-feedback">
                            Please provide a valid Description.
                        </div>
                    </div>
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