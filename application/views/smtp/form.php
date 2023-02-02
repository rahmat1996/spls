<?php $this->load->view('template/header');?>
<div class="container-fluid">
    <h1 class="h3 mb-3 text-gray-800">SMTPs</h1>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><?=$is_edit ? 'Edit' : 'Add'?> Data</h6>
        </div>

        <div class="card-body">
            <?php echo form_open('', array('role' => 'form')); ?>
            <?php if($is_edit) {
			    echo form_hidden('id', $smtp->id);
			} ?>

            <div class="form-group">
                <?php echo form_label('Name', 'name'); ?>
                <?php echo form_input('name', $is_edit ? $smtp->name : set_value('name'), ['class' => 'form-control', 'id' => 'name', 'autofocus' => 'autofocus', 'autocomplete' => 'off']); ?>
                <?php echo form_error('name', '<p class="text-danger">', '</p>'); ?>
            </div>

            <div class="form-group">
                <?php echo form_label('Host', 'host'); ?>
                <?php echo form_input('host', $is_edit ? $smtp->host : set_value('host'), ['class' => 'form-control', 'id' => 'host',  'autocomplete' => 'off']); ?>
                <?php echo form_error('host', '<p class="text-danger">', '</p>'); ?>
            </div>

            <div class="form-group">
                <?php echo form_label('Username', 'username'); ?>
                <?php echo form_input('username', $is_edit ? $smtp->username : set_value('username'), ['class' => 'form-control', 'id' => 'username', 'autocomplete' => 'off']); ?>
                <?php echo form_error('username', '<p class="text-danger">', '</p>'); ?>
            </div>

            <div class="form-group">
                <?php echo form_label('Password', 'password'); ?>
                <?php echo form_password('password','', ['class' => 'form-control', 'id' => 'password', 'autocomplete' => 'off']); ?>
                <?php echo form_error('password', '<p class="text-danger">', '</p>'); ?>
            </div>

            <div class="form-group">
                <?php echo form_label('Port', 'port'); ?>
                <?php echo form_input('port', $is_edit ? $smtp->port : set_value('port'), ['class' => 'form-control', 'id' => 'port', 'autocomplete' => 'off']); ?>
                <?php echo form_error('port', '<p class="text-danger">', '</p>'); ?>
            </div>

            <div class="form-group">
                <?php echo form_label('Secured By', 'secured_by'); ?>
                <?php echo form_input('secured_by', $is_edit ? $smtp->secured_by : set_value('secured_by'), ['class' => 'form-control', 'id' => 'secured_by', 'autocomplete' => 'off']); ?>
                <?php echo form_error('secured_by', '<p class="text-danger">', '</p>'); ?>
            </div>

            <div class="form-group">
                <?php echo form_label('Send Limit', 'send_limit'); ?>
                <?php echo form_input('send_limit', $is_edit ? $smtp->send_limit : set_value('send_limit'), ['class' => 'form-control', 'id' => 'send_limit', 'autocomplete' => 'off']); ?>
                <?php echo form_error('send_limit', '<p class="text-danger">', '</p>'); ?>
            </div>

            <div class="form-group">
                <?php echo form_label('Status', 'state'); ?>
                <?php echo form_dropdown('state', [0=>'Not Active',1=>'Active'], $is_edit ? $smtp->state : set_value('state'), ['id' => 'state', 'class' => 'form-control']); ?>
            </div>

            <div class="form-group">
                <?php echo form_button(['name' => 'form_submit', 'type' => 'submit', 'class' => 'btn btn-primary', 'content' => '<i class="fas fa-save"></i> Save']); ?>
                <a href="<?=site_url('smtp');?>" class="btn btn-warning"><i class="fas fa-undo"></i> Back</a>
            </div>
            <?php echo form_close();?>
        </div>
    </div>
</div>
</div>

<script>
$(document).ready(function() {
    <?php if (!empty($this->session->flashdata('notif'))) : ?>
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
    });

    Toast.fire({
        icon: '<?php echo $this->session->flashdata('notif'); ?>',
        title: '<?php echo $this->session->flashdata('notif_msg'); ?>'
    });
    <?php endif; ?>
});
</script>

<?php $this->load->view('template/footer');?>