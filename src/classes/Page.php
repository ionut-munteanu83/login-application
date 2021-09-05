<?php
namespace App\Classes;

class Page extends Main
{
	public function home()
    {
        include_once('../src/views/home.php');
    }
    
    public function notFound()
    {
    	header("HTTP/1.0 404 Not Found");
		include_once('../src/views/not_found.php');
	}
}