<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card card-body bg-light mt-5">
            <?php flash('change password error'); ?>
            <h2>Forgot password</h2>
            <span class="invalid-feedback"><?php echo $data['confirm_err']; ?></span>
            <form action="<?php echo URLROOT; ?>/users/fgpassword" method="post">
                <div class="form-group">
                    <label for="email"><sup style="color: red;">*</sup>email :</label>
                    <input type="email" name="email" class="form-control form-control-lg <?php echo (!empty($data['email_err'])) ? 'is-invalid' : ''; ?>">
                    <span class="invalid-feedback"><?php echo $data['email_err']; ?></span>
                </div>

                <div class="row">
                    <div class="col">
                        <input type="submit" value="send email" class="btn btn-info btn-block">
                    </div>
            </form>
        </div>
    </div>
</div>
<?php require APPROOT . '/views/inc/footer.php'; ?>