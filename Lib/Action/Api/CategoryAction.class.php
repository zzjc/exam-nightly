<?php
require 'Common/Json.php';
class CategoryAction extends Action
{
    /**
     * 分类列表
     * 目前只考虑一级分类
     * @return string
     */
    public function index()
    {
        $Category = M('category');
        $cats = $Category->field('id, name')->select();
        echo Json::encode(json_encode($cats));
    }
}