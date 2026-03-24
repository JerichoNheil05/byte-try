<?php

namespace App\Controllers;

class About extends BaseController
{
    /**
     * Display the About Us page.
     *
     * @return string
     */
    public function index(): string
    {
        return view('aboutus');
    }
}
