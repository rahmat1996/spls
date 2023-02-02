<?php $this->load->view('template/header');?>
<div class="container-fluid">
    <h1 class="h3 mb-3 text-gray-800">Users</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Add Data</h6>
        </div>

        <div class="card-body">

            <?php echo form_open("auth/create_user");?>

            <div class="form-group">
                <?php echo form_label('First Name', 'first_name'); ?>
                <?php echo form_input($first_name);?>
                <?php echo form_error('first_name', '<p class="text-danger">', '</p>'); ?>
            </div>

            <div class="form-group">
                <?php echo form_label('Last Name', 'last_name'); ?>
                <?php echo form_input($last_name);?>
                <?php echo form_error('last_name', '<p class="text-danger">', '</p>'); ?>
            </div>

            <?php if($identity_column!=='email'): ?>
            <?php echo form_label('Identity','identity'); ?>
            <?php echo form_input($identity);?>
            <?php echo form_error('identity', '<p class="text-danger">', '</p>'); ?>
            <?php endif;?>

            <div class="form-group">
                <?php echo form_label('Company', 'company'); ?>
                <?php echo form_input($company);?>
                <?php echo form_error('company', '<p class="text-danger">', '</p>'); ?>
            </div>

            <div class="form-group">
                <?php echo form_label('Email', 'email'); ?>
                <?php echo form_input($email);?>
                <?php echo form_error('email', '<p class="text-danger">', '</p>'); ?>
            </div>

            <div class="form-group">
                <?php echo form_label('Phone', 'phone'); ?>
                <?php echo form_input($phone);?>
                <?php echo form_error('phone', '<p class="text-danger">', '</p>'); ?>
            </div>

            <div class="form-group">
                <?php echo form_label('Password', 'password'); ?>
                <?php echo form_input($password);?>
                <?php echo form_error('password', '<p class="text-danger">', '</p>'); ?>
            </div>

            <div class="form-group">
                <?php echo form_label('Confirm Password', 'password_confirm'); ?>
                <?php echo form_input($password_confirm);?>
                <?php echo form_error('password_confirm', '<p class="text-danger">', '</p>'); ?>
            </div>

            <div class="form-group">
                <?php echo form_button(['name' => 'form_submit', 'type' => 'submit', 'class' => 'btn btn-primary', 'content' => '<i class="fas fa-save"></i> Save']); ?>
                <a href="<?=site_url('auth');?>" class="btn btn-warning"><i class="fas fa-undo"></i> Back</a>
            </div>

            <?php echo form_close();?>

        </div>
    </div>
</div>

<?php $this->load->view('template/footer'); ?>