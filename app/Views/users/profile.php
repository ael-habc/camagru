<?php require APPROOT . '/views/inc/header.php'; ?>
    <div class="col-md-6 mx-auto">
        <div class="card card-body bg-light mt-5"  style="text-align: center;">
                <h4 class="m-b-20 p-b-5 b-b-default f-w-600">Profile</h4>
                <div class="row">
                    <div class="col-sm-12">
                        <h5 class="m-b-10 f-w-600">Name</h5>
                        <h6 class="text-muted f-w-400"><?php echo $_SESSION['user_name'] ?></h6>
                    </div>
                    <div class="col-sm-12">
                        <h5 class="m-b-10 f-w-600">Email</h5>
                        <h6 class="text-muted f-w-400"><?php echo $_SESSION['user_email'] ?></h6>
                    </div>
                </div>
        </div>
    <?php require APPROOT . '/views/inc/footer.php'; ?>