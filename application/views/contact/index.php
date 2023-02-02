<?php $this->load->view('template/header');?>
<div class="container-fluid">
    <h1 class="h3 mb-3 text-gray-800">Contacts</h1>
    <div class="card shadow mb-4 col-xl-6 px-0">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filters</h6>
        </div>
        <div class="card-body">
            <form id="form-filter" class="form-horizontal">
                <div class="form-group">
                    <label class="control-label font-weight-bold">Status</label>
                    <div>
                        <label><input class="ckboxState" type="checkbox" name="state" value="0" checked="checked"> Not
                            Confirm</label>
                        <label><input class="ckboxState" type="checkbox" name="state" value="1" checked="checked">
                            Subscribe</label>
                        <label><input class="ckboxState" type="checkbox" name="state" value="2" checked="checked"> Not
                            Subscribe</label>
                    </div>
                </div>
                <div class="form-group">
                    <div>
                        <button type="button" id="btn-filter" class="btn btn-primary">Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Lists</h6>
        </div>
        <div class="card-body">
            <div class="mb-4">
                <a href="<?=site_url('contact/add');?>" class="btn btn-success"><i class='fas fa-plus'></i> New Data</a>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Menu</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>

<script>
$(document).ready(function() {
    // Datatable load
    table = $("table#dataTable").DataTable({
        responsive: true,
        autoWidth: false,
        serverSide: true,
        processing: true,
        order: [
            [1, "asc"]
        ],

        ajax: {
            url: "<?php echo site_url('contact/data'); ?>",
            dataSrc: "data",
            type: "POST",
            "data": function(data) {
                state = [];
                $('.ckboxState:checked').each(function() {
                    state.push(this.value);
                })
                data.state = state;
            }
        },
        columns: [{
                data: null,
                width: 50,
                sortable: false,
                searchable: false,
                class: 'text-center align-middle',
                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {
                data: 'email',
                class: 'text-left align-middle'
            },
            {
                data: null,
                class: 'text-center align-middle',
                width: 80,
                sortable: false,
                searchable: false,
                render: function(data, type, row) {
                    if (data.state == '0') {
                        return "<span class='badge badge-warning'>Not Confirm</span>";
                    } else if (data.state == '1') {
                        return "<span class='badge badge-success'>Subscribe</span>";
                    } else {
                        return "<span class='badge badge-danger'>Not Subscribe</span>";
                    }
                }
            },
            {
                data: null,
                sortable: false,
                searchable: false,
                class: 'text-center align-middle',
                width: 150,
                render: function(data, type, row) {
                    var btn_menu = "";
                    btn_menu +=
                        "<a class='btn btn-primary btn-sm btn_edit' href='<?=site_url('contact/edit/'); ?>" +
                        data.id + "' title='Edit'><i class='fas fa-edit'></i> Edit</a> "
                    btn_menu +=
                        "<a class='btn btn-danger btn-sm btn_delete' href='<?=site_url('contact/delete/'); ?>" +
                        data.id + "' title='Delete'><i class='fas fa-trash-alt'></i> Delete</a>"
                    return btn_menu;
                }
            }
        ]
    });

    $('#btn-filter').click(function() {
        table.ajax.reload();
    });

    // popup the message warning before delete data.
    $("table#dataTable").on("click", ".btn_delete", function(event) {
        event.preventDefault();
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.value) {
                window.location.href = event.currentTarget.href;
            }
        });
    });

    // Script execute if there are message from server after insert or update data.
    <?php if (!empty($this->session->flashdata('notif'))) :?>
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
    });

    Toast.fire({
        icon: '<?php echo $this->session->flashdata('notif');?>',
        title: '<?php echo $this->session->flashdata('notif_msg');?>'
    });
    <?php endif;?>
});
</script>

<?php $this->load->view('template/footer');?>