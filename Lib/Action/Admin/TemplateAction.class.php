<?php
import('ORG.Util.Input');
require 'Common/Json.php';
class TemplateAction extends Action
{
    private $gid;
    public function _initialize()
    {
        if(!Session::is_set('manager')) {
            $this->redirect('Auth/login');
        }
        $this->gid = $_SESSION['gid'];
    }

    public function index()
    {

        $Template = M('template');
        $templates = $Template->join("category on category.id = template.cat_id")->where('group_id = ' . $this->gid)->field('template.*, category.name catname')->select();
        $this->assign('templates', $templates);
        $this->display();
    }

    public function add()
    {
        $cat = M('category');
        $cats = $cat->where('group_id = ' . $this->gid)->select();
        $this->assign('cats', $cats);
        $select = !empty($_GET['select'])?Input::getVar($_GET['select']):1;
        $this->assign('select', $select);
        if($this->isPost()) {            
            $Template = M('template');
            if($Template->autoCheckToken($_POST)) {
                $_post = Input::getVar($_POST);
                $data['cat_id'] = $_post['cat_id'];
                $data['name'] = $_post['name'];
                $data['description'] = $_post['description'];
                $data['content'] = $this->_rules2json(
                        $_post['type'], 
                        $_post['difficult'], 
                        $_post['number'], 
                        $_post['score'], 
                        $_post['aspects']);
                $data['score'] = $_post['sum'];
                $data['num'] = $_post['total'];
                $Template->add($data);
                $this->redirect('template/index');
            } else {
                die('表单令牌验证错误');
            }
        }
        $this->display();
    }

    /**
     * 将组题条件返回为二位数组的json格式   
     * @param array $type
     * @param array $difficult
     * @param array $number
     * @param array $score
     * @param array $aspects
     * @return string
     */
    protected function _rules2json(Array $type, Array $difficult, Array $number, Array $score, Array $aspects)
    {
        $json_array = array();
        foreach($difficult as $key => $value) {
            $json_array[$key]['type'] = $type[$key];
            $json_array[$key]['difficult'] = $value;
            $json_array[$key]['number'] = $number[$key];
            $json_array[$key]['score'] = $score[$key];
            $json_array[$key]['aspects'] = $aspects[$key];
        }
        return Json::encode(json_encode($json_array));
    }

    public function delete()
    {
        $Template = M('template');
        $id = Input::getVar($_GET['id']);
        $Template->where("id = $id")->delete();
        $this->redirect('template/index');
    }
}