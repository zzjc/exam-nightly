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
        $t = $Template->where("id = $template_id")->field('content,num,cat_id')->find();
        $cid = $t['cat_id']; //分类ID
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
                        inner join test_device td
                        on test.id = td.test_id
                        WHERE $level
                        and aspects.name in ($aspects)
                        and pid = 0
                        and test.cat_id = $cid
                        ORDER BY rand() LIMIT $num";
                $tests = $testmodel->query($sql);
                $true_num = count($tests);
                if($true_num < $num) {
                    $number = $num - $true_num;
                    $extra = $this->getExtraTest($difficult_from, $difficult_to, $number, $cid);
                    array_merge($tests, $extra);
                }
                foreach($tests as $t) {
                    $t['title'] = $this->subString($t['title']);
                    $t['title'] = trim($t['title']);
                    $t['client'] = '';
                    $images[] = $t['img'];
                    $final[0][] = $t;
                }
            } else {
                $num = $rule['number'];
                $sql = "select id, description from casetest
                        where cat_id = $cid
                        ORDER BY rand() LIMIT $num";
                $cases = $testmodel->query($sql);
                foreach($cases as $key => $case) {
                    $sum = 0;
                    $test_sql = "select test.id,test.content title,
                                test.level, test.answer, td.image480 as img,
                                test.test_type as type, test.point
                                from test
                                inner join test_device td
                                on test.id = td.test_id
                                where pid = {$case['id']}
                                order by rand() limit 20";
                    $tests = $testmodel->query($test_sql);
                    foreach($tests as $k => $t) {
                        $tests[$k]['title'] = $this->subString($t['title']);
                        $tests[$k]['client'] = '';
                        $images[] = $t['img'];
                        $sum += $t['point'];
                    }
                    $cases[$key]['description'] = strip_tags($cases[$key]['description']);
                    $cases[$key]['score'] = strval($sum);
                    $cases[$key]['options'] = $tests;

                }
                $final[1] = $cases;
            }
        }
        $string = Json::encode(json_encode($final));
        echo $this->tarFile($images, $string);

    }

    protected function subString($title)
    {
        return mb_substr(trim(strip_tags($title)), 0, 14, 'utf-8');
    }

    public function getExtraTest($difficult_from, $difficult_to, $number, $cid)
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
                        inner join test_device td
                        on test.id = td.test_id
                        WHERE $level
                        and pid = 0
                        and test.cat_id = $cid
                        ORDER BY rand() LIMIT $number";
        $test = M();
        return $test->query($sql);
    }

    /**
     * 练习接口
     * 根据区间范围和分类进行随机抽题
     * @return string
     */
    public function exercise()
    {
        $range = Input::getVar($_GET['range']);
        $cid = Input::getVar($_GET['cid']);
        if(!$cid)$cid = 1;
        $data = explode($this->_ds, $range);
        $from = $data[0];
        $to   = $data[1];
        $test = M('test');
        $sql = "select t.id,t.content title, td.image480 as img, t.answer, t.point, t.level, t.test_type as type
            from test t
            left join test_device td
            on t.id = td.test_id
            where pid = 0
            and t.cat_id = $cid
            and id >= $from
            and id <= $to
            order by test_type ASC";
        $tests = $test->query($sql);

        $images = array();
        $final = array();
        foreach($tests as $key => $t) {
            $tests[$key]['title'] = $this->subString($t['title']);
            $tests[$key]['client'] = '';
            $images[] = $t['img'];
            $final[0][] = $tests[$key];
        }
        $string = Json::encode(json_encode($final));
        echo $this->tarFile($images, $string);
    }

    /**
     * 单题总数
     */
    public function testTotal()
    {
        $cid = Input::getVar($_GET['cid']);
        if(!$cid)$cid = 1;
        $test = M('test');
        $count = $test->where('pid = 0 and cat_id = ' . $cid)->count();
        echo $count;
    }

    protected function tarFile($images, $string)
    {
        $rand_name = rand(1000, 9999) . time();
        $tarFile = $rand_name . '.tar.gz';
        require 'Archive/Tar.php';
        $tar = new Archive_Tar($tarFile, 'gz');
        $tar->create($images);
        if ($tar->addString('exam.json', $string))
            return C('URL') . $tarFile;
        else
            return 'tar file occur errors';
    }
}