<?php
import('ORG.Util.Input');
require 'Common/Json.php';
class TestAction extends Action
{   
    private $_ds = '-';
    
    /**
     * 模拟考试接口
     * 根据分类和模板进行随机抽题
     * @return string
     */
    public function exam()
    {
        $template_id = Input::getVar($_GET['tid']);
        $Template = M('template');
        $t = $Template->where("id = $template_id")->field('content,num')->find();
        $rules = json_decode($t['content']);
        $testmodel = M();
        $images = array();
        $final = array();
        foreach($rules as $rule) {
            $rule = (array)$rule;
            if($rule['type'] != 4) {
                $difficult = explode('-', $rule['difficult']);
                $difficult_from = $difficult[0];
                $difficult_to = $difficult[1];
                $num = $rule['number'];
                $aspects = json_decode($rule['aspects']);
                foreach($aspects as $key => $aspect)
                    $aspects[$key] = "'$aspect'";
                $aspects = implode($aspects, ', ');

                if($difficult_from == $difficult_to) {
                    $level = "level = $difficult_to";
                } else {
                    $level = "level >= $difficult_from and level <= $difficult_to";
                }
                $sql = "SELECT distinct(test.id),test.content title,
                        test.level, test.answer, td.image480 as img,
                        test.test_type as type, test.point
                        FROM `test`
                        inner join test_aspects ta
                        on test.id = ta.test_id
                        inner join aspects
                        on ta.aspects_id = aspects.id
                        left join test_device td
                        on test.id = td.test_id
                        WHERE $level
                        and aspects.name in ($aspects)
                        and pid = 0
                        ORDER BY rand() LIMIT $num";
                $tests = $testmodel->query($sql);
                $true_num = count($tests);
                if($true_num < $num) {
                    $number = $num - $true_num;
                    $extra = $this->getExtraTest($difficult_from, $difficult_to, $number);
                    array_merge($tests, $extra);
                }
                foreach($tests as $t) {
                    $t['title'] = mb_substr(strip_tags($t['title']), 0, 15, 'utf-8');
                    $t['title'] = trim($t['title']);
                    $t['client'] = '';
                    $images[] = $t['img'];
                    $final[0][] = $t;
                }
            } else {
                $num = $rule['number'];
                $sql = "select id, description from casetest
                        ORDER BY rand() LIMIT $num";
                $cases = $testmodel->query($sql);
                foreach($cases as $key => $case) {
                    $sum = 0;
                    $test_sql = "select test.id,test.content title,
                                test.level, test.answer, td.image480 as img,
                                test.test_type as type, test.point
                                from test
                                left join test_device td
                                on test.id = td.test_id
                                where pid = {$case['id']}
                                order by rand() limit 20";
                    $tests = $testmodel->query($test_sql);
                    foreach($tests as $k => $t) {
                        $tests[$k]['title'] = mb_substr(trim(strip_tags($t['title'])), 0, 15, 'utf-8');
                        $tests[$k]['client'] = '';
                        $images[] = $t['img'];
                        $sum += $t['point'];
                    }
                    $cases[$key]['score'] = strval($sum);
                    $cases[$key]['options'] = $tests;

                }
                $final[1] = $cases;
            }
        }
        $string = Json::encode(json_encode($final));
        $rand_name = rand(1000, 9999) . time();
        $tarFile = $rand_name . '.tar.gz';
        require 'Archive/Tar.php';
        $tar = new Archive_Tar($tarFile, 'gz');
        $tar->create($images);
        if ($tar->addString('exam.json', $string))
            echo C('URL') . $tarFile;
    }

    public function getExtraTest($difficult_from, $difficult_to, $number)
    {
        if($difficult_from == $difficult_to) {
            $level = "level != $difficult_to";
        } else {
            $level = "level <= $difficult_from or level >= $difficult_to";
        }
        $sql = "SELECT distinct(test.id),test.content title,
                        test.level, test.answer, td.image480 as img,
                        test.test_type as type, test.point
                        FROM `test`
                        inner join test_aspects ta
                        on test.id = ta.test_id
                        inner join aspects
                        on ta.aspects_id = aspects.id
                        left join test_device td
                        on test.id = td.test_id
                        WHERE $level
                        and pid = 0
                        ORDER BY rand() LIMIT $number";
        $test = M();
        return $test->query($sql);
    }

    /**
     * 练习接口
     * 根据区间范围和分类进行随机抽题
     * @todo 目前没有按照分类来进行取题，后面加上按照分类取题
     * @return string
     */
    public function exercise()
    {
        $rand_name = rand(1000, 9999) . time();
        $tarFile = $rand_name . '.tar.gz';
        require 'Archive/Tar.php';
        $tar = new Archive_Tar($tarFile, 'gz');  
        $range = Input::getVar($_GET['range']);
        $cat_id = Input::getVar($_GET['cid']);
        $data = explode($this->_ds, $range);
        $from = $data[0];
        $to   = $data[1];
        $num = $to - $from + 1;
        $test = M('test');
        $sql = "select t.id,t.content title, td.image480 as img, t.answer, t.point, t.level, t.test_type as type
            from test t
            left join test_device td
            on t.id = td.test_id
            where pid = 0
            order by pid ASC, test_type ASC
            limit $from, $num";
        $tests = $test->query($sql);

        $images = array();
        $final = array();
        foreach($tests as $key => $t) {
            $tests[$key]['title'] = mb_substr(trim(strip_tags($t['title'])), 0, 15, 'utf-8');
            $tests[$key]['client'] = '';
            $images[] = $t['img'];
            $final[0][] = $tests[$key];
        }
        $string = Json::encode(json_encode($final));
        $tar->create($images);
        if ($tar->addString('exam.json', $string))
            echo C('URL') . $tarFile;
    }

    /**
     * 单题总数
     * @todo 全部的题目总数，后期按照分类随机取题的时候要加入分类参数
     */
    public function testTotal()
    {
        $test = M('test');
        $count = $test->where('pid = 0')->count();
        echo $count;
    }
}