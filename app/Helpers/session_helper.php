<?php
    session_start();

    // FLASH messege
    function flash($name = '',$message = '', $class = 'alert alert-success'){
        if (!empty($name)){
            if (!empty($message) && empty($_SESSION[$name])){
                if (!empty($_SESSION[$name])){
                    unset($_SESSION[$name]);
                }
                if (!empty($_SESSION[$name.'_class'])){
                    unset($_SESSION[$name . '_class']);
                }
                $_SESSION[$name] = $message;
                $_SESSION[$name.'_class'] = $class;
            }elseif (empty($message) && !empty($_SESSION[$name])){
                $class = !empty($_SESSION[$name. '_class']) ? $_SESSION[$name. '_class'] : '';
                echo '<div class="'.$class.'"id=msg-flash">'.$_SESSION[$name].'</div>';
                unset($_SESSION[$name]);
                unset($_SESSION[$name.'_class']);
            }
        }
    }
    function MailSender($data)
    {
        $to_mail = $data['email'];
        $subject = "test";
        $message = '
                        <html>
                            <body>
                                <p>
                                    To activate your account please click 
                                    <a href="http://'.getenv('HTTP_HOST'). '/Camagru/users/confirm/?token='. $data['token'].'"target="_blank""">
                                        here
                                    </a>
                                </p>
                            </body>
                        </html>
                    ';
        $headers = 'Content-type: text/html; charset=iso-8859-1'."\r\n";
        mail($to_mail,$subject,$message,$headers);
    }
    function passwordMail($data)
    {
        $to_mail = $data['email'];
        $subject = "test";
        $message = '
                        <html>
                            <body>
                                <p>
                                    Tap to the link to chage your password
                                    <a href="http://'.getenv('HTTP_HOST'). '/Camagru/users/changepass/?token='. $data['token'].'"target="_blank""">
                                        here
                                    </a>
                                </p>
                            </body>
                        </html>
                    ';
        $headers = 'Content-type: text/html; charset=iso-8859-1'."\r\n";
        mail($to_mail,$subject,$message,$headers);
    }
