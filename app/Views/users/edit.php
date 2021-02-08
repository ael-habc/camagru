<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card card-body bg-light mt-5">
            <h2>Edit your data</h2>
            <form action="<?php echo URLROOT; ?>/users/edit" method="post">
                <?php flash("No change"); ?>
                <div class="form-group">
                    <label for="name">Name :</label>
                    <input type="text" name="name" class="form-control form-control-lg <?php echo (!empty($data['name_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $_SESSION['user_name'] ?>">
                    <span class="invalid-feedback"><?php echo $data['name_err']; ?></span>
                </div>
                <div class="form-group">
                    <label for="email">Email :</label>
                    <input type="email" name="email" class="form-control form-control-lg <?php echo (!empty($data['email_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $_SESSION['user_email'] ?>">
                    <span class="invalid-feedback"><?php echo $data['email_err']; ?></span>
                </div>
                <div class="form-group">
                    <label for="password"> New password :</label>
                    <input type="password" name="password" class="form-control form-control-lg <?php echo (!empty($data['password_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['password']; ?>">
                    <span class="invalid-feedback"><?php echo $data['password_err']; ?></span>
                </div>
                <div class="form-group">
                    <label for="confirm_password">confirm password :</label>
                    <input type="password" name="confirm_password" class="form-control form-control-lg <?php echo (!empty($data['confirm_password_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['confirm_password']; ?>">
                    <span class="invalid-feedback"><?php echo $data['confirm_password_err']; ?></span>
                </div>
                <div class="form-group">
                    <?php if ($_SESSION['user_notif']) { ?>
                        <input type="checkbox" name="notif" value="1" checked>
                        <label class="form-check-label" for="notif">Receive notifications by email</label>
                    <?php } else { ?>
                        <input type="checkbox" name="notif" value="0" unchecked>
                        <label class="form-check-label" for="notif">Receive notifications by email</label>
                    <?php } ?>
                </div>
                <div class="form-group">
                    <label for="old_password">old password :</label>
                    <input type="password" name="old_password" class="form-control form-control-lg <?php echo (!empty($data['old_password_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['old_password']; ?>">
                    <span class="invalid-feedback"><?php echo $data['old_password_err']; ?></span>
                </div>
                <div class="row">
                    <div class="col">
                        <input type="submit" value="Edit" class="btn btn-success btn-block">
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>
<?php require APPROOT . '/views/inc/footer.php'; ?>