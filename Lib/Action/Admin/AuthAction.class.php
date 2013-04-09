<?php
import('ORG.Util.Input');
class AuthAction extends Action
{
    /**
     * 后台用户登录
     */
    public function login()
    {
        $error = '';
        if ($this->isPost()) {
            $Manager = M('manager');
            if($Manager->autoCheckToken($_POST)) {
                $username = Input::getVar($_POST['username']);
                $password = $this->salt(Input::getVar($_POST['password']));
                $sql = "select m.id,m.name,g.id gid,g.name gname from manager m
                        left join groups g
                        on m.group_id = g.id
                        where m.name = '$username' and password = '$password'";
                $row = $Manager->query($sql);
                if($row) {
                    $user = $row[0];
                    Session::set('manager', true);
                    Session::set('userid', $user['id']);
                    Session::set('username', $user['name']);
                    if($user['gid'] == 0) {
                        Session::set('gid', 0);
                        Session::set('gname', '管理员');
                    } else {
                        Session::set('gid', $user['gid']);
                        Session::set('gname', $user['gname']);
                    }
                    $this->redirect('index/index');
                } else {
                    $error = '用户名 或者 密码错误，请重试';
                }
            } else {
                die('表单令牌验证错误');
            }
        }
        $this->assign('error', $error);
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