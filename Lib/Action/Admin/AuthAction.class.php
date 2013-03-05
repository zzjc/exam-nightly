<?php
import('ORG.Util.Input');
class AuthAction extends Action
{
    /**
     * 后台用户登录
     */
    public function login()
    {
        if ($this->isPost()) {

            $Manager = M('manager');
            if($Manager->autoCheckToken($_POST)) {
                $username = Input::getVar($_POST['username']);
                $password = $this->salt(Input::getVar($_POST['password']));
                $row = $Manager->where("name = '$username' AND password = '$password'")->find();
                if($row) {
                    Session::set('manager', true);
                    Session::set('userid', $row['id']);
                    Session::set('username', $row['name']);
                    $this->redirect('index/index');
                }
            } else {
                die('表单令牌验证错误');
            }
        }
        $this->display();
    }

    public function logout()
    {
        unset($_SESSION);
        session_destroy();
        $this->redirect('Auth/login');
    }

    /**
     * ajax方法，更改用户密码
     */
    public function changePass()
    {
        $username = Input::getVar($_POST['name']);
        $password = $this->salt(Input::getVar($_POST['password']));
        $user = M('manager');
        $data['password'] = $password;
        $status = $user->where("name = '$username'")->save($data);
        echo $status;
    }

    public function salt($password)
    {
        $salt = C('SALT');
        return md5($salt . md5($password));
    }
}