<?php

    class Users extends Controller{

        public function __construct()
        {
            $this->userModel = $this->model('User');
            $this->postModel = $this->model('Post');
            $_SESSION['user_img'] = (file_exists($_SESSION['user_img'])) ? $_SESSION['user_img'] : 'https://www.washingtonfirechiefs.com/Portals/20/EasyDNNnews/3584/img-blank-profile-picture-973460_1280.png';
        }

        public function signup() {
            if ($_SERVER['REQUEST_METHOD'] == 'POST')
            {
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                $token = openssl_random_pseudo_bytes(16);
                $token = bin2hex($token);
                $data = [
                    'fullname' => trim($_POST['fullname']),
                    'email' => trim($_POST['email']),
                    'username' => trim($_POST['username']),
                    'password' => trim($_POST['password']),
                    'confirm_pwd' => trim($_POST['confirmPwd']),
                    'token' => $token,
                    'err_fullname' => '',
                    'err_email' => '',
                    'err_username' => '',
                    'err_password' => '',
                    'err_confirmPwd' => ''
                ];

                if (empty($data['fullname']))
                    $data['err_fullname'] = 'please enter fullname !!';
                if (empty($data['email']))
                    $data['err_email'] = 'please enter email !!';
                else if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL))
                    $data['err_email'] = 'Invalid email !!';
                else
                {
                    if($this->userModel->findUsrByEmail($data['email']))
                        $data['err_email'] = 'Email is already taken !!';
                }
                if (empty($data['username']))
                    $data['err_username'] = 'please enter username !!';
                else
                {
                    if($this->userModel->findUsrByUsername($data['username']))
                        $data['err_username'] = 'Username is already taken !!';
                }
                if (empty($data['password']))
                    $data['err_password'] = 'please enter password !!';
                else if (strlen($data['password']) < 6)
                    $data['err_password'] = 'Password must be at least 6 characters';
                else if (!preg_match('@[A-Z]@', $data['password']))
                    $data['err_password'] = 'Password must contain an upper case';
                else if (!preg_match('@[a-z]@', $data['password']))
                    $data['err_password'] = 'Password must contain a  lower case';
                else if (!preg_match('@[0-9]@', $data['password']))
                    $data['err_password'] = 'Password must contain a number';
                if ($data['password'] != $data['confirm_pwd'])
                    $data['err_confirmPwd'] = 'Passwords do not match !!';

                    if (empty($data['err_fullname']) && empty($data['err_email']) && empty($data['err_username']) &&
                    empty($data['err_password']) && empty($data['err_confirmPwd']))
                {
                    $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                    if ($this->userModel->signup($data))
                    {
                        $to_email = $data['email'];
                        $subject = "Verify your email";
                        $token = $data['token'];
                        $body = '<div class="email-container" style="background-color: whitesmoke; width: 700px; height: 500px;padding: 20px;">
                        <div class="title" style=\'color: #0C1117; text-align: center; font-family: billabong;font-size: 200%;\'><h1>Camagru</h1></div>
                        <div class="welcome"><h2 style=\'color: #0C1117; text-align: left; font-family: "Gill Sans", sans-serif;\'>Welcome '.$data['username'].',</h2></div>
                        <div class="reset"><h3 style=\'color: #0C1117; text-align: left; font-family: "Gill Sans", sans-serif;\'>Verify your account</h3></div>
                        <div class="body"><p style=\'color: #0C1117; text-align: left; font-family: "Gill Sans", sans-serif;\'>
                        
                        <br/>
                    
                        To verify your camagru account , please follow the link below:<br/>
                        <a href="'.URL_ROOT.'/users/verification/?token='.$token.'">click here.</a>
                        <br/>
                        <br/>

                        <br/>
                        <br/>
                        The CAMAGRU Team.
                        </p>
                        </div>
                        </div>';
                        $headers = "Content-type:text/html;charset=UTF-8" . "\r\n";
                        $headers .= 'From: <smoudene@Camagru.ma>' . "\r\n";    
                        if (mail($to_email, $subject, $body, $headers))
                                pop_up('signup_ok', 'Welcome to camagru, Verify your email to login');
                            else
                                pop_up('signup_ok', 'we couldnt send the email verification, please retry', 'alert alert-danger');
                            redirect('users/login');   
                    }
                    else
                        die('wrong');
                }              
                else
                    $this->view('users/signup', $data);
            }
            else
            {
                $data = [
                    'fullname' => '',
                    'email' => '',
                    'username' => '',
                    'password' => '',
                    'confirm_pwd' => '',
                    'token' => '',
                    'err_name' => '',
                    'err_email' => '',
                    'err_username' => '',
                    'err_password' => '',
                    'err_confirm-pwd' => ''
                ];

                $this->view('users/signup', $data);
            }
        }

        public function login() {
            if ($_SERVER['REQUEST_METHOD'] == 'POST')
            {
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                $username = (isset($_POST['username'])) ? trim($_POST['username']) : '';
                $password = (isset($_POST['password'])) ? trim($_POST['password']) : '';
                $data = [
                    'username' => $username,
                    'password' => $password,
                    'err_username' => '',
                    'err_password' => '',
                ];

                if (empty($data['username']))
                    $data['err_username'] = 'please enter username !!';
                else if(!$this->userModel->findUsrByUsername($data['username']))
                    $data['err_username'] = 'Username doest not exist !!';
                if (empty($data['password']))
                    $data['err_password'] = 'please enter password !!';

                if (empty($data['err_username']) && empty($data['err_password']))
                {
                    $loggedUser = $this->userModel->login($data['username'], $data['password']);
                    if ($loggedUser)
                    {
                        if($loggedUser->verified)
                            $this->createUserSession($loggedUser);
                        else
                        {
                            pop_up('not_verified', 'Please verify you email !!', 'alert alert-danger');
                            redirect('users/login');
                        }
                    }
                    else
                    {
                        $data['err_password'] = 'Invalid password !!';
                        $this->view('users/login', $data);
                    }   
                }
                else
                    $this->view('users/login', $data);
            }
            else
            {
                $data = [
                    'username' => '',
                    'password' => '',
                    'err_username' => '',
                    'err_password' => '',
                ];

                $this->view('users/login', $data);
            }
        }

        public function logout()
        {
            unset($_SESSION['user_id']);
            unset($_SESSION['user_email']);
            unset($_SESSION['user_username']);
            unset($_SESSION['user_fullname']);
            unset($_SESSION['user_img']);
            unset($_SESSION['notification']);

            session_destroy();
            redirect('users/login');
        }

        public function forgot()
        {
            $this->view('users/forgot');
        }

        public function reset()
        {
            if ($_SERVER['REQUEST_METHOD'] == 'POST')
            {
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_EMAIL);

                $data = [
                    'forgotEmail' => trim($_POST['forgotEmail']),
                    'err_forgotEmail' => ''
                ];

                if (empty($data['forgotEmail']))
                    $data['err_forgotEmail'] = 'please enter email !!';
                else if(!$this->userModel->findUsrByEmail($data['forgotEmail']))
                    $data['err_forgotEmail'] = 'Email doest not exist !!';
                
                if (empty($data['err_forgotEmail']))
                {
                        $to_email = $data['forgotEmail'];
                        $subject = "Reset password";
                        $user = $this->userModel->getUserToken($data['forgotEmail']);
                        $body = '<div class="email-container" style="background-color: whitesmoke; width: 700px; height: 500px;padding: 20px;">
                        <div class="title" style=\'color: #0C1117; text-align: center; font-family: billabong;font-size: 200%;\'><h1>Camagru</h1></div>
                        <div class="welcome"><h2 style=\'color: #0C1117; text-align: left; font-family: "Gill Sans", sans-serif;\'>hello '.$user->username.',</h2></div>
                        <div class="reset"><h3 style=\'color: #0C1117; text-align: left; font-family: "Gill Sans", sans-serif;\'>Reset your password</h3></div>
                        <div class="body"><p style=\'color: #0C1117; text-align: left; font-family: "Gill Sans", sans-serif;\'>

                        <br/>
                    
                        To choose a new password and complete your request, please follow the link below:<br/>
                        <a href="'.URL_ROOT.'/users/newpassword/?token='.$user->token.'&id='.$user->id.'" style=\'color: #8DA2FB;\'"><strong>click here.</strong></a>
                        <br/>
   
                        <br/>
                        <br/>
                        The CAMAGRU Team.
                        </p>
                        </div>
                        </div>';
                        $headers = "Content-type:text/html;charset=UTF-8" . "\r\n";
                        $headers .= 'From: <smoudene@Camagru.ma>' . "\r\n";
                        if (mail($to_email, $subject, $body, $headers))
                        {
                            pop_up('signup_ok', 'Reset password verification mail sent to your email');
                            $this->view('users/login', $data);
                        }
                        else
                        {
                            pop_up('signup_ok', 'Can not send email verificaton, please retry', 'alert alert-danger');
                            $this->view('users/forgot', $data);
                        }
                }
                else{
                    //pop_up('signup_ok', 'Can not send email verificaton, please retry', 'alert alert-danger');

                    $this->view('users/forgot', $data);
                }
            }
        }
        public function createUserSession($user)
        {
            $_SESSION['user_id'] = $user->id;
            $_SESSION['user_email'] = $user->email;
            $_SESSION['user_username'] = $user->username;
            $_SESSION['user_fullname'] = $user->fullname;
            $_SESSION['user_img'] = $user->profile_img;
            $_SESSION['notification'] = $user->notification;

            redirect('posts');
        }

        public function verification()
        {
            if (isset($_GET['token']))
            {
                $token = $_GET['token'];
                
                if (!isLogged())
                {
                    if ($this->userModel->verify($token, 1))
                    {
                        pop_up('signup_ok', 'Your account is verified succesfully');
                        redirect('users/login');
                    }
                    else
                    {
                        pop_up('signup_ok', 'Failed to verify your accout', 'alert alert-danger text-center');
                        redirect('users/login');
                    }
                }
                else
                    redirect('posts');
            }
            else
                die('error');
        }

        public function newpassword()
        {
            if (isset($_GET['token']) && isset($_GET['id']))
            {
                $data =[
                    'token' => $_GET['token'],
                    'id' => $_GET['id']
                ];
                
                if ($this->userModel->verify($data['token'], 0))
                    $this->view('users/reset', $data);
                else {
                    pop_up('signup_ok', 'Token not found', 'alert alert-danger');
                    redirect('users/login');
                }
            }
            else
                die('error');
        }
        
        public function updatepass($id)
        {
            if ($_SERVER['REQUEST_METHOD'] == 'POST')
            {
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

                $data = [
                    'newPassword' => trim($_POST['newPassword']),
                    'id' => $id,
                    'err_newPassword' => ''
                ];

                if (empty($data['newPassword']))
                    $data['err_newPassword'] = 'please enter password !!';
                else if (strlen($data['newPassword']) < 6)
                    $data['err_newPassword'] = 'Password must be at least 6 characters';
                else if (!preg_match('@[A-Z]@', $data['newPassword']))
                    $data['err_newPassword'] = 'Password must contain an upper case';
                else if (!preg_match('@[a-z]@', $data['newPassword']))
                    $data['err_newPassword'] = 'Password must contain a  lower case';
                else if (!preg_match('@[0-9]@', $data['newPassword']))
                    $data['err_newPassword'] = 'Password must contain a  number';
                if (empty($data['err_newPassword']))
                {
                    $data['newPassword'] = password_hash($data['newPassword'], PASSWORD_DEFAULT);
                    if($this->userModel->update_pass($data['newPassword'], $data['id']))
                    {
                        pop_up('signup_ok', 'Password updated');
                        redirect('users/login');
                    }
                    else {
                        pop_up('signup_ok', 'Password not updated', 'alert alert-danger');
                        redirect('users/login');
                    }
                }
                else
                {
                    $this->view('users/reset', $data);
                    // //die("fail");
                    // pop_up('signup_ok', 'Password not updated');
                    // redirect('users/login');
                }
            }
        }
        
        public function profile() {
            $post = $this->postModel->getPosts();
            $data = [
                'username' => $_SESSION['user_username'],
                'posts' =>$post
            ];
            
            $this->view('users/profile', $data);
        }

        public function update_user() {
            $error = 1;
            $data = [
                'id' => $_SESSION['user_id'],
            ];
           // die(print_r($_POST));
            //$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            if(!empty($_POST['new_username']))
            {
                if(!($this->userModel->findUsrByUsername($_POST['new_username'])) && $this->userModel->update_username($_POST['new_username'], $data['id']))
                {
                    $error = 0;
                    //pop_up('updated', 'Profile updated ✓', 'pop alert alert-success w-50 mx-auto text-center');
                    $_SESSION['user_username'] = $_POST['new_username'];
                    // redirect('users/profile');
                }
                else
                {
                    pop_up('updated', 'Username not updated', 'pop alert alert-danger w-50 mx-auto text-center');
                    redirect('users/profile');
                }
            }
            if(!empty($_POST['new_fullname']))
            {
                if($this->userModel->update_fullname($_POST['new_fullname'], $data['id']))
                {
                    $error = 0;
                    //pop_up('updated', 'Profile updated ✓', 'pop alert alert-success w-50 mx-auto text-center');
                    $_SESSION['user_fullname'] = $_POST['new_fullname'];
                    // redirect('users/profile');
                }
                else
                {
                    pop_up('updated', 'fullname not updated', 'pop alert alert-danger w-50 mx-auto text-center');
                    redirect('users/profile');
                }
            }
            if(!empty($_POST['new_email']))
            {
                if (filter_var($_POST['new_email'], FILTER_VALIDATE_EMAIL) && !$this->userModel->findUsrByEmail($_POST['new_email']))
                {
                    if($this->userModel->update_email($_POST['new_email'], $data['id']))
                    {
                        $error = 0;
                        //pop_up('updated', 'Profile updated ✓', 'pop alert alert-success w-50 mx-auto text-center');
                        $_SESSION['user_email'] = $_POST['new_email'];
                        // redirect('users/profile');
                    }
                    else
                    {
                        pop_up('updated', 'Email not updated', 'pop alert alert-danger w-50 mx-auto text-center');
                        redirect('users/profile');
                    }
                }
                else
                {
                    pop_up('updated', 'Email not updated', 'pop alert alert-danger w-50 mx-auto text-center');
                    redirect('users/profile');
                }
            }
            if(!empty($_POST['new_password']))
            {
                if ((strlen($_POST['new_password']) < 6) || (!preg_match('@[A-Z]@', $_POST['new_password'])) || (!preg_match('@[a-z]@', $_POST['new_password'])) || (!preg_match('@[0-9]@', $_POST['new_password'])))
                {
                    pop_up('updated', 'Password not valid', 'pop alert alert-danger w-50 mx-auto text-center');
                    redirect('users/profile');
                }
                else
                {
                    $_POST['new_password'] = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
                    if($this->userModel->update_pass($_POST['new_password'], $data['id']))
                    {
                        $error = 0;
                        //pop_up('updated', 'Profile updated ✓', 'pop alert alert-success w-50 mx-auto text-center');
                        // redirect('users/profile');
                    }
                    else
                    {
                        pop_up('updated', 'password not updated', 'pop alert alert-danger w-50 mx-auto text-center');
                        redirect('users/profile');
                    }
                }
            }
            if (($_SESSION['notification'] == 1 && empty($_POST['notifs'])) || ($_SESSION['notification'] == 0 && !empty($_POST['notifs'])))
            {
                if(!empty($_POST['notifs']))
                {
                    if($this->userModel->update_notifs($data['id'], 1))
                    {
                        $error = 0;
                        //pop_up('updated', 'Notification updated ✓', 'pop alert alert-success w-50 mx-auto text-center');
                        $_SESSION['notification'] = 1;
                        // redirect('users/profile');;
                    }
                    else
                    {
                        pop_up('updated', 'Notification not updated', 'pop alert alert-danger w-50 mx-auto text-center');
                        redirect('users/profile');
                    }
                }
                else if (empty($_POST['notifs']))
                {
                    if($this->userModel->update_notifs($data['id'], 0))
                    {
                        $error = 0;
                        //pop_up('updated', 'Notification updated ✓', 'pop alert alert-success w-50 mx-auto text-center');
                        $_SESSION['notification'] = 0;
                        // redirect('users/profile');;
                    }
                    else
                    {
                        pop_up('updated', 'notification not updated', 'pop alert alert-danger w-50 mx-auto text-center');
                        redirect('users/profile');
                    }
                }
                redirect('users/profile');
            }
            else
                redirect('users/profile');
            if($error == 0)
                pop_up('updated', 'Profile updated ✓', 'pop alert alert-success w-50 mx-auto text-center');
        }

        public function set_pdp($post_id)
        {
            $post = $this->postModel->getPostById($post_id);
            if ($this->userModel->setPhoto($post->content, $_SESSION['user_id']))
            {
                $user = $this->userModel->gets_user($_SESSION['user_id']);
                $_SESSION['user_img'] = $user->profile_img;
                redirect('users/profile');
            }
            else
                die('error');
        }


        
    }
