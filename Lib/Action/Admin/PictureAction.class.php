<?php
require 'Common/Json.php';
import('ORG.Util.Input');
class PictureAction extends Action{

    public function update_html(){
        header("content-type:text/html;charset=utf8");
        $testId=Input::getVar($_POST["testId"]);
        //重新生成html
        $dir = 'Data/html';
        $template = "Data/template.html";
        $template_html = file_get_contents($template);
        $exam_test = M('test');
        $test=$exam_test->where("id=".$testId)->field('content')->find();
        if($test) {
            unlink('Data/html/' .$testId.'.html');
            //替换模板内容，生成新模板，往数据库插入图片地址
            $new = str_replace('{REPLACE_HOLDER}', $test['content'], $template_html);
            $html_name = $dir . '/' . $testId. '.html';
            file_put_contents($html_name, $new);
            //生成图片
            $document_root = C('DOCUMENT_ROOT');
            $command ="cd $document_root;phantomjs rasterize.js "."Data/html/".$testId.".html Storage/image480/".$testId.".gif";
            exec($command);
        }else{
            echo '修改失败';
        }

    }
}

