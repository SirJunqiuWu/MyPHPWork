<?php
namespace app\index\controller;

class Index
{
    public function index()
    {
        return view('forgetWater');
    }

    public function login()
    {

        return '登陆';
    }

    public  function  logout()
    {
        return '退出';
    }

}
