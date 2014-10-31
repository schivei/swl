<?php

// arquivo /controllers/home.php
class Home extends CI_Controller
{

    public function index()
    {
        $this->load->view("index");
    }

    public function about()
    {
        $this->load->view("about");
    }

    public function contacts()
    {
        $this->load->view("contacts");
    }

}

//arquivo /config/routes.php
$route['default_cntroller'] = 'Home';
$route['contacts']          = 'Home/contacts';
$route['about']             = 'Home/about';










