<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card card-body bg-light mt-5">
            <h2>Change pass</h2> 
            <span class="invalid-feedback"><?php echo $data['confirm_err'];?></span>
            <form method="POST">
                <div class="form-group">
                    <label for="password"><sup style="color: red;">*</sup>password :</label>
                    <input type="password" name="password" class="form-control form-control-lg <?php echo (!empty($data['password_err'])) ? 'is-invalid' : '';?>">
                    <span class="invalid-feedback"><?php echo $data['password_err'];?></span>
                </div>
                    <!-- <input type="hidden" name="token" class="form-control form-control-lg" value="<?php echo $_GET['token']?>"> -->
                <div class="form-group">
                    <label for="confirm_password"><sup style="color: red;">*</sup>confirm password :</label>
                    <input type="password" name="confirm_password" class="form-control form-control-lg <?php echo (!empty($data['confirm_password_err'])) ? 'is-invalid' : '';?>" value="<?php echo $data['confirm_password']; ?>">
                    <span class="invalid-feedback"><?php echo $data['confirm_password_err'];?></span>
                </div>
                <div class="row">
                    <div class="col">
                        <input type="submit" value="Change Password" class="btn btn-info btn-block">
                </div>
            </form>
        </div>
    </div>
</div>
<?php require APPROOT . '/views/inc/footer.php'; ?>