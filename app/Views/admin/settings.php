<?php

/**
 * @var \CodeIgniter\view\View $this
 */
$this->extend('App\Views\admin\layouts\index');
?>
<?php $this->section('main-content'); ?>
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8">
                <div class="card">
                    <form action="<?= $actionUrl ?>" method="post" enctype="multipart/form-data">
                        <div class="card-body">
                            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                            <div class="form-group">
                                <label for="site_name">Site Name</label>
                                <input required value="<?= cleanValue($settings->site_name) ?>" type="text" class="form-control" id="site_name" name="site_name" placeholder="Site Name">
                            </div>
                            <div class="form-group">
                                <label for="site_title">Site Title</label>
                                <input required value="<?= cleanValue($settings->site_title) ?>" type="text" class="form-control" id="site_title" name="site_title" placeholder="Site Title">
                            </div>
                            <div class="form-group">
                                <label for="site_description">Site Description</label>
                                <textarea required type="text" class="form-control" id="site_description" name="site_description" placeholder="Site Description"><?= cleanValue($settings->site_description) ?></textarea>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="site_icon">Site Icon</label>
                                    <input type="file" class="form-control-file" id="site_icon" name="site_icon">
                                </div>
                                <div class="col-md-6">
                                    <img src="<?= base_url(cleanValue($settings->site_icon)) ?>" height="80px" alt="site_icon" srcset="">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="site_logo">Site Logo</label>
                                    <input type="file" class="form-control-file" id="site_logo" name="site_logo">
                                </div>
                                <div class="col-md-6">
                                    <img src="<?= base_url(cleanValue($settings->site_logo)) ?>" height="80px" alt="site_logo" srcset="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="gtag">Google Analytics Code</label>
                                <input value="<?= cleanValue($settings->gtag) ?>" type="text" class="form-control" id="gtag" name="gtag" placeholder="UA-1XXX">
                            </div>
                            <div class="form-group">
                                <label for="disqus">Disqus</label>
                                <input value="<?= cleanValue($settings->disqus) ?>" type="text" class="form-control" id="disqus" name="disqus" placeholder="https://xxx.disqus.com/script.js">
                            </div>
                            <div class="form-group">
                                <label for="Maintenatace">Maintenance</label><br>
                                <input type="checkbox" id="maintenance" name="maintenance" <?= get_var($settings->maintenance) == 2 ? 'checked' : '' ?> data-bootstrap-switch class="form-control">
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-success">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-2"></div>
        </div>

        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data Dump</h3>

                <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                    <i class="fas fa-minus"></i></button>
                <button type="button" class="btn btn-tool" data-card-widget="remove" data-toggle="tooltip" title="Remove">
                    <i class="fas fa-times"></i></button>
                </div>
            </div>
            <div class="card-body">
            <?php
            if (ENVIRONMENT != 'production') {
            var_dump($this->getData());
            }
            ?>
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
                Footer
            </div>
            <!-- /.card-footer-->
        </div>
        <!-- /.card -->

    </div> <!-- /.container-fluid -->
</section>
<!-- /.content -->
<?php $this->endSection(); ?>

<?php $this->section('main-script'); ?>
<!-- Bootstrap Switch -->
<?php print_script_resource('assets/admin/plugins/bootstrap-switch/js/bootstrap-switch.min.js') ?>

<script>
    $("input[data-bootstrap-switch]").each(function(){
      $(this).bootstrapSwitch('state', $(this).prop('checked'));
    });
</script>
<?php $this->endSection(); ?>