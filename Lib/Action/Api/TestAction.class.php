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
    {   //传入模板文件id
        $template_id = Input::getVar($_GET['tid']);
        $Template = M('template');
        $t = $Template->where("id = $template_id")->field('content,num,cat_id')->find();
        $cid = $t['cat_id'];
        $rules = json_decode($t['content']);
        $testmodel = M();
        $images = array();
        $final = array();
        foreach($rules as $rule) {
            $rule = (array)$rule;
            $rule_type=$rule['type'];
            if($rule_type!=4&&$rule_type!=5) {
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
                  $sql="SELECT * 
                  FROM (
                  SELECT 
                  test.id, test.content title, test.level,td.image480 AS img, 
                  test.test_type as type,test.point, td.answer
                  FROM `test` 
                  INNER JOIN test_aspects ta ON test.id = ta.test_id
                  INNER JOIN aspects ON ta.aspects_id = aspects.id
                  INNER JOIN test_device td ON test.id = td.test_id

                  WHERE $level
                  AND test.test_type =$rule_type
                  AND aspects.name
                  IN (
                    $aspects
                  )
                  AND pid =0
                  AND test.cat_id =$cid
                  ORDER BY rand( )  
                  ) AS newGroup group by id limit $num";
                $tests = $testmodel->query($sql);
                $true_num = count($tests);
                if($true_num < $num) {
                    $number = $num - $true_num;
                    $extra = $this->getExtraTest($difficult_from, $difficult_to, $number, $cid,$rule_type);               
                    $tests=array_merge($tests, $extra);
                }
                foreach($tests as $t) {
                    $t['title'] = $this->subString($t['title']);
                    $t['title'] = trim($t['title']);
                    $t['client'] = '';
                    $images[] = $t['img'];
                    $final[0][] = $t;
                }
            } else if($rule_type==4){//材料分析题
                $num = $rule['number'];
                $sql = "select id, description from casetest
                        where cat_id = $cid
                        ORDER BY rand() LIMIT $num";
                $cases = $testmodel->query($sql);
                foreach($cases as $key => $case) {
                    $sum = 0;
                    $test_sql = "select * from(select test.id,test.content title,
                                test.level, td.answer, td.image480 as img,
                                test.test_type as type, test.point
                                from test
                                inner join test_device td
                                on test.id = td.test_id
                                where pid = {$case['id']}
                                order by rand() limit 20)AS newGroup group by id";
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
                $final[1]= $cases; 
            }else if($rule_type==5){
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
                 $sql="select test.id,test.content title,
                  test.level,te.answer,td.image480 as img,
                  test.test_type as type,test.point
                  from test
                  inner join test_essay te on te.test_id=test.id
                  inner join test_aspects ta ON test.id = ta.test_id
                  inner join test_device td on test.id=td.test_id
                  inner join aspects ON ta.aspects_id = aspects.id
                  where $level
                  AND test.test_type=$rule_type
                  AND aspects.name
                  IN(
                    $aspects
                  )
                  AND pid=0
                  AND test.cat_id=$cid
                  ORDER BY rand() limit $num";
                  $tests = $testmodel->query($sql);
                  foreach($tests as $t) {
                      $t['title'] = $this->subString($t['title']);
                      $t['title'] = trim($t['title']);
                      $t['client'] = '';
                      $images[] = $t['img'];
                      $final[2][]=$t;
                  }            
            }
        }
        $string = Json::encode(json_encode($final));
        echo $this->tarFile($images, $string);

    }

    protected function subString($title)
    {
        $string = mb_substr(trim(strip_tags($title)), 0, 14, 'utf-8');
        $string =  str_replace(PHP_EOL, '', $string);
        $string =  str_replace("\r", ' ', $string);
        $string =  str_replace(" ", '', $string);
        return  str_replace("\t", ' ', $string);
    }
    //题目不足模板的要求，随机抽剩下的
    public function getExtraTest($difficult_from, $difficult_to, $number, $cid,$rule_type)
    {
        if($difficult_from == $difficult_to) {
            $level = "level != $difficult_to";
        } else {
            $level = "level < $difficult_from or level > $difficult_to";
        }
          $sql="SELECT * 
          FROM (
              SELECT 
              test.id, test.content title, test.level,td.image480 AS img, 
              test.test_type as type,test.point, td.answer
              FROM `test` 
              INNER JOIN test_aspects ta ON test.id = ta.test_id
              INNER JOIN aspects ON ta.aspects_id = aspects.id
              INNER JOIN test_device td ON test.id = td.test_id
              WHERE $level
              AND test.test_type =$rule_type
              AND pid =0
              AND test.cat_id =$cid
              ORDER BY rand( )
          ) AS newGroup group by id limit  $number";                                  
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
        $sql = "select *from(select d.id,d.content title,d.image480 img, d.answer, d.point, d.level, d.test_type as type from 
                (select a.id,a.pid,a.cat_id,a.content,a.level,a.test_type,a.point,a.date,a.author,td.image480,td.answer, (select count(id) from test where test.id<=a.id and cat_id = $cid and pid = 0) as rownum
                from test as a inner join test_device as td on td.test_id = a.id where pid = 0 and cat_id = $cid order by rand()) as d 
                where rownum between $from and $to order by test_type ASC) as ran group by id";
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