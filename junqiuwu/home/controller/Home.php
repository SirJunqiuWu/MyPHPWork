<?php
/**
 * Created by PhpStorm.
 * User: Jack
 * Date: 2017/2/21
 * Time: 上午11:09
 */

namespace app\home\controller;


use think\Controller;

class Home extends Controller
{
    public function home()
    {
        return view('home');
    }
}