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

    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Dashboard</h3>
        <div class="card-tools">
        </div>
      </div>
      <div class="card-body">
        <?php
        if (ENVIRONMENT != 'production') {
          var_dump($this->getData());
        }
        ?>
      </div>
    </div>

    <!-- Default box -->
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Title</h3>

        <div class="card-tools">
          <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
            <i class="fas fa-minus"></i></button>
          <button type="button" class="btn btn-tool" data-card-widget="remove" data-toggle="tooltip" title="Remove">
            <i class="fas fa-times"></i></button>
        </div>
      </div>
      <div class="card-body">
        <table id="userList" class="display table table-bordered table-hover table-striped" style="width:100%">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Username</th>
                    <th>First Name</th>
                    <th>Email</th>
                    <th>Active</th>
                    <th>Option</th>
                </tr>
            </thead>
        </table>
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
<script>
  const baseUrl = '<?= base_url()?>';
  $(document).ready(function() {
      $('#userList').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": true,
      "responsive": true,
          "processing": true,
          "serverSide": true,
          "ajax": "/admin/userlist/getdata",
          "columns": [
            {
              data: 0
            },
            {
              data: 1
            },
            {
              data: 2
            },
            {
              data: 3
            },
            {
              data: 4,
              render: function(data, type){
                let id = data[5];

                if(type === 'display'){
                  if(data == 1){
                    return `<a href="${baseUrl}/admin/userlist/deactivate/${id}" class="label label-info">active</a>`
                  } else {
                    return `<a href="${baseUrl}/admin/userlist/activate/${id}" class="label label-info">Inactive</a>`
                  }
                }
                return data;
              }
            },
            {
              data: 5,
              render: function(data, type){
                if(type === 'display'){
                  return data;
                }
                return data;
              }
            }
          ],
      });


  });

  function toggleMenu() {
      var menuItems = document.getElementsByClassName('menu-item');
      for (var i = 0; i < menuItems.length; i++) {
          var menuItem = menuItems[i];
          menuItem.classList.toggle("hidden");
      }
  }		
</script>

<?php $this->endSection(); ?> 