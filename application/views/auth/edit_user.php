<?php $this->load->view('template/header');?>
<div class="container-fluid">
    <h1 class="h3 mb-3 text-gray-800">Users</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Edit Data</h6>
        </div>

        <div class="card-body">

            <?php echo form_open(uri_string());?>

            <?php echo form_hidden('id', $user->id);?>
            <?php echo form_hidden($csrf); ?>

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

            <div class="form-group">
                <?php echo form_label('Company', 'company'); ?>
                <?php echo form_input($company);?>
                <?php echo form_error('company', '<p class="text-danger">', '</p>'); ?>
            </div>

            <div class="form-group">
                <?php echo form_label('Phone', 'phone'); ?>
                <?php echo form_input($phone);?>
                <?php echo form_error('phone', '<p class="text-danger">', '</p>'); ?>
            </div>

            <div class="form-group">
                <?php echo form_label('Password', 'password'); ?>
                <?php echo form_input($password);?>
                <small id="password_help" class="form-text text-muted">Fill it if you want to change password.</small>
                <?php echo form_error('password', '<p class="text-danger">', '</p>'); ?>
            </div>

            <div class="form-group">
                <?php echo form_label('Confirm Password', 'password_confirm'); ?>
                <?php echo form_input($password_confirm);?>
                <small id="password_confirm_help" class="form-text text-muted">Fill it same with password if you want to
                    change password.</small>
                <?php echo form_error('password_confirm', '<p class="text-danger">', '</p>'); ?>
            </div>

            <?php if ($this->ion_auth->is_admin()): ?>
            <div class="form-group">
                <?php echo form_label('Groups', 'groups'); ?>
                <br />
                <?php foreach ($groups as $group):?>
                <label class="checkbox">
                    <input type="checkbox" name="groups[]" value="<?php echo $group['id'];?>"
                        <?php echo (in_array($group, $currentGroups)) ? 'checked="checked"' : null; ?>>
                    <?php echo htmlspecialchars($group['name'],ENT_QUOTES,'UTF-8');?>
                </label>
                <?php endforeach?>
            </div>
            <?php endif ?>

            <div class="form-group">
                <?php echo form_button(['name' => 'form_submit', 'type' => 'submit', 'class' => 'btn btn-primary', 'content' => '<i class="fas fa-save"></i> Save']); ?>
                <a href="<?=site_url('auth');?>" class="btn btn-warning"><i class="fas fa-undo"></i> Back</a>
            </div>

            <?php echo form_close();?>
        </div>
    </div>

    <?php $this->load->view('template/footer'); ?>