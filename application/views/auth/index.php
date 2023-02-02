<?php $this->load->view('template/header');?>

<div class="container-fluid">
    <h1 class="h3 mb-3 text-gray-800">Users</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Lists</h6>
        </div>
        <div class="card-body">
            <div id="infoMessage"><?php echo $message;?></div>
            <div class="mb-4">
                <?php echo anchor('auth/create_user', "<i class='fas fa-plus'></i> New User",['class'=>'btn btn-success'])?>
                |
                <?php echo anchor('auth/create_group',"<i class='fas fa-plus'></i> New Group",['class'=>'btn btn-success'])?>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">First Name</th>
                            <th class="text-center">Last Name</th>
                            <th class="text-center">Email</th>
                            <th class="text-center">Groups</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Menu</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user):?>
                        <tr>
                            <td class="text-center"></td>
                            <td class="text-center">
                                <?php echo htmlspecialchars($user->first_name, ENT_QUOTES, 'UTF-8');?></td>
                            <td class="text-center">
                                <?php echo htmlspecialchars($user->last_name, ENT_QUOTES, 'UTF-8');?></td>
                            <td class="text-center"><?php echo htmlspecialchars($user->email, ENT_QUOTES, 'UTF-8');?>
                            </td>
                            <td class="text-center">
                                <?php foreach ($user->groups as $group):?>
                                <?php echo anchor("auth/edit_group/".$group->id, htmlspecialchars($group->name, ENT_QUOTES, 'UTF-8'),['class'=>'badge badge-success']) ;?><br />
                                <?php endforeach?>
                            </td>
                            <td class="text-center">
                                <?php echo ($user->active) ? anchor("auth/deactivate/".$user->id, 'Active',['class'=>'badge badge-success']) : anchor("auth/activate/". $user->id, 'Inactive',['class'=>'badge badge-danger']);?>
                            </td>
                            <td class="text-center">
                                <?php echo anchor("auth/edit_user/".$user->id, "<i class='fas fa-edit'></i> Edit",['class'=>'btn btn-sm btn-primary']) ;?>
                            </td>
                        </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>




</div>

<script>
$(document).ready(function() {

    table = $("table#dataTable").DataTable({
        responsive: true,
        columnDefs: [{
            searchable: false,
            orderable: false,
            targets: [0, 4, 5, 6]
        }],
        order: [
            [1, 'asc']
        ],
        fnRowCallback: function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
            $('td:eq(0)', nRow).html(iDisplayIndexFull + 1);
        }
    });

});
</script>

<?php $this->load->view('template/footer'); ?>