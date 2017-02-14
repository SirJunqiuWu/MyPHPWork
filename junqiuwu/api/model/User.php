<?php
/**
 * Created by PhpStorm.
 * User: Jack
 * Date: 2017/1/20
 * Time: 上午10:43
 */

namespace app\api\model;


use think\Model;

class User extends Model
{
    /**
     * 登录:根据当前对象的用户名和密码进行登录
     * @param $user
     * @return array|false|\PDOStatement|string|Model
     */
    public function login($name,$pwd)
    {
        $temp_name = $name;
        $temp_pwd  = $pwd;

        $stmp['name'] = $temp_name;
        $stmp['pwd']  = $temp_pwd;
        //从数据库中找用户名为name的数据
        $data = self::where($stmp)->find();
        return $data;
    }


    /**
     * 注册:保存新用户到数据库
     * @param $name
     * @return false|int
     */
    public function register($name,$pwd,$nickname)
    {
        $temp['name']=$name;
        $temp['pwd']=$pwd;
        $temp['nickname']=$nickname;
        $data = self::data($temp)->save();
        return $data;
    }


    /**
     * 更新:根据用户id更新指定用户的某个信息
     * @param $user
     * @return $this
     */
    public function update_info($user)
    {
        $id = $user['id'];
        $nickname = $user['nickname'];
        $stmp['id']= $id;
        $update_nickname['nickname']=$nickname;
        $data = self::update($update_nickname,$stmp);
        return $data;
    }

    /**
     * 更新:根据用户id和用户登录状态更改用户登录状态标记
     * @param $id
     * @param $status
     * @return $this
     */
    public function update_login_status($id,$status)
    {
        $stamp['id'] = $id;
        $login_status['status'] = $status;
        $data = self::update($login_status,$stamp);
        return $data;
    }


    /**
     * 更新:根据用户id和新密码修改用户密码
     * @param $id
     * @param $pwd
     * @return $this
     */
    public function update_pwd($id,$pwd)
    {
        $stamp['id'] = $id;
        $update_pwd['pwd'] = $pwd;
        $data = self::update($update_pwd,$stamp);
        return $data;
    }

    /**
     * 查找:根据用户id获取指定用户的信息
     * @param $id
     * @return array|false|\PDOStatement|string|Model
     */
    public function get_info($id)
    {
        $stmp['id'] = $id;
        $data = self::where($stmp)->find();
        return $data;
    }




    /**
     * 查找:根据用户账户去获取用户基本信息
     * @param $name
     * @return array|false|\PDOStatement|string|Model
     */
    public function get_info_by_name($name)
    {
        $temp_user['name'] = $name;
        $data = self::where($temp_user)->find();
        return $data;
    }


    /**
     * 查找:根据用户id判断某个用户是否存在
     * @param $id
     * @return bool
     */
    public function obj_has_by_id($id)
    {
        $temp_user['id']   = $id;
        //从数据库中找用户id为当前id的个数
        $data = self::where($temp_user)->count();
        if ($data > 0)
        {
            //存在
            return true;
        }
        else
        {
            //不存在
            return false;
        }
    }

    /**
     * 查找:根据用户名判断某个用户是否存在
     * @param $name
     * @return bool
     */
    public function obj_has_by_name($name)
    {
        $stmp['name'] = $name;
        //从数据库中找指定用户名的用户
        $data = self::where($stmp)->count();
        if ($data > 0)
        {
            //存在
            return true;
        }
        else
        {
            //不存在
            return false;
        }
    }


    /**
     * 获取在线用户
     * @return false|\PDOStatement|string|\think\Collection
     */
     public function get_login_user()
     {
         $stamp['status']=1;
         $data = self::where($stamp)->select();
         return $data;
     }


    /**
     * 获取所有用户
     * @return \think\response\Json
     */
     public function get_all_user()
     {
//         $data = self::where('id'>0)->select();
         $data = $this->select();
         return $data;
     }


    /**根据用户id删除某个用户
     * @param $id
     * @return int
     */
    public function delete_user($id)
    {
        $temp_user['id'] = $id;
        $data = self::where($temp_user)->delete();
        return $data;
    }
}
