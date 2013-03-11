<?php
import('ORG.Util.Input');
class CategoryAction extends Action
{
    public function index()
    {
        $gid = $_SESSION['gid'];
        $cat = M('category');
        $cats = $cat->where('group_id = ' . $gid)->select();
        $this->assign('cats', $cats);

        $this->display();
    }

    /**
     * ajax function
     */
    public function addCategory()
    {
        $cat_name = Input::getVar($_POST['name']);
        $gid = Input::getVar($_POST['gid']);
        $cat = M('category');
        $data['pid'] = 0;
        $data['name'] = trim($cat_name);
        $data['group_id'] = $gid;
        if($cat->add($data))
            echo 'success';
        else
            echo 'failed';
    }
}