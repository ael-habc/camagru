<?php
class User
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }
    //register

    public function register($data)
    {
        $this->db->query('INSERT INTO user (`username`, `email`,`password`,`token`) VALUES (:name, :email,:password,:token)');
        //bind Value
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':password', $data['password']);
        $this->db->bind(':token', $data['token']);

        //execute
        return ($this->db->execute()) ? true : false;
    }

    //login user
    public function login($name, $password)
    {
        $this->db->query('SELECT * FROM user WHERE username = :name');
        $this->db->bind(':name', $name);
        $row = $this->db->single();
        $hash_password = $row->password;
        return (password_verify($password, $hash_password)) ? $row : false;
    }
    //confirmation
    public function confirm($token)
    {
        $this->db->query('SELECT * FROM user WHERE token = :token');
        $this->db->bind(':token', $token);
        $row = $this->db->single();
        if ($this->db->rowCount() > 0) {
            $this->db->query('UPDATE user SET confirmed = 1,token = "" WHERE token = :token');
            $this->db->bind(':token', $token);
            return ($this->db->execute()) ? true : false;
        } else
            return false;
    }
    //expire token
    public function expToken($token)
    {
        $this->db->query('SELECT * FROM user WHERE token = :token');
        $this->db->bind(':token', $token);
        $row = $this->db->single();
        return ($this->db->rowCount() > 0) ? true : false;
    }
    public function genToken($data)
    {
        $this->db->query('SELECT * FROM user WHERE email = :email');
        $this->db->bind(':email', $data['email']);
        $row = $this->db->single();
        if ($this->db->rowCount() > 0) {
            $this->db->query('UPDATE user SET token = :token WHERE email = :email');
            $this->db->bind(':token', $data['token']);
            $this->db->bind(':email', $data['email']);
            return ($this->db->execute()) ? true : false;
        } else
            return false;
    }
    //check is_confirm
    public function is_confirmed($name)
    {
        $this->db->query('SELECT * FROM user WHERE username = :name');
        $this->db->bind(':name', $name);
        $row = $this->db->single();
        return ($row->confirmed == 1) ?  true : false;
    }
    public function is_confirmedByEmail($email)
    {
        $this->db->query('SELECT * FROM user WHERE email = :email');
        $this->db->bind(':email', $email);
        $row = $this->db->single();
        return ($row->confirmed == 1) ?  true : false;
    }
    public function getUserToken($email)
    {
        $this->db->query('SELECT * FROM user WHERE email = :email');
        $this->db->bind(':email', $email);
        $row = $this->db->single();
        return ($row->token);
    }
    public function changepass($data)
    {
        $this->db->query("SELECT * FROM user WHERE token =  :token");
        $this->db->bind(':token', $data['token']);
        $row = $this->db->single();

        if ($this->db->rowCount() > 0) {

            $this->db->query('UPDATE user SET `password`= :password,token= "" WHERE token = :token');
            $this->db->bind(':token', $data['token']);
            $this->db->bind(':password', $data['password']);
            return ($this->db->execute()) ? true : false;
        } else
            return false;
    }
    public function getUserData($id)
    {
        $this->db->query("SELECT * FROM user WHERE id =  :id");
        $this->db->bind(':id', $id);
        $row = $this->db->single();
        return ($row);
    }
    public function edit($data)
    {
        if(!empty($data['password']))
        {
            $this->db->query('UPDATE user SET `username` = :name,`email`= :email,`password`= :password');
            $this->db->bind(':name', $data['name']);
            $this->db->bind(':email', $data['email']);
            $this->db->bind(':password', $data['password']);
            return ($this->db->execute()) ? true : false;
        } else{
            $this->db->query('UPDATE user SET `username` = :name,`email`= :email');
            $this->db->bind(':name', $data['name']);
            $this->db->bind(':email', $data['email']);
            return ($this->db->execute()) ? true : false;
        }
    }
    //Find user by email
    public function findUserByEmail($email)
    {
        $this->db->query('SELECT * FROM user WHERE email = :email');
        $this->db->bind(':email', $email);
        $row = $this->db->single();
        //check row
        return ($this->db->rowCount() > 0) ?  true : false;
    }
    public function findUserByName($name)
    {
        $this->db->query('SELECT * FROM user WHERE username = :name');
        $this->db->bind(':name', $name);
        $row = $this->db->single();

        //check row
        return ($this->db->rowCount() > 0) ?  true : false;
    }
    public function GetUserByToken($token)
    {
        $this->db->query('SELECT * FROM user WHERE token = :token');
        $this->db->bind(':token', $token);
        $row = $this->db->single();
        return $row;
    }
    public function ChangeEdit($id,$password)
    {
        $this->db->query('SELECT * FROM user WHERE id = :id');
        $this->db->bind(':id', $id);
        $row = $this->db->single();
        $hash_password = $row->password;
        return (password_verify($password, $hash_password)) ? $row : false;   
    }
}