<?php
import('ORG.Util.Input');
class CategoryAction extends Action
{
    public function index()
    {
        $cat = M('category');
        $cats = $cat->select();
        $this->assign('cats', $cats);

        $this->display();
    }

    /**
     * ajax function
     */
    public function addCategory()
    {
        $cat_name = Input::getVar($_POST['name']);
        $cat = M('category');
        $data['pid'] = 0;
        $data['name'] = trim($cat_name);
        if($cat->add($data))
            echo 'success';
        else
            echo 'failed';
    }
}