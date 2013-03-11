<?php
import('ORG.Util.Input');
class UserAction extends Action
{
    public function index()
    {
        $u = M('manager');
        import("ORG.Util.Page");
        $count = $u->where('group_id != 0')->count();
        $p = new Page($count, 10);
        $page = $p->show();
        $sql = "select manager.id,manager.name,groups.name groups from manager
                inner join groups on manager.`group_id` = groups.id
                limit $p->firstRow,$p->listRows";
        $users = $u->query($sql);
        $this->assign('users', $users);
        $this->assign('page', $page);
        $this->display();
    }

    public function add()
    {
        $g = M('groups');
        $u = M('manager');
        if($this->isPost()) {
            if($u->autoCheckToken($_POST)) {
                $_post = Input::getVar($_POST);
                $data['name'] = $_post['name'];
                $data['password'] = $this->salt($_post['password']);
                $data['group_id'] = $_post['gid'];
                $u->add($data);
                $this->redirect('user/index');
            } else {
                die('表单令牌验证错误');
            }
        }
        $groups = $g->select();
        $this->assign('groups', $groups);
        $this->display();
    }

    public function update()
    {

    }

    public function delete()
    {
        $u = M('manager');
        $id = Input::getVar($_GET['id']);
        $u->where("id = $id")->delete();
        $this->redirect('user/index');
    }

    public function salt($password)
    {
        $salt = C('SALT');
        return md5($salt . md5($password));
    }
}