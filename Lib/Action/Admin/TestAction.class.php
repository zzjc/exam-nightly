<?php
import('ORG.Util.Input');
import("ORG.Page.Page");
class TestAction extends Action
{
    private $gid;
    private $picUrl;
    public function _initialize()
    {
        if(!Session::is_set('manager')) {
            $this->redirect('Auth/login');
        }
        $this->gid = $_SESSION['gid'];
    }
    /*
      *添加知识点
    */
    public function addAspects(){
       $aspects=M("aspects");
       $data["name"]=Input::getVar($_POST["name"]);
       $data["cat_id"]=Input::getVar($_POST["cat_id"]);
       $sql="select count(id) as num from aspects where name='".$data["name"]."' and cat_id=".$data["cat_id"];
       $m=M();
       $num=$m->query($sql);
       if(!$num[0]["num"]){
         $aspects->add($data);
         echo true;
       }else{
         echo false;
       }


    }
    public function getAspect(){
          $cate=M("category");
          $aspects=M("aspects");
          $categoryId=$_POST["categoryId"];
          $arrAsp=$aspects->where("cat_id=".$categoryId)->select();
          echo json_encode($arrAsp);
    }
    /*
      *将题目类型写入cookie
    */
    public function ses_type(){
      setcookie("test_type",$_POST['test_type'],-1,"/");
      setcookie("cate",$_POST['cate'],-1,"/");
    }
    /*
    *试题列表分类获取题目信息内容
    */
     public function index()
    { 
        $test=M("test");
        $casetest=M("casetest");
        $essay=M("test_essay");
        if($this->isAjax()){
            $categoryId = Input::getVar($_GET["categoryId"]);
            $type       = Input::getVar($_GET["type"]);
            $aspectId   = Input::getVar($_GET["aspectId"]);
            $from       = strtotime(Input::getVar($_GET["from"]));
            $to         = strtotime(Input::getVar($_GET["to"]));
            $author     = Input::getVar($_GET["author"]);
            if ($from && $to) {
                $str = "test.test_type={$type} and test.pid=0 and test.cat_id={$categoryId} and test.date between {$from} and {$to}";
              if ($aspectId != 0) {
                  $str .= " and a.id={$aspectId}";
              }
              if ($author) {
                if($_SESSION['gid'] !=0){
                  $str .= " and test.author='{$author}'";
                } elseif ($_SESSION['gid'] ==0 && $_SESSION['username'] != $author) {
                    $str .= " and test.author='{$author}'";
                }
              }
            } else {
                $str = "test.test_type={$type} and test.pid=0 and test.cat_id={$categoryId}";
                if ($aspectId != 0) {
                    $str .= " and a.id={$aspectId}";
                }
                if ($author) {
                    if($_SESSION['gid'] !=0){
                      $str .= " and test.author='{$author}'";
                    } elseif ($_SESSION['gid'] ==0 && $_SESSION['username'] != $author) {
                        $str .= " and test.author='{$author}'";
                    }
                }              
            }
            switch ($type) {
              case 1:
                echo $this->indexTest($test,$str);
                break;
              case 2:
                echo $this->indexTest($test,$str);
                break;
              case 3:
                echo $this->indexTest($test,$str);
                break;
              case 4:
                $confident['casetest.cat_id'] = $categoryId;
                if ($from && $to) {
                    $str="casetest.cat_id={$categoryId} and t.date between {$from} and {$to} ";
                    if ($aspectId != 0) {
                        $str .= "  and a.id={$aspectId}";                     
                    }
                    if ($author) {
                      if($_SESSION['gid'] !=0){
                        $str .= " and t.author='{$author}'";
                      } elseif ($_SESSION['gid'] ==0 && $_SESSION['username'] != $author) {
                          $str .= " and t.author='{$author}'";
                      }
                    }                    
                } else {
                    $str="casetest.cat_id={$categoryId}";
                    if ($aspectId != 0) {
                        $str .= "  and a.id={$aspectId}";                     
                    }
                    if ($author) {
                      if($_SESSION['gid'] !=0){
                        $str .= " and t.author='{$author}'";
                      } elseif ($_SESSION['gid'] ==0 && $_SESSION['username'] != $author) {
                          $str .= " and t.author='{$author}'";
                      }
                    }                                           
                }
                $count = $casetest->field("casetest.description,casetest.id")->join("inner join test as t  on casetest.id=t.pid")->join("inner join test_aspects as ta on t.id=ta.test_id")
                       ->join("inner join aspects a on ta.aspects_id=a.id")->where($str)->count();  
                $page = new page($count,10);
                $page -> setConfig('theme', "%totalRow% %header% %nowPage%/%totalPage% 页%first%  %prePage% %upPage%  %linkPage%  %downPage% %nextPage% %end% "); 
                $show = $page->show();  
                $list = $casetest->field("casetest.description,casetest.id")->join("inner join test as t  on casetest.id=t.pid")->join("inner join test_aspects as ta on t.id=ta.test_id")
                                ->join("inner join aspects a on ta.aspects_id=a.id")->where($str)->group('casetest.id')
                                ->limit($page->firstRow.','.$page->listRows)->select();
               // echo $casetest->getLastSql();
                foreach($list as $key=>$val){
                    $str = trim(strip_tags($val["description"]));
                    $str = preg_replace('/\s(?=\s)/','',$str);
                    if (mb_strlen(preg_replace('/[\n\r\t]/','',$str),"utf-8") > 40) {
                        $omiss = "......";
                    } else {
                        $omiss = "";
                    };                       
                    $str = mb_substr(preg_replace('/[\n\r\t]/','',$str),0,40,"utf-8");
                    switch ($this->gid) {
                      case 0:
                        echo "<tr><td class='left'>".$str.$omiss."</td><td>...</td><td>...</td><td>...</td>
                              <td><a href='javascript:void(0)' onclick='openUpdateSets(".$val["id"].")'>
                             修改</a>&nbsp&nbsp&nbsp&nbsp<a href='javascript:void(0)' onclick=\"if(confirm('确认要删除')){del(".$val["id"].")}\">
                             删除</a></td></tr>";
                      break;
                      default:
                        echo "<tr><td class='left'>".$str.$omiss."</td><td>...</td><td>...</td><td>...</td>
                              <td><a href='javascript:void(0)' onclick='openUpdateSets(".$val["id"].")'>
                             修改</a></td></tr>";
                    }
                    if($key == count($list)-1){
                      echo "<tr><td colspan='5'>".$show."</td></tr>";

                    }
                }
                break;
              case 5:          
                $count = $test->field("test.id,test.point,test.answer,test.content,te.answer as ea")->join("inner join test_essay as te on test.id=te.test_id")->join('inner join test_aspects ta ON test.id =ta.test_id ')
                        ->join('inner join aspects a on ta.aspects_id=a.id')->where($str)->count();         
                $page  = new page($count,10);
                $page -> setConfig('theme', "%totalRow% %header% %nowPage%/%totalPage% 页%first%  %prePage% %upPage%  %linkPage%  %downPage% %nextPage% %end% "); 
                $show = $page->show();  
                $list = $test->field("test.id,test.point,test.answer,test.content,te.answer as ea")->join("inner join test_essay as te on test.id=te.test_id")->join('inner join test_aspects ta ON test.id =ta.test_id ')
                        ->join('inner join aspects a on ta.aspects_id=a.id')->where($str)->limit($page->firstRow.','.$page->listRows)->select();
                //echo $test->getLastSql(); 
                foreach($list as $key=>$val){
                    $str=trim(strip_tags($val["content"]));
                    $str=preg_replace('/\s(?=\s)/','',$str);
                    if (mb_strlen(preg_replace('/[\n\r\t]/','',$str),"utf-8") > 20) {
                        $omiss = "......";
                    } else {
                        $omiss = "";
                    };                       
                    $str=mb_substr(preg_replace('/[\n\r\t]/','',$str),0,20,"utf-8");
                    $ea=trim(strip_tags($val["ea"]));
                    $ea=preg_replace('/\s(?=\s)/','',$ea);
                    if (mb_strlen(preg_replace('/[\n\r\t]/','',$ea),"utf-8") > 20) {
                        $omiea = "......";
                    } else {
                        $omiea = "";
                    };                        
                    $ea=mb_substr(preg_replace('/[\n\r\t]/','',$ea),0,20,"utf-8");
                    switch($this->gid){
                      case 0:                    
                        echo "<tr><td>...</td><td class='left'>".$str.$omiss.
                             "</td><td class='left'>".$ea.$omiea."</td><td>".strip_tags($val["point"]).
                             "</td><td><a href='javascript:void(0)' onclick='openUpdateTest(".$val["id"].")'>
                             修改</a>&nbsp&nbsp&nbsp&nbsp<a href='javascript:void(0)' onclick=\"if(confirm('确认要删除')){del(".$val["id"].")}\">
                             删除</a></td></tr>";
                      break;
                      default:
                        echo "<tr><td>...</td><td class='left'>".$str.$omiss.
                             "</td><td class='left'>".$ea.$omiea."</td><td>".strip_tags($val["point"]).
                             "</td><td><a href='javascript:void(0)' onclick='openUpdateTest(".$val["id"].")'>
                             修改</a></td></tr>";
                    }     
                    if($key==count($list)-1){
                      echo "<tr><td colspan='5'>".$show."</td></tr>";
                    }
                }                  
            }
        }else{
          $cate = M("category");
          $aspects = M("aspects");
          $manager = M("manager");
          if ($this->gid == 0) {
              $arrCate = $cate->select();
              $arrAsp = $aspects->where("cat_id=".$arrCate[0]["id"])->select();
              $arrAuthor = $manager->select();         
          } else {
              $arrCate = $cate->where('group_id = ' . $this->gid)->select();
              $arrAsp = $aspects->where("cat_id=" . $arrCate[0]["id"])->select();
              $arrAuthor = array(array("name"=>$_SESSION['username'])); 
          }
          $this -> assign("arrCate",$arrCate);
          $this -> assign("arrAsp",$arrAsp);
          $this -> assign("arrAuthor",$arrAuthor);
          $this -> display();
         }
    }
    /*
    *试题列表分类获取类型为1，2，3的题目信息内容
    */
    public function indexTest($test,$str){
      $trStr="";
      $count=$test->field("test.id,test.point,test.answer,test.content")->join('inner join test_aspects ta ON test.id =ta.test_id ')
              ->join('inner join aspects a on ta.aspects_id=a.id')->where($str)->count();  
      $page=new Page($count,10);  
      $page->setConfig('theme', "%totalRow% %header% %nowPage%/%totalPage% 页%first%  %prePage% %upPage%  %linkPage%  %downPage% %nextPage% %end% ");
      $show=$page->show();  
      $list = $test->field("test.id,test.point,test.answer,test.content")->join('inner join test_aspects ta ON test.id =ta.test_id ')
              ->join('inner join aspects a on ta.aspects_id=a.id')->where($str)->limit($page->firstRow.','.$page->listRows)->select();
      foreach($list as $key=>$val){
          $str = trim(strip_tags($val["content"]));
          $str = preg_replace('/\s(?=\s)/','',$str);
          if (mb_strlen(preg_replace('/[\n\r\t]/','',$str),"utf-8") > 40) {
              $omiss = "......";
          } else {
              $omiss = "";
          };          
          $str = mb_substr(preg_replace('/[\n\r\t]/','',$str),0,40,"utf-8");
          switch($this -> gid){
            case 0:
              $trStr .= "<tr><td>...</td><td class='left'>".$str.$omiss.
                        "</td><td>".$val["answer"]."</td><td>".strip_tags($val["point"]).
                        "</td><td><a href='javascript:void(0)' onclick='openUpdateTest(".$val["id"].")'>
                        修改</a>&nbsp&nbsp&nbsp&nbsp<a href='javascript:void(0)' onclick=\"if(confirm('确认要删除')){del(".$val["id"].")}\">
                        删除</a></td></tr>";
            break;
            default:
              $trStr.= "<tr><td>...</td><td class='left'>".$str.$omiss.
                       "</td><td>".$val["answer"]."</td><td>".strip_tags($val["point"]).
                       "</td><td><a href='javascript:void(0)' onclick='openUpdateTest(".$val["id"].")'>
                       修改</a></td></tr>";
          }
          if($key==count($list)-1){
            $trStr.="<tr><td colspan='5'>".$show."</td></tr>";

        }
      }
      //return $test->getLastSql();

        return $trStr;
    }
    /*
     *对题目分类添加
    */
    public function add()
    {
       header("Content-type: text/html; charset=utf-8");
       $cate=M("category");
       if($this->isPost()){
          $descriptionOb=M("casetest");
          $testOb=M("test");
          $aspectOb=M("aspects");
          $test_aspectsOb=M("test_aspects");
          $td=M("test_device");
          $test_type=Input::getVar($_POST['test_type']);
          $cat_id=Input::getVar($_POST['cat_id']);
          if($test_type==4){
            $pid=$this->addCasetest($descriptionOb,$_POST);
          }else{
            $pid="";
          }
          //添加题目并生成题目图片
          for($i=0;$i<count($_POST["level"]);$i++){
            //插入知识点关联
            $aspectArr= json_decode(str_replace("\\","",$_POST["name"][$i]),true);
            $testId=$this->addTest($testOb,$_POST,$pid,$i,$test_type);
            for($j=0;$j<count($aspectArr);$j++){
              $aspectsId=$aspectOb->field("id")->where("name='{$aspectArr[$j]}' and cat_id=".$cat_id)->find();
              $this->addTestAspect($test_aspectsOb,$_POST,$testId,$aspectsId["id"]);
            } 
            //添加图片
            $this->addPicture($testId,$_POST,$i);
          } 
            $this->redirect('Test/add');
        }else{
            if($this->gid==0){
              $arrCate=$cate->select();
            }else{
             $arrCate=$cate->where('group_id = '.$this->gid)->select();
            } 
             $this->assign("arrCate",$arrCate);
             $this->display();
         } 
    }
   /*
   *添加题组案例题
   */
    public function addCasetest($model,$post){
       if($model->autoCheckToken($post)){
         $data['cat_id']=Input::getVar($post["category"]);
         $data['description']=Input::getVar($post["description"]);
         if($model->add($data)){
            return mysql_insert_id();
          }else{
            echo "插入题目材料失败";
           }
       }else{
          die("表单令牌错误");
        }
    }
    /*
    *添加试卷题目
    */
    public function addTest($model,$post,$pid,$i,$test_type){
      if($model->autoCheckToken($post)){
        $essay=M("test_essay");
        $data['cat_id']=Input::getVar($post["category"]);  
        $data["level"]=Input::getVar($post["level"][$i]);  
        $data["answer"]=Input::getVar($post["answer"][$i]);     
        $data["point"]=Input::getVar($post["point"][$i]); 
        $data["author"]=$_SESSION['username'];    
        $data["date"]=time();
        $data['content']=Input::getVar($post["content"][$i]);            
        $data["pid"]=$pid;   
        $data["test_type"]=$test_type;        
        switch($test_type){
           case "5":
           $data["answer"]=""; 
            if($model->add($data)){
                $test_id=mysql_insert_id();
                $dat["test_id"]=$test_id;
                $dat["answer"]=Input::getVar($post["answer"][$i]);
                $essay->add($dat);
                return $test_id;  
            }                                                     
          break;
          default:
            if($test_type==4){
              $data["test_type"]=Input::getVar($post["setsType"][$i]);
              $model->add($data);
              return mysql_insert_id();
            }else{
              $model->add($data);
              return mysql_insert_id();            
            }
        }
      }else{
          echo "表单令牌错误";
      }

    }
    /*
    *
    *分类添加图片
    */
    public function addPicture($testId,$post,$i){
     $td=M("test_device");
     $this->picUrl="cd ".C('DOCUMENT_ROOT').";".C('PHANTOMJS_PATH')." rasterize.js ";
     $dir = 'Data/html';
     $template = "Data/template.html"; 
     $template_html = file_get_contents($template);          
     $test_type=Input::getVar($post['test_type']);
     $content=Input::getVar($post["content"][$i]);
     $option=$post["option"][$i];
     print_r($option);
     $new = str_replace('{REPLACE_HOLDER}', $content, $template_html);                                     
     $html_name = $dir . '/' . $testId. '.html';
     switch($test_type){
       case 1:
       //写入数据库
         for($c=0;$c<count($option);$c++){
            $this->addOption($option[$c],$testId,$c);
         }
         //生成图 
         file_put_contents($html_name, $new);
         $url=$this->picUrl."Data/html/".$testId.".html Storage/image480/".$testId.".gif";
         exec($url);
         for($j=0;$j<count($option);$j++){
             $hn=str_replace('{REPLACE_HOLDER}',$option[$j], $template_html);
             $html_num = $dir . '/' . $testId. '_'.$j.'.html';
             file_put_contents($html_num,$hn);
             $url=$this->picUrl."Data/html/".$testId."_{$j}.html Storage/image480/".$testId."_{$j}.gif";
             exec($url);
         }                                       


             $data['test_id']=$testId;
             $data['image480'] = "Storage/image480/{$testId}.gif";
             $td->add($data);   
           break;
      case 2:
         //写入数据库
         for($c=0;$c<count($option);$c++){
            $this->addOption($option[$c],$testId,$c);
         }
         //生成图 
         file_put_contents($html_name, $new);
         $url=$this->picUrl."Data/html/".$testId.".html Storage/image480/".$testId.".gif";
         exec($url);
         for($j=0;$j<count($option);$j++){
             $hn=str_replace('{REPLACE_HOLDER}',$option[$j], $template_html);
             $html_num = $dir . '/' . $testId. '_'.$j.'.html';
             file_put_contents($html_num,$hn);
             $url=$this->picUrl."Data/html/".$testId."_{$j}.html Storage/image480/".$testId."_{$j}.gif";
             exec($url);
         }                                       

             $data['test_id']=$testId;
             $data['image480'] = "Storage/image480/{$testId}.gif";
             $td->add($data);   
           break;
      case 3:
       //写入数据库
         for($c=0;$c<count($option);$c++){
            $this->addOption($option[$c],$testId,$c);
         }
         //生成图 
         file_put_contents($html_name, $new);
         $url=$this->picUrl."Data/html/".$testId.".html Storage/image480/".$testId.".gif";
         exec($url);
         for($j=0;$j<count($option);$j++){
             $hn=str_replace('{REPLACE_HOLDER}',$option[$j], $template_html);
             $html_num = $dir . '/' . $testId. '_'.$j.'.html';
             file_put_contents($html_num,$hn);
             $url=$this->picUrl."Data/html/".$testId."_{$j}.html Storage/image480/".$testId."_{$j}.gif";
             exec($url);
         }                                       
             $data['test_id']=$testId;
             $data['image480'] = "Storage/image480/{$testId}.gif";
             $td->add($data);   
           break;
      case 4:
         $setsType=Input::getVar($post["setsType"][$i]);
         $post['test_type']=$setsType;
         $this->addPicture($testId,$post,$i);
         break;
      case 5:
        $replace_content="<p>".$content."</p>";
        $new = str_replace('{REPLACE_HOLDER}', $replace_content, $template_html);
        file_put_contents($html_name, $new);
        $url=$this->picUrl."Data/html/".$testId.".html Storage/image480/".$testId.".gif";
        exec($url); 
        $data['test_id']=$testId;
        $data['image480'] = "Storage/image480/{$testId}.gif";
        $td->add($data);           
    }  
  }

