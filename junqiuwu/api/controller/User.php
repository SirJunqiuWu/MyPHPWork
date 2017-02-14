<?php
/**
 * Created by PhpStorm.
 * User: Jack
 * Date: 2017/1/3
 * Time: 下午5:54
 */

namespace app\api\controller;


use think\Controller;

//模块配置文件
class User extends Controller
{
    /**
     * 用户登陆
     * @return \think\response\Json
     */
    public function login()
    {
        //初始化一个user对象
        $user = new \app\api\model\User();

        //得到post上来的name参数
        $name = input('post.name');
        $pwd  = input('post.pwd');
        $obj = new \stdClass();
        if (!$name)
        {
            $result = ['info'=>'用户名不能为空','code'=>'2','data'=>$name];
            return json($result);
        }

        if (!$pwd)
        {
            $result = ['info'=>'密码不能为空','code'=>'3','data'=>$obj];
            return json($result);
        }

        //条件都满足,查询该用户是否存在
        $obj_has = $user->obj_has_by_name($name);
        
        if ($obj_has)
        {
            //用户存在,执行登录
            $data = $user->login($name,$pwd);
            if ($data)
            {
                //登录成功
                $obj_data = $user->get_info_by_name($name);

                //更改用户登录状态
                $id = $obj_data['id'];
                $user->update_login_status($id,1);

                //重新获取用户信息
                $obj_data = $user->get_info_by_name($name);
                $result = ['info'=>'登录成功','code'=>'1','data'=>$obj_data];
            }
            else
            {
                //登录失败,说明密码错误
                $result = ['info'=>'密码错误,请重新输入','code'=>'5','data'=>$obj];
            }
        }
        else
        {
            //用户不存在
            $result = ['info'=>'用户不存在','code'=>'6','data'=>$obj];
        }
        return json($result);
    }


    /**
     * 用户注册
     * @return \think\response\Json
     */
    public function register()
    {
        $user = new \app\api\model\User();
        $obj = new \stdClass();

        $name = input('post.name');
        $pwd  = input('post.pwd');
        $nickname = input('post.nickname');


        //先判断条件是否满足
        if (!$name)
        {
            $result = ['info'=>'用户名不能为空','code'=>'2','data'=>$obj];
            return json($result);
        }

        if (!$pwd)
        {
            $result = ['info'=>'密码不能为空','code'=>'3','data'=>$obj];
            return json($result);
        }

        if (!$nickname)
        {
            $result = ['info'=>'昵称不能为空','code'=>'4','data'=>$obj];
            return json($result);
        }

        //条件均符合,查看该用户是否存在
        $obj_has = $user->obj_has_by_name($name);
        if ($obj_has == true)
        {
            //用户已经存在,获取用户基本信息
            $data = $user->get_info_by_name($name);
            $result = ['info'=>'用户已存在','code'=>'55','data'=>$data];
        }
        else
        {
            //用户不存在,注册新用户
            $data = $user->register($name,$pwd,$nickname);
            if ($data)
            {
                $obj_info = $user->get_info_by_name($name);
                $result = ['info'=>'注册成功','code'=>'1','data'=>$obj_info];
            }
            else
            {
                $result = ['info'=>'注册失败','code'=>'2','data'=>$obj];
            }
        }
        return json($result);
    }


    /**
     * 根据用户id更新用户基本信息
     * @return \think\response\Json
     */
    public function updateInfo()
    {
        $id = input('post.id');
        $nickname = input('post.nickname');
        $temp_user['id'] = $id;
        $temp_user['nickname'] = $nickname;

        $user = new \app\api\model\User();
        $obj  = new \stdClass();

        $obj_has = $user->obj_has_by_id($id);
        if ($obj_has)
        {
            //执行更新
            $update_result = $user->update_info($temp_user);
            if ($update_result)
            {
                //更新成功
                $obj_data = $user->get_info($id);
                $result = ['info'=>'更新成功','code'=>'1','data'=>$obj_data];

            }
            else
            {
                //更新失败
                $result = ['info'=>'更新失败','code'=>'2','data'=>$obj];
            }
        }
        else
        {
            $result = createApiData('用户不存在','3',$obj);

        }
        return json($result);
    }


