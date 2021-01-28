<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card card-body bg-light mt-5">
            <?php flash('register_seccess'); ?>
            <h2>login</h2>
            <form action="<?php echo URLROOT;?>/users/login" method="post">
                <div class="form-group">
                    <label for="name"><sup style="color: red;">*</sup>Name :</label>
                    <input type="text" name="name" class="form-control form-control-lg <?php echo (!empty($data['name_err'])) ? 'is-invalid' : '';?>">
                    <span class="invalid-feedback"><?php echo $data['name_err'];?></span>
                </div>

                <div class="form-group">
                    <label for="password"><sup style="color: red;">*</sup>password :</label>
                    <input type="password" name="password" class="form-control form-control-lg <?php echo (!empty($data['password_err'])) ? 'is-invalid' : '';?>">
                    <span class="invalid-feedback"><?php echo $data['password_err'];?></span>
                </div>

                <div class="row">
                    <div class="col">
                        <input type="submit" value="login" class="btn btn-success btn-block">
                    </div>
                    <div class="col">
                        <a href="<?php echo URLROOT; ?>/users/register" class="btn btn-light btn-block">register</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php require APPROOT . '/views/inc/footer.php'; ?> 