    /*
    *添加试题选择项
    */
    public function addOption($option,$testId,$num){
     $optionOb=M("test_choice");
     $data['option']=Input::getvar($option);
     $data['test_id']=$testId;
     $data["image480"]= "Storage/image480/{$testId}_{$num}.gif";
     $optionOb->add($data);
    }


    /*
    *添加题目与知识点关联
    */
    public function addTestAspect($model,$post,$testId,$aspectsId){
      if($model->autoCheckToken($post)){
          $data['aspects_id']=$aspectsId;
          $data['test_id']=$testId;
          $model->add($data);   
      }else{
        die("表单令牌错误");
      }


    }
    /*
    *获取一个id的题目信息
    */
    public function getTitleInfo($setsTitleId){
      $test=M("test");
      $test_aspects=M("test_aspects");
      $aspects=M("aspects");
      $ea=M("test_essay");
      $test_type=$_POST["test_type"];
      $id=Input::getVar($_POST["id"]);
      $aspectsId=$test_aspects->field("aspects_id")->where("test_id=".$id)->select();
      $aspectsName=array();
      for($i=0;$i<count($aspectsId);$i++){
        $aspectsName[]=$aspects->field("name")->where("id=".$aspectsId[$i]["aspects_id"])->find();
      }
      $titleInfo=$test->where("id=".$id)->find();
      if($test_type==5){
        $titleInfo["eaAnswer"]=$ea->field("answer")->where("test_id=".$id)->find();
      }
      $titleInfo["name"]=$aspectsName;
      echo json_encode($titleInfo);
    }
    /*
    *修改题目
    */
    public function updateTest(){
       $test=M("test");
       $essay=M("test_essay");
       $test_aspects=M("test_aspects");
       $aspectsOb=M("aspects");
       $test_choice=M("test_choice");
       $opArr=explode(",",$_POST["option"]);
       $aspects=Input::getVar($_POST["aspects"]);
       $dat["answer"]=Input::getVar($_POST["eaAnswer"]);
       $data["answer"]=Input::getVar($_POST["answer"]);
       $data["point"]=Input::getVar($_POST["point"]);
       $data["content"]=Input::getVar($_POST["question"]);
       $data["level"]=Input::getVar($_POST["level"]);
       $testId=Input::getVar($_POST["id"]);
       $cat_id=Input::getVar($_POST["cat_id"]);
       $test_type=Input::getVar($_POST["test_type"]);
       $sets_type=Input::getVar($_POST["sets_type"]);
       if($test_type==4){
        $test_type=$sets_type;  
       }
       $test->where('id='.$testId)->save($data);
       if($test_type==5){
        $essay->where("test_id=".$testId)->save($dat);
       }
       //修改知识点
       $aspectArr=json_decode($aspects);
       @$test_aspects->where("test_id=".$testId)->delete();
       for($i=0;$i<count($aspectArr);$i++){
          $aspectsId=$aspectsOb->where("name='".$aspectArr[$i]."' and cat_id=".$cat_id)->getField("id");
          $datt['aspects_id']=$aspectsId;
          $datt['test_id']=$testId;
          $test_aspects->add($datt);   
       }
       //修改题目选项
       if($test_type!=5){
         $test_id=$test_choice->where("test_id=".$testId)->delete();
         $op["test_id"]=$testId;
         for($i=0;$i<count($opArr);$i++){
          $op["option"]=input::getVar($opArr[$i]);
          $op["image480"]="Storage/image480/".$testId."_".$i.".gif";
          $test_choice->add($op);
         }
       }
    }
    /*
      *获取题目选项
    */
    public function getTestOption(){
      $test_choice=M("test_choice");
      $option=$test_choice->field('option')->where("test_id=".$_POST['testId'])->order('id asc')->select();
      echo json_encode($option);
    }
    /*
    *根据id获取材料阅读题的信息
    */
    public function getTitleSetsInfo(){
      $casetest=M("casetest");
      $test=M("test");
      $aspects=M("aspects");
      $test_aspects=M("test_aspects");
      $setsId=Input::getVar($_POST["id"]);
      $description=$casetest->where("id=".$setsId."")->getField("description");
      $titleInfo=$test->where("pid=".$setsId."")->select();
      for($j=0;$j<count($titleInfo);$j++){
        $aspectsId=$test_aspects->field("aspects_id")->where("test_id=". $titleInfo[$j]["id"])->select();
        $aspectsName=array();
        for($i=0;$i<count($aspectsId);$i++){
          $aspectsName[]=$aspects->field("name")->where("id=".$aspectsId[$i]["aspects_id"])->find();
        }
        $titleInfo[$j]["name"]=$aspectsName;
      }
      array_unshift($titleInfo,$description);
      echo json_encode($titleInfo); 
    }
    /*
    *修改材料阅读题材料
    */
    public function updateDescription(){
      $setsId=Input::getVar($_POST["id"]);
      $data["description"]=Input::getVar($_POST["description"]);
      $casetest=M("casetest");
      $casetest->where("id=".$setsId)->save($data);
    }
    /*
    *删除试题
    */
    public function del()
    {   
      $type=Input::getVar($_POST["type"]);
      $id=Input::getVar($_POST["id"]);
      $casetest=M("casetest");
      $test=M("test");
      $test_aspects=M("test_aspects");
      $td=M("test_choice");
      $test_device=M("test_device");
      $ea=M("test_essay");
      if($type!=4){
        $test->where("id=".$id)->delete();
        $test_aspects->where("test_id=".$id)->delete();
        $td->where("test_id=".$id)->delete();
        $ea->where("test_id=".$id)->delete();
        $test_device->where("test_id=".$id)->delete();
        unlink('Data/html/' .$id.'.html');
        unlink('Storage/image480/'.$id.".gif");
        for($i=0;$i<10;$i++){
          unlink('Data/html/' .$id."_".$i.'.html');
          unlink('Storage/image480/'.$id."_".$i.".gif");
        }
      }else if($type==4){  
        $testId=$test->field("id")->where("pid=".$id)->select();
        for($i=0;$i<count($testId);$i++){
          $test_aspects->where("test_id=".$testId[$i]["id"])->delete();
          $td->where("test_id=".$testId[$i]["id"])->delete();
          $test_device->where("test_id=".$testId[$i]["id"])->delete();
          unlink('Data/html/'.$testId[$i]["id"].'.html');
          unlink('Storage/image480/'.$testId[$i]["id"].".gif");
          for($j=0;$j<10;$j++){
            unlink('Data/html/'.$testId[$i]["id"]."_".$j.'.html');
            unlink('Storage/image480/'.$testId[$i]["id"]."_".$j.".gif");
          }
        }
        if($casetest->where("id=".$id)->delete()&&$test->where("pid=".$id)->delete()){
          echo true;  
        }else{
          echo false;
        }

      }
    }
        
}