    /**
     * 退出登录:根据用户id退出登录
     * @return \think\response\Json
     */
    public function logout()
    {
        $id = input('post.id');

        $user = new \app\api\model\User();
        $obj_has = $user->obj_has_by_id($id);
        $obj = new \stdClass();
        if ($obj_has)
        {
            $data = $user->update_login_status($id,0);
            if ($data)
            {
                $obj_data = $user->get_info($id);
                $result = ['info'=>'退出登录成功','code'=>'1','data'=>$obj_data];
            }
            else
            {
                $obj_data = $user->get_info($id);
                $result = ['info'=>'退出登录失败','code'=>'3','data'=>$data];
            }
        }
        else
        {
            $result = ['info'=>'用户不存在','code'=>'2','data'=>$obj];
        }
        return json($result);
    }


    /**
     * 根据用户id删除指定用户
     * @return \think\response\Json
     */
    public function delete()
    {
        $id = input('post.id');
        $user = new \app\api\model\User();
        $obj_has = $user->obj_has_by_id($id);
        if ($obj_has)
        {
            $delete_result = $user->delete_user($id);
            if ($delete_result)
            {
                $result = ['info'=>'删除成功','code' =>'1','data'=>$delete_result];
            }
            else
            {
                $result = ['info'=>'删除失败','code' =>'1','data'=>$delete_result];
            }
        }
        else
        {
            $result = createApiData('用户不存在','2',[]);
        }

        return json($result);
    }


    /**
     * 修改用户密码
     * @return \think\response\Json
     */
    public function changePassWord()
    {
        //用户id 新密码  旧密码  验证码(暂时验证码不能为空)
        $id = input('post.id');
        $pwd = input('post.pwd');
        $old_pwd = input('post.old_pwd');
        $code = input('post.code');

        $obj = new \stdClass();
        //参数是否传递
        if (!$id || !$pwd || !$code || !$old_pwd)
        {
            $result = ['info'=>'参数不足','code'=>'2','data'=>$obj];
            return json($result);
        }
        $user = new \app\api\model\User();
        $obj_has = $user->obj_has_by_id($id);
        $obj_data = $user->get_info($id);

        //用户是否存在
        if ($obj_has)
        {
            //存在,先比对旧密码是否填写正确

            //获取到旧密码
            $obj_data = $user->get_info($id);
            $obj_pwd  = $obj_data['pwd'];

            if ($obj_pwd != $old_pwd)
            {
                $result = ['info'=>'修改失败,旧密码填写错误','code'=>'3','data'=>$obj];
                return json($result);
            }


            if ($old_pwd == $pwd)
            {
                $result = ['info'=>'修改失败,新密码和旧密码一样','code'=>'4','data'=>$obj];
                return json($result);
            }


            //新旧密码不一样,执行更新
            $data = $user->update_pwd($id,$pwd);
            if ($data)
            {
                //更新成功
                $obj_data = $user->get_info($id);
                $result = ['info'=>'密码修改成功','code'=>'1','data'=>$obj_data];
            }
            else
            {
                $result = ['info'=>'修改密码失败','code'=>'2','data'=>$obj];
            }
        }
        else
        {
            $result = ['info'=>'用户不存在','code'=>'3','data'=>$obj];
        }
        return json($result);
    }


    /**
     * 获取所有在线用户
     * @return \think\response\Json
     */
    public function getAllLoginUser()
    {
        $user = new \app\api\model\User();
        $all_login_user = $user->get_login_user();
        $login_user_count = count($all_login_user);
        if ($login_user_count >0)
        {
            $result = createApiData('获取成功','1',$all_login_user);
        }
        else
        {
            $result = createApiData('暂无在线用户','1',$all_login_user);
        }

        return json($result);
    }


    /**
     * 获取所有用户
     * @return \think\response\Json
     */
    public function getAllUser()
    {
        $user = new \app\api\model\User();
        $all_user = $user->get_all_user();
        $all_user_count = count($all_user);
        if ($all_user_count >0)
        {
            $result = ['info'=>'当前在线'.$all_user_count.'人','code'=>'1','data'=>$all_user];
        }
        else
        {
            $result = ['info'=>'当前无人在线','code'=>'1','data'=>$all_user];
        }
        return json($result);

    }

    /**
     * 上传用户头像
     * @return \think\response\Json
     */
    public function uploadImage()
    {
        $data = uploadImage('avatar');
        return $data;
    }

}