<?php $this->load->view('template/header');?>
<div class="container-fluid">
    <h1 class="h3 mb-3 text-gray-800">Logs</h1>
    <div class="card shadow mb-4 col-xl-6 px-0">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filters</h6>
        </div>
        <div class="card-body">
            <form id="form-filter" class="form-horizontal">
                <div class="form-group">
                    <label class="control-label font-weight-bold">Status</label>
                    <div>
                        <label><input class="ckboxState" type="checkbox" name="state" value="0" checked="checked">
                            Failed</label>
                        <label><input class="ckboxState" type="checkbox" name="state" value="1" checked="checked">
                            Success</label>
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
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Date</th>
                            <th>Contact</th>
                            <th>Content</th>
                            <th>SMTP</th>
                            <th>Remarks</th>
                            <th>Status</th>
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
            url: "<?php echo site_url('log/data'); ?>",
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
                data: null,
                class: 'text-center align-middle',
                width: 145,
                sortable: true,
                searchable: true,
                render: function(data, type, row, meta) {
                    return moment.unix(data.date).format("DD-MM-YYYY hh:mm:ss")
                }
            },
            {
                data: 'contact',
                class: 'text-center align-middle'
            },
            {
                data: 'content',
                class: 'text-center align-middle'
            },
            {
                data: 'smtp',
                class: 'text-center align-middle'
            },
            {
                data: 'remarks',
                class: 'text-center align-middle'
            },
            {
                data: null,
                class: 'text-center align-middle',
                width: 80,
                sortable: false,
                searchable: false,
                render: function(data, type, row) {
                    if (data.state == '0') {
                        return "<span class='badge badge-danger'>Failed</span>";
                    } else {
                        return "<span class='badge badge-success'>Success</span>";
                    }
                }
            }
        ]
    });

    $('#btn-filter').click(function() {
        table.ajax.reload();
    });

});
</script>

<?php $this->load->view('template/footer');?>