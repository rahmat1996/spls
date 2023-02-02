<?php $this->load->view('template/header');?>
<div class="container-fluid">
    <h1 class="h3 mb-3 text-gray-800">Groups</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Edit Data</h6>
        </div>

        <div class="card-body">

            <?php echo form_open(current_url());?>

            <div class="form-group">
                <?php echo form_label('Group Name', 'group_name'); ?>
                <?php echo form_input($group_name);?>
                <?php echo form_error('group_name', '<p class="text-danger">', '</p>'); ?>
            </div>

            <p>
                <?php echo form_label('Description', 'description'); ?>
                <?php echo form_input($group_description);?>
                <?php echo form_error('description', '<p class="text-danger">', '</p>'); ?>
            </p>

            <div class="form-group">
                <?php echo form_button(['name' => 'form_submit', 'type' => 'submit', 'class' => 'btn btn-primary', 'content' => '<i class="fas fa-save"></i> Save']); ?>
                <a href="<?=site_url('auth');?>" class="btn btn-warning"><i class="fas fa-undo"></i> Back</a>
            </div>

            <?php echo form_close();?>

        </div>
    </div>
    <?php $this->load->view('template/footer'); ?>