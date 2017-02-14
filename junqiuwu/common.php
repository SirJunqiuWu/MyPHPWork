<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
/**
 * api返回数据构建
 * @param string $info
 * @param string $code
 * @param null $data
 */
function createApiData($info = '',$code = '0',$data = null)
{
    $result = ['info'=>$info,'code'=>$code, 'data'=>$data];
    return $result;
}


/**
 * 根据文件名上传图片
 * @param $image_name
 * @return \think\response\Json
 */
function uploadImage($image_name)
{
    $file = request()->file($image_name);
    if ($file)
    {
        //将图片移至指定目录
        $info = $file->move(ROOT_PATH . 'public' . DS . 'images');
        if ($info)
        {
            //上传成功,获取图片的保存路径,拼接图片在服务器的连接地址可访问图片
            $save_name = $info->getSaveName();
            $result = ['info'=>'失败','code'=>'1', 'data'=>'http://www.junqiuwu.com/images/'.$save_name];
        }
        else
        {
            //上传失败
            $error = $file->getError();
            $result = ['info'=>'失败','code'=>'1', 'data'=>$error];
        }
    }
    else
    {
        $result = ['info'=>'失败','code'=>'1', 'data'=>'图片不存在'];
    }
    return json($result);
}
