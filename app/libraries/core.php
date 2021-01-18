<?php
    class Core {
        protected $currentController = 'Pages';
        protected $currentMethode = 'index';
        protected $params = [] ;

        public function __construct()
        {
            $url = $this->getUrl();

            //look in controllers for first value
            if (file_exists('../app/controllers/' . ucwords($url[0]) . '.php'))
            {
                //if exists set ass controller
                $this->currentController = ucwords($url[0]);
                //unset 0 index
                unset($url[0]);
            }
            //require the controller

            require_once '../app/controllers/' .$this->currentController . '.php';
            
            //Instantiate controller Class
            $this->currentController = new $this->currentController;

            // check for 1 part url
            if (isset($url[1]))
            {
                //check method exist in controller
                if(method_exists($this->currentController, $url[1]))
                {
                    $this->currentMethode = $url[1];
                    unset($url[1]);
                }
            }
            // get params
            $this->params = $url ? array_values($url) : [];
            
            //Call a callback  with array of params

            call_user_func_array([$this->currentController, $this->currentMethode],$this->params);
        }

        public function getUrl(){
            if (isset($_GET['url'])){
                $url = rtrim($_GET['url'],'/');
                $url = filter_var($url,FILTER_SANITIZE_URL);
                $url = explode('/',$url);
                return $url;
            }            
        }
    }