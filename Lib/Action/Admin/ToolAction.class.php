<?php
require 'Common/Json.php';
class ToolAction extends Action
{
    /**
     * 生成知识点缓存
     */
    public function aspects()
    {
        $cat = M('category');
        $aspects = M('aspects');
        $cats = $cat->field('id')->select();
        foreach($cats as $c) {
            $data = $aspects->where('cat_id =' . $c['id'])->field('name')->select();
            foreach($data as $d) {
                $tmp[] = $d['name'];
            }
            $aspects_json =  Json::encode(json_encode($tmp));
            $tmp = array();
            unlink('Data/aspects/' . $c['id'] . '.json');
            file_put_contents('Data/aspects/' . $c['id'] . '.json', $aspects_json);
        }
        header("Content-type: text/html; charset=utf-8");
        echo '生成完成 <br />';
        echo '<a href="javascript:history.go(-1);">返回后台首页</a>';
    }

    /**
     * 生成临时的html文件，供自动程序调用生成图片
     */
    public function create_html()
    {
        header("Content-type: text/html; charset=utf-8");
        $dir = 'Data/html2img';
        $template = "Data/template.html";
        $record = "Data/create_html.json";
        $jscache = "Data/jscache.json";

        $template_html = file_get_contents($template);
        $exam_test = M('test');
        $record_data = array();
        if(!file_exists($record)) {
            $tests = $exam_test->field('id, content')->order('id ASC')->select();            
        } else {
            $record_data = (Array)json_decode(file_get_contents($record));
            $tests = $exam_test->where('id >' . $record_data['id'])->field('id, content')->order('id ASC')->select();
        }
        if($tests) {
            $js_url = '';
            foreach($tests as $test) {
                $new = str_replace('{REPLACE_HOLDER}', $test['content'], $template_html);
                $html_name = $dir . '/' . $test['id'] . '.html';
                file_put_contents($html_name, $new);
                $id = $test['id'];
                $js_url .= C('URL') . $html_name . '@';

                // 插入图片表
                $td = M('test_device');
                $data['test_id'] = $test['id'];
                $data['image480'] = "Storage/image480/{$test['id']}.gif";
                $td->add($data);
            }
            $record_data['id'] = $id;
            $record_data['time'] = time();
            file_put_contents($record, json_encode($record_data));
            if(!file_put_contents($jscache, substr($js_url, 0, -1))) {
                echo 'js error';
            }
            $command = file_get_contents('Command.sh');
            exec($command);
            echo "生成完成!<br />";
        } else
            echo '没有创建任何静态HTML页面，可能尚未有更新新的题目.<br />';

        echo '<a href="javascript:history.go(-1);">返回后台首页</a>';
    }
}