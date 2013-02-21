<?php
import('ORG.Util.Input');
require 'Common/Json.php';
class TemplateAction extends Action
{
    /**
     * 根据分类id获得相对应的模板列表
     * @return string
     */
    public function index()
    {
        $cat_id = Input::getVar($_GET['cid']);
        $Template = M('template');
        $templates = $Template->where("cat_id = $cat_id")->field('id, name, score, num, description')->select();
        echo Json::encode(json_encode($templates));
    }
}