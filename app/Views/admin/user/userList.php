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

    <?= empty($message) ? '' : $message ?>
    
    <div class="card">
      <div class="card-header">
        <a href="<?= adminURL('admin/userlist/add') ?>" class="btn btn-primary font-weight-bold"><i class="fas fa-plus"></i>&nbsp;Add</a>
      </div>
      <div class="card-body">
        <table id="userList" class="display table table-bordered table-hover table-striped" style="width:100%">
          <thead>
              <tr>
                  <th>No</th>
                  <th>Username</th>
                  <th>First Name</th>
                  <th>Email</th>
                  <th>Groups</th>
                  <th>Active</th>
                  <th>Option</th>
              </tr>
          </thead>
        </table>        
      </div>
      <div class="card-footer">
        <!-- <a href="<?= adminURL('admin/userlist/add') ?>" class="btn btn-primary font-weight-bold"><i class="fas fa-plus"></i>&nbsp;Add</a> -->
      </div>
    </div>

    <!-- Default box -->
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Data Dump</h3>

        <div class="card-tools">
          <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
            <i class="fas fa-minus"></i>
          </button>
          <button type="button" class="btn btn-tool" data-card-widget="remove" data-toggle="tooltip" title="Remove">
            <i class="fas fa-times"></i>
          </button>
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
<script>
  $.ajaxSetup( {
    headers: {
      _CSRF_HEADER: _CSRF_HASH,
    }
  });

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
      "ajax": _ADMIN_SITE_URL + "/userlist/getdata",
      "columns": [
        { data: 0 },
        { data: 1 },
        { data: 2 },
        { data: 3 },
        { 
          data: 4,
          render: function(data, type, row, meta) {
            let html = [];
            if(type === 'display'){           
              html.push('<div class="btn-group">');
              for(let i = 0; i < data.length; i++){
                let id = data[i]['id'];
                let name = data[i]['name'];
                html.push(`<a onClick="getDetailUserGroups(this)" data-id="${id}" class="btn bg-gray btn-sm">${name}</a>`);
              }
              html.push('</div>');

              return html.join('\n');
            }
            return data;
          }
        },
        {
          data: null,
          render: function(data,type,row,meta){
            let id = data[6];
            if(type === 'display'){
              if(data[5] == 1){
                return `<button onClick="deactivate(this)" data-id="${id}" class="btn btn-sm bg-success mx-auto">Active</button>`
              } else {
                return `<button onClick="activate(this)" data-id="${id}" class="btn btn-sm bg-danger mx-auto">Inactive</button>`
              }
            }
            return data;
          }
        },
        {
          data: null,
          render: function(data, type){
            const ID = data[6];
            const USERNAME = data[1];

            if(type === 'display'){
              let html = [];

              html.push(`<div class="btn-group">`);
              html.push(`<button onClick="getDetail(this)" class="btn btn-info btn-sm" data-id="${ID}" data-username="${USERNAME}"><i class="fas fa-search"></i></button>`);
              html.push(`<a class="btn bg-purple btn-sm" href="${_ADMIN_SITE_URL}/userlist/edit/${ID}"><i class="fas fa-edit"></i></a>`);
              html.push(`<button onClick="deleteData(this)" data-id="${ID}" data-username="${USERNAME}" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>`);
              html.push(`</div>`);

              return html.join('\n');
            }
            return data;
          }
        }
      ],
      "initComplete": function () {
        // Event Callback setelah selesai render data
      },
    });
  });

  function reloadTable() {
    $('#userList').DataTable().ajax.reload();
  }
  
  function getDetailUserGroups(el) {
    const ID = el.getAttribute('data-id');

    let requestOptions = {
      method: 'GET',
      mode: 'same-origin',
      redirect: 'follow',
      // include credentials to apply cookies from browser window
      credentials: 'same-origin', // 'include',
      headers: new Headers()
    };
    requestOptions.headers.append("X-Requested-With", "XMLHttpRequest");
    requestOptions.headers.append(_CSRF_HEADER, _CSRF_HASH);
    const URI = new URL(__ADMIN_PREFIX__ + '/admin/usergroups/detail/' + ID, _BASE_URL);
    const request = new Request(URI, requestOptions);

    fetch(request).then(res => res.json()).then(res => {
      let data = res['data'];
      let bhtml = [];
      let active = ``;

      if(data['active'] == 1){
        active = `<div class="badge bg-success p-2 mx-auto">Active</div>`
      } else {
        active = `<div class="badge bg-danger p-2 mx-auto">Inactive</div>`
      }

      bhtml.push(`<table class="table table-bordered">`);
      bhtml.push(`<tbody>`);
      bhtml.push(`<tr>`);
      bhtml.push(`<td>Name</td>`);
      bhtml.push(`<td>${data['name']}</td>`);
      bhtml.push(`</tr>`);
      bhtml.push(`<tr>`);
      bhtml.push(`<td>Description</td>`);
      bhtml.push(`<td>${data['description']}</td>`);
      bhtml.push(`</tr>`);
      bhtml.push(`</tbody>`);
      bhtml.push(`</table>`);

      Swal.fire({
        showCloseButton:true,
        showConfirmButton: false,
        html: bhtml.join('\n')
      });
    }).catch(err => {
      console.error(err);
    })    
  }

  function getDetail(el) {
    const ID = el.getAttribute('data-id');
    const Username = el.getAttribute('data-username');

    let requestOptions = {
      method: 'GET',
      mode: 'same-origin',
      redirect: 'follow',
      // include credentials to apply cookies from browser window
      credentials: 'same-origin', // 'include',
      headers: new Headers()
    };
    requestOptions.headers.append("X-Requested-With", "XMLHttpRequest");
    requestOptions.headers.append(_CSRF_HEADER, _CSRF_HASH);
    const URI = new URL(__ADMIN_PREFIX__ + '/admin/userlist/getdetail/' + ID, _BASE_URL);
    const request = new Request(URI, requestOptions);

    fetch(request).then(res => res.json()).then(res => {
      let data = res['data'];
      let bhtml = [];
      let active = ``;

      if(data['active'] == 1){
        active = `<div class="badge bg-success p-2 mx-auto">Active</div>`
      } else {
        active = `<div class="badge bg-danger p-2 mx-auto">Inactive</div>`
      }

      bhtml.push(`<table class="table table-bordered">`);
      bhtml.push(`<tbody>`);
      bhtml.push(`<tr>`);
      bhtml.push(`<td>Username</td>`);
      bhtml.push(`<td>${data['username']}</td>`);
      bhtml.push(`</tr>`);
      bhtml.push(`<tr>`);
      bhtml.push(`<td>First Name</td>`);
      bhtml.push(`<td>${data['first_name']}</td>`);
      bhtml.push(`</tr>`);
      bhtml.push(`<tr>`);
      bhtml.push(`<td>Last Name</td>`);
      bhtml.push(`<td>${data['last_name']}</td>`);
      bhtml.push(`</tr>`);
      bhtml.push(`<tr>`);
      bhtml.push(`<td>Email</td>`);
      bhtml.push(`<td>${data['email']}</td>`);
      bhtml.push(`</tr>`);
      bhtml.push(`<tr>`);
      bhtml.push(`<td>Status</td>`);
      bhtml.push(`<td>${active}</td>`);
      bhtml.push(`</tr>`);
      bhtml.push(`<tr>`);
      bhtml.push(`<td>Phone</td>`);
      bhtml.push(`<td>${data['phone']}</td>`);
      bhtml.push(`</tr>`);
      bhtml.push(`<tr>`);
      bhtml.push(`<td>Company</td>`);
      bhtml.push(`<td>${data['company']}</td>`);
      bhtml.push(`</tr>`);
      bhtml.push(`</tbody>`);
      bhtml.push(`</table>`);

      Swal.fire({
        showCloseButton:true,
        showConfirmButton: false,
        html: bhtml.join('\n')
      });
    }).catch(err => {
      console.error(err);
    })    
  }

  function deleteData(el) {
    const ID = el.getAttribute('data-id');
    const Username = el.getAttribute('data-username');

    const swalWithBootstrapButtons = Swal.mixin({
      customClass: {
        confirmButton: 'btn btn-success m-2',
        cancelButton: 'btn btn-danger m-2'
      },
      buttonsStyling: false
    })

    swalWithBootstrapButtons.fire({
      title: 'Apakah anda yakin?',
      text: "Anda tidak akan dapat mengembalikan ini!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes, delete!',
      cancelButtonText: 'Cancel',
      reverseButtons: true
    }).then((result) => {
      if (result.isConfirmed) {
        let requestOptions = {
            method: 'DELETE',
            mode: 'same-origin',
            redirect: 'follow',
            // include credentials to apply cookies from browser window
            credentials: 'same-origin', // 'include',
            headers: new Headers()
        };
        requestOptions.headers.append("X-Requested-With", "XMLHttpRequest");
        requestOptions.headers.append(_CSRF_HEADER, _CSRF_HASH);
        const URI = new URL(__ADMIN_PREFIX__ + '/admin/userlist/delete/' + ID, _BASE_URL);
        const request = new Request(URI, requestOptions);

        fetch(request)
          .then((res)=> {return res.json();})
          .then(res => {
            if(res.status === 200) {
              let messages = 'Your file has been deleted.';
              if(res.messages){
                messages = res.messages;
              }
              swalWithBootstrapButtons.fire(
                'Deleted!',
                `${messages}`,
                'success'
              )
              reloadTable();
            } else {
              let messages;
              if(res.messages){
                messgaes = res.messages.error
              } else {
                messages = res.message
              }
              swalWithBootstrapButtons.fire(
                `ERROR ${res.error}!`,
                `${messages}`,
                'error'
              )
            }
          })
          .catch(res =>{
            swalWithBootstrapButtons.fire(
              'ERROR',
              `${res}`,
              'error'
            )
          });
      } else if (
        /* Read more about handling dismissals below */
        result.dismiss === Swal.DismissReason.cancel
      ) {
        swalWithBootstrapButtons.fire(
          'Cancelled',
          'Data anda tersimpan dengan aman :)',
          'error'
        )
      }
    })
  }

  function activate(el) {
    const ID = el.getAttribute('data-id');

    const swalWithBootstrapButtons = Swal.mixin({
      customClass: {
        confirmButton: 'btn btn-success m-2',
        cancelButton: 'btn btn-danger m-2'
      },
      buttonsStyling: false
    })

    swalWithBootstrapButtons.fire({
      title: 'Apakah anda yakin?',
      text: "Anda ingin mengaktifkan pengguna ini!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes, Activate!',
      cancelButtonText: 'Cancel',
      reverseButtons: true
    }).then((result) => {
      if (result.isConfirmed) {
        let requestOptions = {
            method: 'POST',
            mode: 'same-origin',
            redirect: 'follow',
            // include credentials to apply cookies from browser window
            credentials: 'same-origin', // 'include',
            headers: new Headers()
        };
        requestOptions.headers.append("X-Requested-With", "XMLHttpRequest");
        requestOptions.headers.append(_CSRF_HEADER, _CSRF_HASH);

        const URI = new URL(__ADMIN_PREFIX__ + '/admin/userlist/activate/' + ID, _BASE_URL);
        const request = new Request(URI, requestOptions);

        fetch(request)
          .then((res)=> {return res.json();})
          .then(res => {
            if(res.status === 200) {
              let messages = 'User telah diaktifkan';
              if(res.messages){
                messages = res.messages;
              }
              swalWithBootstrapButtons.fire(
                'Activated!',
                `${messages}`,
                'success'
              )
              reloadTable();
            } else {
              let messages;
              if(res.messages){
                messgaes = res.messages.error
              } else {
                messages = res.message
              }
              swalWithBootstrapButtons.fire(
                `ERROR ${res.error}!`,
                `${messages}`,
                'error'
              )
            }
          })
          .catch(res =>{
            swalWithBootstrapButtons.fire(
              'ERROR',
              `${res}`,
              'error'
            )
          });
      } else if (
        /* Read more about handling dismissals below */
        result.dismiss === Swal.DismissReason.cancel
      ) {
        swalWithBootstrapButtons.fire(
          'Cancelled',
          'User tidak diaktifkan.',
          'error'
        )
      }
    })
  }

  function deactivate(el) {
    const ID = el.getAttribute('data-id');

    const swalWithBootstrapButtons = Swal.mixin({
      customClass: {
        confirmButton: 'btn btn-success m-2',
        cancelButton: 'btn btn-danger m-2'
      },
      buttonsStyling: false
    })

    swalWithBootstrapButtons.fire({
      title: 'Apakah anda yakin?',
      text: "Anda ingin menonaktifkan pengguna ini!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes, Deactivate!',
      cancelButtonText: 'Cancel',
      reverseButtons: true
    }).then((result) => {
      if (result.isConfirmed) {
        // Form Data
        const formData = new FormData();
        formData.append('id', ID);

        // RequestOption
        const requestOptions = {
          method: 'POST',
          mode: 'same-origin',
          redirect: 'follow',
          // include credentials to apply cookies from browser window
          credentials: 'same-origin', // 'include',
          headers: new Headers(),
          body: formData
        };
        requestOptions.headers.append("X-Requested-With", "XMLHttpRequest");
        requestOptions.headers.append(_CSRF_HEADER, _CSRF_HASH);

        // Rquest Builder
        const URI = new URL(__ADMIN_PREFIX__ + '/admin/userlist/deactivate/' + ID, _BASE_URL);
        const request = new Request(URI, requestOptions);

        fetch(request)
          .then((res)=> {return res.json();})
          .then(res => {
            if(res.status === 200) {
              let messages = 'User telah dinonaktifkan';
              if(res.messages){
                messages = res.messages;
              }
              swalWithBootstrapButtons.fire(
                'Deactivated!',
                `${messages}`,
                'success'
              )
              reloadTable();
            } else {
              let messages;
              if(res.messages){
                messgaes = res.messages.error
              } else {
                messages = res.message
              }
              swalWithBootstrapButtons.fire(
                `ERROR ${res.error}!`,
                `${messages}`,
                'error'
              )
            }
          })
          .catch(res =>{
            swalWithBootstrapButtons.fire(
              'ERROR',
              `${res}`,
              'error'
            )
          });
      } else if (
        /* Read more about handling dismissals below */
        result.dismiss === Swal.DismissReason.cancel
      ) {
        swalWithBootstrapButtons.fire(
          'Cancelled',
          'User tidak diaktifkan.',
          'error'
        )
      }
    })
  }
</script>

<?php $this->endSection(); ?> 