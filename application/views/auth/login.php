<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SPLS - Login</title>

    <link href="<?=base_url('assets/vendor/fontawesome-free/css/all.min.css');?>" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <link href="<?=base_url('assets/css/sb-admin-2.min.css');?>" rel="stylesheet">

</head>

<body class="bg-gradient-primary">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-5 col-lg-6 col-md-4">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Login</h1>
                                    </div>
                                    <?php if(isset($message)): ?>
                                    <div class="alert alert-danger"><?php echo $message;?></div>
                                    <?php endif;?>
                                    
                                    <?php echo form_open("auth/login", ['class'=>'user']);?>
                                        <div class="form-group">
                                            <?php echo form_input(['id'=>'email','class'=>'form-control form-control-user','name'=>'identity','type'=>'email','placeholder'=>'Email','autocomplete'=>'off']); ?>
                                        </div>
                                        <div class="form-group">
                                            <?php echo form_password('password', '', ['class'=>'form-control form-control-user','placeholder'=>'Password']);?>
                                        </div>
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox small">
                                                <?php echo form_checkbox('remember', '1', false, ['id'=>'remember','class'=>'custom-control-input']);?>
                                                <?php echo form_label('Remember Me', 'remember', ['class'=>'custom-control-label']); ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php echo form_submit('submit', 'Login', ['class'=>'btn btn-primary btn-user btn-block']);?>
                                        </div>
                                    <?php echo form_close(); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="<?=base_url('assets/vendor/jquery/jquery.min.js'); ?>"></script>
    <script src="<?=base_url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js');?>"></script>
    <script src="<?=base_url('assets/vendor/jquery-easing/jquery.easing.min.js');?>"></script>
    <script src="<?=base_url('assets/js/sb-admin-2.min.js');?>"></script>

</body>

</html>