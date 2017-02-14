<?php
/**
 * Created by PhpStorm.
 * User: Jack
 * Date: 2017/1/3
 * Time: 下午5:55
 */

namespace app\api\controller;


use think\Controller;

class Admin extends Controller
{
    //Admin的权限较高,尽量不要在这里面混杂其他的东西
    public function login()
    {
        return json("123");
    }
}