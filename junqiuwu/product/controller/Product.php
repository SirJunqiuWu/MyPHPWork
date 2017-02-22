<?php
/**
 * Created by PhpStorm.
 * User: Jack
 * Date: 2017/2/22
 * Time: 下午1:44
 */

namespace app\product\controller;


use think\Controller;

class Product extends Controller
{
    public function product()
    {
        return view("product");
    }
}