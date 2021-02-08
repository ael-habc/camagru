<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-3">
  <a class="navbar-brand" href="<?php echo URLROOT; ?>"><?php echo SITENAME; ?></a>
  <div class="container-fluid">
    </button>
  </div>
  <div class="navbar-collapse">
    <ul class="navbar-nav">
      <li class="nav-item ">
        <a class="nav-link" aria-current="page" href="<?php echo URLROOT; ?>">Home</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="<?php echo URLROOT; ?>/pages/about">About</a>
      </li>
    </ul>
    <ul class="navbar-nav">
      <?php if (isset($_SESSION['user_id'])) { ?>

        <li class="nav-item ">
          <a class="nav-link mb-0 h5" aria-current="page" href="<?php echo URLROOT; ?>/users/profile"><?php session_start(); echo $_SESSION['user_name'] ?></a>
        </li>
        <li class="nav-item ">
          <a class="nav-link" aria-current="page" href="<?php echo URLROOT; ?>/users/camera">Camera</a>
        </li>
        <li class="nav-item ">
          <a class="nav-link" aria-current="page" href="<?php echo URLROOT; ?>/users/edit">Edit</a>
        </li>
        <li class="nav-item ">
          <a class="nav-link" aria-current="page" href="<?php echo URLROOT; ?>/users/logout">Logout</a>
        </li>
    </ul>
</nav>
<? }else{ ?>
<li class="nav-item ">
  <a class="nav-link" aria-current="page" href="<?php echo URLROOT; ?>/users/register">Register</a>
</li>
<li class="nav-item">
  <a class="nav-link" aria-current="page" href="<?php echo URLROOT; ?>/users/login">login</a>
</li>
</ul>
</div>
</nav>
<?php } ?>