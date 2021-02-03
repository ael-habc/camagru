<?php
class Users extends Controller
{
  public function __construct()
  {
    $this->userModel = $this->model('user');
  }
  public function register()
  {
    // Check for POST
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      // Process form

      // Sanitize POST data
      $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

      // Init data
      $data = [
        'name' => trim($_POST['name']),
        'email' => trim($_POST['email']),
        'password' => trim($_POST['password']),
        'confirm_password' => trim($_POST['confirm_password']),
        'name_err' => '',
        'email_err' => '',
        'password_err' => '',
        'confirm_password_err' => '',
        'token' => ''
      ];
      $regName = "/^[a-zA-Z\d\.]+$/";
      $regEmail = "/^[a-zA-Z\d\.]+@[a-zA-Z\d]+\.[a-zA-Z]+$/";
      // Validate Email
      if (empty($data['email'])) {
        $data['email_err'] = 'Please enter email';
      } else {
        if ($this->userModel->findUserByEmail($data['email']))
          $data['email_err'] = 'Please already used';
        elseif (!preg_match($regEmail, $data['email']))
          $data['email_err'] = 'Bad email format';
      }
      // Validate Name
      if (empty($data['name'])) {
        $data['name_err'] = 'Please enter name';
      } else {
        if ($this->userModel->findUserByName($data['name']))
          $data['name_err'] = 'Please already name';
        elseif (!preg_match($regName, $data['name']))
          $data['name_err'] = 'name only accept Alphabet and numbers';
      }

      // Validate Password
      if (empty($data['password'])) {
        $data['password_err'] = 'Please enter password';
      } else {
        if (strlen($data['password']) < 6)
          $data['password_err'] = 'Password must be at least 6 characters';
        else if (!preg_match("#[0-9]+#", $data['password']))
          $data['password_err'] = 'password must contain one number atleast';
        else if (!preg_match("#[a-z]+#", $data['password']))
          $data['password_err'] = 'password must contain an one lowercase letter';
        else if (!preg_match("#[A-Z]+#", $data['password']))
          $data['password_err'] = 'password must contain an one upercase letter';
        else if (!preg_match("#\W+#", $data['password']))
          $data['password_err'] = 'password must contain an one symbole ';
      }

      // Validate Confirm Password
      if (empty($data['confirm_password'])) {
        $data['confirm_password_err'] = 'Please confirm password';
      } else {
        if ($data['password'] != $data['confirm_password']) {
          $data['confirm_password_err'] = 'Passwords do not match';
        }
      }

      // Make sure errors are empty
      if (empty($data['email_err']) && empty($data['name_err']) && empty($data['password_err']) && empty($data['confirm_password_err'])) {
        // Validated
        {
          //generete Token
          $data['token'] = bin2hex(random_bytes(10));
          //Hash Password
          $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
          //register
          if ($this->userModel->register($data)) {
            flash('register_seccess', 'You are register please verify your email');
            MailSender($data);
            header('location: ' . URLROOT . '/users/login');
          }
        }
      } else {
        // Load view with errors
        $this->view('users/register', $data);
      }
    } else {
      // Init data
      $data = [
        'name' => '',
        'email' => '',
        'password' => '',
        'confirm_password' => '',
        'name_err' => '',
        'email_err' => '',
        'password_err' => '',
        'confirm_password_err' => '',
        'token' => ''
      ];

      // Load view
      $this->view('users/register', $data);
    }
  }

  public function login()
  {
    $regName = "/^[a-zA-Z\d\.]+$/";
    //Check for Post
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $data = [
        'name' => trim($_POST['name']),
        'password' => trim($_POST['password']),
        'name_err' => '',
        'password_err' => '',
      ];
      if (empty($data['name'])) {
        $data['name_err'] = 'Please enter name';
      } elseif (!preg_match($regName, $data['name']))
        $data['name_err'] = 'Use a Valide name';
      // Validate Password
      if (empty($data['password'])) {
        $data['password_err'] = 'Please enter password';
      }
      //check for user
      if ($this->userModel->findUserByName($data['name'])) {
      } else {
        $data['name_err'] = 'user not found';
      }
      if (empty($data['email_err']) && empty($data['name_err']) && empty($data['password_err']) && empty($data['confirm_password_err'])) {
        // Validated
        // Check and set logges in user
        $loggedInUser = $this->userModel->login($data['name'], $data['password']);
        if ($loggedInUser) {
          //Create session
          if (!$this->userModel->is_confirmed($data['name'])) {
            flash("confirm error", "You should confirm your email befor login", "alert alert-danger");
            header('location: ' . URLROOT . '/users/login');
          } else {
            $this->createUserSession($loggedInUser);
          }
        } else {
          $data['password_err'] = 'Password incorrect';
          $this->view('/users/login', $data);
        }
      } else {
        // Load view with errors
        $this->view('users/login', $data);
      }
    } else {
      //init Data
      $data = [
        'name' => '',
        'password' => '',
        'name_err' => '',
        'password_err' => '',
      ];
      //Load View
      $this->view('users/login', $data);
    }
  }

  public function confirm()
  {
    $token = $_GET['token'];
    if (!$this->userModel->expToken($token))
      die("Token already expired");
    $this->userModel->confirm($token);
    $this->view('users/login');
  }
  public function createUserSession($user)
  {
    $_SESSION['user_id'] = $user->id;
    $_SESSION['user_email'] = $user->email;
    $_SESSION['user_name'] = $user->username;
    $_SESSION['user_notif'] = ($user->notification) ? 1 : 0; 
    header('location: ' . URLROOT . '/pages/index');
  }
  public function logout()
  {
    unset($_SESSION['user_id']);
    unset($_SESSION['user_email']);
    unset($_SESSION['user_name']);
    session_destroy();
    header('location: ' . URLROOT . '/users/login');
  }
  public function isloggedIn()
  {
    return (isset($_SESSION['user_id'])) ? true : false;
  }
  public function fgpassword()
  {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
      // Init data
      $data = [
        'email' => trim($_POST['email']),
        'email_err' => '',
        'token' => ''
      ];
      $regEmail = "/^[a-zA-Z\d\.]+@[a-zA-Z\d]+\.[a-zA-Z]+$/";
      // Validate Email
      if (empty($data['email'])) {
        $data['email_err'] = 'Please enter email';
      } else {
        if (!$this->userModel->findUserByEmail($data['email']))
          $data['email_err'] = 'Email not found';
        elseif (!preg_match($regEmail, $data['email']))
          $data['email_err'] = 'Bad email format';
      }
      // Make sure errors are empty
      if (empty($data['email_err'])) {
        // Validated
        if (!$this->userModel->is_confirmedByEmail($data['email'])) {
          flash("change password error", "You should confirm your account befor change your password", "alert alert-danger");
          header('location: ' . URLROOT . '/users/fgpassword');
        } else {
          //generete Token
          $data['token'] = bin2hex(random_bytes(10));
          //update token to the user
          $this->userModel->genToken($data);
          passwordMail($data);
          echo "
            <h1>Check your email for the link to reset your password</h1>
          ";
        }
      } else {
        // Load view with errors
        $this->view('users/fgpassword', $data);
      }
    } else {
      $this->view('users/fgpassword');
    }
  }


  public function changepass()
  {
    // set session and start it
    if (session_id() !== '') {
      session_destroy();
      unset($_SESSION);
      session_start();
    }
    $_SESSION['userData'] = $this->userModel->GetUserByToken($_GET['token']);
    if ($_SESSION['userData']) {
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        extract($_SESSION);
        $data = [
          'password' => trim($_POST['password']),
          'password_err' => '',
          'confirm_password' => trim($_POST['confirm_password']),
          'confirm_password_err' => '',
          'token' =>  $userData->token
        ];
        // Validate Password
        if (empty($data['password']))
          $data['password_err'] = 'Please enter password';
        else {
          if (strlen($data['password']) < 6)
            $data['password_err'] = 'Password must be at least 6 characters';
          else if (!preg_match("#[0-9]+#", $data['password']))
            $data['password_err'] = 'password must contain one number atleast';
          else if (!preg_match("#[a-z]+#", $data['password']))
            $data['password_err'] = 'password must contain an one lowercase letter';
          else if (!preg_match("#[A-Z]+#", $data['password']))
            $data['password_err'] = 'password must contain an one upercase letter';
          else if (!preg_match("#\W+#", $data['password']))
            $data['password_err'] = 'password must contain an one symbole ';
        }
        if (empty($data['confirm_password'])) {
          $data['confirm_password_err'] = 'Please confirm password';
        } else {
          if ($data['password'] != $data['confirm_password']) {
            $data['confirm_password_err'] = 'Passwords do not match';
          }
        }
        if (empty($data['password_err']) && empty($data['confirm_password_err'])) {
          $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
          if ($this->userModel->expToken($token))
            die("Token already expired");
          $this->userModel->changepass($data);

          header('location: ' . URLROOT . '/users/login');
        } else
          $this->view('users/changepass', $data);
      } else {
        $data = [
          'password' => '',
          'password_err' => '',
          'confirm_password' => '',
          'confirm_password_err' => '',
        ];
        $this->view('users/changepass', $data);
      }
    } else
      die('Token Error');
  }
  public function edit()
  {
    session_start();
    $row = $this->userModel->getUserData($_SESSION['user_name']);
    
    // Check for POST
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      // Process form
      // Sanitize POST data
      $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
      // Init data
      $data = [
        'name' => trim($_POST['name']),
        'email' => trim($_POST['email']),
        'password' => trim($_POST['password']),
        'confirm_password' => trim($_POST['confirm_password']),
        'old_password' => trim($_POST['old_password']),
        'name_err' => '',
        'email_err' => '',
        'password_err' => '',
        'confirm_password_err' => '',
      ];
      $regName = "/^[a-zA-Z\d\.]+$/";
      $regEmail = "/^[a-zA-Z\d\.]+@[a-zA-Z\d]+\.[a-zA-Z]+$/";
      // Validate Email
      if (empty($data['email'])) {
        $data['email_err'] = 'Please enter email';
      } else {
        if ($this->userModel->findUserByEmail($data['email']))
          $data['email_err'] = 'Please already used';
        elseif (!preg_match($regEmail, $data['email']))
          $data['email_err'] = 'Bad email format';
      }
      // Validate Name
      if (empty($data['name'])) {
        $data['name_err'] = 'Please enter name';
      } else {
        if ($this->userModel->findUserByName($data['name']))
          $data['name_err'] = 'Please already name';
        elseif (!preg_match($regName, $data['name']))
          $data['name_err'] = 'name only accept Alphabet and numbers';
      }

      // Validate Password
      if (empty($data['password'])) {
        $data['password_err'] = 'Please enter password';
      } else {
        if (strlen($data['password']) < 6)
          $data['password_err'] = 'Password must be at least 6 characters';
        else if (!preg_match("#[0-9]+#", $data['password']))
          $data['password_err'] = 'password must contain one number atleast';
        else if (!preg_match("#[a-z]+#", $data['password']))
          $data['password_err'] = 'password must contain an one lowercase letter';
        else if (!preg_match("#[A-Z]+#", $data['password']))
          $data['password_err'] = 'password must contain an one upercase letter';
        else if (!preg_match("#\W+#", $data['password']))
          $data['password_err'] = 'password must contain an one symbole ';
      }

      // Validate Confirm Password
      if (empty($data['confirm_password'])) {
        $data['confirm_password_err'] = 'Please confirm password';
      } else {
        if ($data['password'] != $data['confirm_password']) {
          $data['confirm_password_err'] = 'Passwords do not match';
        }
      }

      // Make sure errors are empty
      if (empty($data['email_err']) && empty($data['name_err']) && empty($data['password_err']) && empty($data['confirm_password_err'])) {
        // Validated
        {
          //generete Token
          $data['token'] = bin2hex(random_bytes(10));
          //Hash Password
          $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
          //register
          if ($this->userModel->register($data)) {
            flash('register_seccess', 'You are register please verify your email');
            MailSender($data);
            header('location: ' . URLROOT . '/users/login');
          }
        }
      } else {
        // Load view with errors
        $this->view('users/edit', $data);
      }
    } else {
      // Init data
      $data = [
        'name' => '',
        'email' => '',
        'password' => '',
        'confirm_password' => '',
        'name_err' => '',
        'email_err' => '',
        'password_err' => '',
        'confirm_password_err' => '',
        'token' => ''
      ];

      // Load view
      $this->view('users/edit', $data);
    }
  }
}
