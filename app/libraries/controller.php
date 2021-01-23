<?php
    //base controller
    // load the models ansd views
    class Controller{

        public function model($model)
        {
            // require model files
            require_once '../app/models/'. $model . '.php';
            //instatiate model
            return new $model();
        }
        
        //load view 
        public function view($view, $data = [])
        {
            // check the view files
            if (file_exists('../app/views/' . $view . '.php'))
            {
                require_once '../app/views/' . $view . '.php';
            }
            else
                die("View does not exist");
        }
    }