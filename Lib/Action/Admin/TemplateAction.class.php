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
        if($this->gid==0){
            $templates = $Template->join("category on category.id = template.cat_id")->field('template.*, category.name catname')->select();
        }else{
            $templates = $Template->join("category on category.id = template.cat_id")->where('group_id = ' . $this->gid)->field('template.*, category.name catname')->select();
        }
        $this->assign('templates', $templates);
        $this->display();
    }

    public function add()
    {
        $cat = M('category');
        if($this->gid==0){
            $cats = $cat->select();
        }else{
            $cats = $cat->where('group_id = ' . $this->gid)->select();
        }
        $this->assign('cats', $cats);
        $select = !empty($_GET['select'])?Input::getVar($_GET['select']):1;
        $this->assign('select', $select);
        if($this->isPost()) {            
            $Template = M('template');
            if($Template->autoCheckToken($_POST)) {
                $data['cat_id'] = Input::getVar($_POST['cat_id']);
                $data['name'] =Input::getVar($_POST['name']); 
                $data['description'] = Input::getVar($_POST['description']); 
                $data['content'] = Input::getVar($this->_rules2json(
                       $_POST['type'], 
                       $_POST['difficult'], 
                       $_POST['number'], 
                       $_POST['score'], 
                       $_POST['aspects']));
                $data['score'] = Input::getVar($_POST['sum']);
                $data['num'] =  Input::getVar($_POST['total']);
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