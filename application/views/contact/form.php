<?php $this->load->view('template/header');?>
<div class="container-fluid">
    <h1 class="h3 mb-3 text-gray-800">Contacts</h1>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><?=$is_edit ? 'Edit' : 'Add'?> Data</h6>
        </div>

        <div class="card-body">
            <?php echo form_open('', array('role' => 'form')); ?>
            <?php if($is_edit) {
			    echo form_hidden('id', $contact->id);
			} ?>

            <div class="form-group">
                <?php echo form_label('Email', 'email'); ?>
                <?php echo form_input(['type'=>'email','name'=>'email','value'=> $is_edit ? $contact->email : set_value('email'),'id'=>'email','class'=>'form-control','autocomplete'=>'off']); ?>
                <?php echo form_error('email', '<p class="text-danger">', '</p>'); ?>
            </div>

            <div class="form-group">
                <?php echo form_label('Status', 'state'); ?>
                <?php echo form_dropdown('state', [0=>'Not Confirm',1=>'Subscribe',2=>'Not Subscribe'], $is_edit ? $contact->state : set_value('state'), ['id' => 'state', 'class' => 'form-control']); ?>
            </div>

            <div class="form-group">
                <?php echo form_button(['name' => 'form_submit', 'type' => 'submit', 'class' => 'btn btn-primary', 'content' => '<i class="fas fa-save"></i> Save']); ?>
                <a href="<?=site_url('contact');?>" class="btn btn-warning"><i class="fas fa-undo"></i> Back</a>
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