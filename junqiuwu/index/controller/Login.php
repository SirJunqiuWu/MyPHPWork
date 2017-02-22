<?php
/**
 * Created by PhpStorm.
 * User: wujunqiu
 * Date: 17/2/7
 * Time: 16:33
 */

namespace app\index\controller;


use think\Controller;
use think\View;

class Login extends Controller
{
    public function login()
    {
        $view = new View();
        //获取某个controller下的某个文件下的文件
        return $view->fetch('index@index/forgetWater');
    }

    public function logout()
    {
        return view('login');
    }

}