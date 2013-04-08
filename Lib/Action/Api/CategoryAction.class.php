<?php
require 'Common/Json.php';
import('ORG.Util.Input');
class CategoryAction extends Action
{
    /**
     * 分类列表
     * 目前只考虑一级分类
     * @return string
     */
    public function index()
    {
        $user_id = Input::getVar($_GET['uid']);
        if($user_id) {
            $group_id = $this->getGroup($user_id);
            $Category = M('category');
            $cats = $Category->where('group_id =' . $group_id)->field('id, name')->select();
        } else {
            $Category = M('category');
            $cats = $Category->field('id, name')->select();
        }
        echo Json::encode(json_encode($cats));
    }

    public function getGroup($user_id)
    {
        $u = M('manager');
        return $u->where('id = ' . $user_id)->getField('group_id');
    }
}