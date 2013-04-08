<?php
import('ORG.Util.Input');
require 'Common/Json.php';
class UserAction extends Action
{
    public function login()
    {
        $username = Input::getVar($_GET['username']);
        $password = $this->salt(Input::getVar($_GET['password']));
        $sql = "select 0 status,m.id,m.name loginname,
                p.nickname, p.class, p.idcard, p.exam_no examno
                from manager m
                left join profile p
                on p.user_id = m.id
                where m.name = '$username' and password = '$password'";
        $auth = M();
        $result = $auth->query($sql);
        if ($result){
            $user = $result[0];
            $user['status'] = 1;
        } else {
            $user['status'] = 0;
        }
        echo Json::encode(json_encode($user));

    }

    /**
     * copy & paste function from Admin/Auth class, please delete it from here.
     * @param $password
     * @return string
     */
    public function salt($password)
    {
        $salt = C('SALT');
        return md5($salt . md5($password));
    }
}