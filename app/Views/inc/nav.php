<header class="blog-header py-3">
    <div class="row flex-nowrap justify-content-between align-items-center">
      <div class="col-4 pt-1">
        <a class="blog-header-logo text-dark" id="logo" href="<?php echo URL_ROOT ?>/posts"><img src="../public/img/logo.png" height="80" width="95"></a>
      </div>
      <div class="col-3 text-center">
        
      </div>
      <?php if (isset($_SESSION['user_id'])) : ?>
        <div class="menu col-4 d-flex justify-content-end align-items-center w-25 h-auto">
          <a href="<?php echo URL_ROOT ?>/users/profile"><img class="profile-btn profile border border-dark mx-3" src="<?php echo $_SESSION['user_img'] ?>" alt="profile"></a>
          <div class="list-toggle" id="list-toggle" ><img class="list-btn profile border border-dark mx-3" src="<?php echo $_SESSION['user_img'] ?>" alt="profile"></a>
          <div class="liste rounded shadow" id="liste">
            <ul>
              <li><a class="btn btn-sm" href="<?php echo URL_ROOT ?>/users/profile"><img class="list-profile-btn profile border border-dark" src="<?php echo $_SESSION['user_img'] ?>" alt="profile"> Profile</a></li>
              <li><a class="btn btn-sm" href="<?php echo URL_ROOT ?>/posts/add"><img class="list-cam-btn" src="../public/img/camera.png"> Camera</a></li>
              <li><a class="btn btn-sm" href="<?php echo URL_ROOT ?>/users/logout" ><img class="out-btn" src="../public/img/delete.png"> Logout</a></li>
            </ul>
          </div>
          </div>
            <a href="<?php echo URL_ROOT ?>/posts/add"><img class="cam-btn" src="../public/img/camera.png"></a>
            <a class="out-link btn btn-sm mx-1" href="<?php echo URL_ROOT ?>/users/logout" ><img class="out-btn" src="../public/img/delete.png" ></a>
        </div>
      <?php else : ?>
      <div class="col-4 d-flex justify-content-end align-items-center">
        <a class="btn btn-sm btn-outline-secondary mx-2" id="log" href="<?php echo URL_ROOT ?>/users/login">Login</a>
        <a class="btn btn-sm btn-outline-secondary" id="log" href="<?php echo URL_ROOT ?>/users/signup">Signup</a>
      </div>
      <?php endif; ?>
    </div>
  </header>
  <hr style="position:relative; top: -30px;">
  <?php if (isset($_SESSION['user_id'])) : ?>
  <?php endif; ?>
