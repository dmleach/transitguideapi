<?php

namespace transitguide\api\controller;

class HelloController extends base\ActionController
{
    public function execute()
    {
        $this->outputGreeting();
    }

    public function get($request)
    {
        $this->outputGreeting();
    }

    public function outputGreeting()
    {
        // TO-DO: Output a list of available endpoints
        // TO-DO: Manage output using a view class
        echo "<p>Hello! I'm HelloController. I say hello!</p>";
    }
}
