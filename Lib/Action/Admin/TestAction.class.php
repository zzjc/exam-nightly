<?php
import('ORG.Util.Input');
import("ORG.Page.Page");
class TestAction extends Action
{
    private $gid;
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
       $aspects->add($data);

    }
    /*
      *将题目类型写入session
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
            $categoryId=Input::getVar($_GET["categoryId"]);
            $type=Input::getVar($_GET["type"]);
            switch($type){
              case 1:
                $confident['test_type']=1;
                $confident['pid']=0;
                $confident['cat_id']=$categoryId;
                $count=$test->where($confident)->count();  
                $page=new Page($count,10);  
                $page->setConfig('theme', "%totalRow% %header% %nowPage%/%totalPage% 页%first%  %prePage% %upPage%  %linkPage%  %downPage% %nextPage% %end% ");
                $show=$page->show();  
                $list = $test->where($confident)->limit($page->firstRow.','.$page->listRows)->select();
                foreach($list as $key=>$val){
                    $str=trim(strip_tags($val["content"]));
                    $str=preg_replace('/\s(?=\s)/','',$str);
                    $str=mb_substr(preg_replace('/[\n\r\t]/','',$str),0,40,"utf-8");
                    switch($this->gid){
                      case 0:
                        echo "<tr><td>...</td><td>".$str."...".
                             "</td><td>".$val["answer"]."</td><td>".strip_tags($val["point"]).
                             "</td><td><a href='javascript:void(0)' onclick='openUpdateTest(".$val["id"].")'>
                             修改</a>&nbsp&nbsp&nbsp&nbsp<a href='javascript:void(0)' onclick=\"if(confirm('确认要删除')){del(".$val["id"].")}\">
                             删除</a></td></tr>";
                      break;
                      default:
                        echo "<tr><td>...</td><td>".$str."...".
                             "</td><td>".$val["answer"]."</td><td>".strip_tags($val["point"]).
                             "</td><td><a href='javascript:void(0)' onclick='openUpdateTest(".$val["id"].")'>
                             修改</a></td></tr>";
                    }
                    if($key==count($list)-1){
                      echo "<tr><td colspan='5'>".$show."</td></tr>";

                    }
                }
                break;
              case 2:
                $confident['test_type']=2;
                $confident['pid']=0;
                $confident['cat_id']=$categoryId;
                $count=$test->where($confident)->count();  
                $page=new page($count,10);
                $page->setConfig('theme', "%totalRow% %header% %nowPage%/%totalPage% 页%first%  %prePage% %upPage%  %linkPage%  %downPage% %nextPage% %end% ");  
                $show=$page->show();  
                $list = $test->where($confident)->limit($page->firstRow.','.$page->listRows)->select();
                foreach($list as $key=>$val){
                    $str=trim(strip_tags($val["content"]));
                    $str=preg_replace('/\s(?=\s)/','',$str);
                    $str=mb_substr(preg_replace('/[\n\r\t]/','',$str),0,40,"utf-8");
                    switch($this->gid){
                      case 0:
                         echo "<tr><td>...</td><td>".$str."...".
                           "</td><td>".$val["answer"]."</td><td>".strip_tags($val["point"]).
                           "</td><td><a href='javascript:void(0)' onclick='openUpdateTest(".$val["id"].")'>
                           修改</a>&nbsp&nbsp&nbsp&nbsp<a href='javascript:void(0)' onclick=\"if(confirm('确认要删除')){del(".$val["id"].")}\">
                           删除</a></td></tr>";
                      break;
                      default:
                         echo "<tr><td>...</td><td>".$str."...".
                           "</td><td>".$val["answer"]."</td><td>".strip_tags($val["point"]).
                           "</td><td><a href='javascript:void(0)' onclick='openUpdateTest(".$val["id"].")'>
                           修改</a></td></tr>";
                    }
                    if($key==count($list)-1){
                      echo "<tr><td colspan='5'>".$show."</td></tr>";

                    }

                }
                break;
              case 3:
                $confident['test_type']=3;
                $confident['pid']=0;
                $confident['cat_id']=$categoryId;
                $count=$test->where($confident)->count();  
                $page=new page($count,10);
                $page->setConfig('theme', "%totalRow% %header% %nowPage%/%totalPage% 页%first%  %prePage% %upPage%  %linkPage%  %downPage% %nextPage% %end% "); 
                $show=$page->show();  
                $list = $test->where($confident)->limit($page->firstRow.','.$page->listRows)->select();
                foreach($list as $key=>$val){
                    $str=trim(strip_tags($val["content"]));
                    $str=preg_replace('/\s(?=\s)/','',$str);
                    $str=mb_substr(preg_replace('/[\n\r\t]/','',$str),0,40,"utf-8");
                    switch($this->gid){
                      case 0:
                        echo "<tr><td>...</td><td>".$str."...".
                             "</td><td>".$val["answer"]."</td><td>".strip_tags($val["point"]).
                             "</td><td><a href='javascript:void(0)' onclick='openUpdateTest(".$val["id"].")'>
                             修改</a>&nbsp&nbsp&nbsp&nbsp<a href='javascript:void(0)' onclick=\"if(confirm('确认要删除')){del(".$val["id"].")}\">
                             删除</a></td></tr>";
                      break;
                      default:
                        echo "<tr><td>...</td><td>".$str."...".
                             "</td><td>".$val["answer"]."</td><td>".strip_tags($val["point"]).
                             "</td><td><a href='javascript:void(0)' onclick='openUpdateTest(".$val["id"].")'>
                             修改</a></td></tr>";
                    }
                    if($key==count($list)-1){
                      echo "<tr><td colspan='5'>".$show."</td></tr>";
                    }
                }
                break;
              case 4:
                $confident['cat_id']=$categoryId;
                $count=$casetest->where($confident)->count();  
                $page=new page($count,10);
                $page->setConfig('theme', "%totalRow% %header% %nowPage%/%totalPage% 页%first%  %prePage% %upPage%  %linkPage%  %downPage% %nextPage% %end% "); 
                $show=$page->show();  
                $list =$casetest->where($confident)->limit($page->firstRow.','.$page->listRows)->select();
                foreach($list as $key=>$val){
                    $str=trim(strip_tags($val["description"]));
                    $str=preg_replace('/\s(?=\s)/','',$str);
                    $str=mb_substr(preg_replace('/[\n\r\t]/','',$str),0,40,"utf-8");
                    switch($this->gid){
                      case 0:
                        echo "<tr><td>".$str."..."."</td><td>...</td><td>...</td><td>...</td>
                              <td><a href='javascript:void(0)' onclick='openUpdateSets(".$val["id"].")'>
                             修改</a>&nbsp&nbsp&nbsp&nbsp<a href='javascript:void(0)' onclick=\"if(confirm('确认要删除')){del(".$val["id"].")}\">
                             删除</a></td></tr>";
                      break;
                      default:
                        echo "<tr><td>".$str."..."."</td><td>...</td><td>...</td><td>...</td>
                              <td><a href='javascript:void(0)' onclick='openUpdateSets(".$val["id"].")'>
                             修改</a></td></tr>";
                    }
                    if($key==count($list)-1){
                      echo "<tr><td colspan='5'>".$show."</td></tr>";

                    }
                }
                break;
              case 5:
                $confident['test_type']=5;
                $confident['pid']=0;
                $confident['cat_id']=$categoryId;
                $count=$test->where($confident)->count();  
                $page=new page($count,10);
                $page->setConfig('theme', "%totalRow% %header% %nowPage%/%totalPage% 页%first%  %prePage% %upPage%  %linkPage%  %downPage% %nextPage% %end% "); 
                $show=$page->show();  
                $list = $test->field("test.*,te.answer as ea")->join("inner join test_essay as te on test.id=te.test_id")->where($confident)->limit($page->firstRow.','.$page->listRows)->select();

                foreach($list as $key=>$val){
                    $str=trim(strip_tags($val["content"]));
                    $str=preg_replace('/\s(?=\s)/','',$str);
                    $str=mb_substr(preg_replace('/[\n\r\t]/','',$str),0,20,"utf-8");
                    $ea=trim(strip_tags($val["ea"]));
                    $ea=preg_replace('/\s(?=\s)/','',$ea);
                    $ea=mb_substr(preg_replace('/[\n\r\t]/','',$ea),0,20,"utf-8");
                    switch($this->gid){
                      case 0:                    
                        echo "<tr><td>...</td><td>".$str."...".
                             "</td><td>".$ea."</td><td>".strip_tags($val["point"]).
                             "</td><td><a href='javascript:void(0)' onclick='openUpdateTest(".$val["id"].")'>
                             修改</a>&nbsp&nbsp&nbsp&nbsp<a href='javascript:void(0)' onclick=\"if(confirm('确认要删除')){del(".$val["id"].")}\">
                             删除</a></td></tr>";
                      break;
                      default:
                        echo "<tr><td>...</td><td>".$str."...".
                             "</td><td>".$ea."</td><td>".strip_tags($val["point"]).
                             "</td><td><a href='javascript:void(0)' onclick='openUpdateTest(".$val["id"].")'>
                             修改</a></td></tr>";
                    }     
                    if($key==count($list)-1){
                      echo "<tr><td colspan='5'>".$show."</td></tr>";
                    }
                }                  
            }
        }else{
          $cate=M("category");
          if($this->gid==0){
           $arrCate=$cate->select();           
          }else{
            $arrCate=$cate->where('group_id = ' . $this->gid)->select();
          }
          $this->assign("arrCate",$arrCate);
          $this->display();
         }
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
          if($test_type==4){
            $pid=$this->addCasetest($descriptionOb,$_POST);
          }else{
            $pid="";
          }
          //添加题目并生成4张随机题目图片
          for($i=0;$i<count($_POST["level"]);$i++){
            $aspectArr= json_decode(str_replace("\\","",$_POST["name"][$i]),true);
            $testId=$this->addTest($testOb,$_POST,$pid,$i,$test_type);
            for($j=0;$j<count($aspectArr);$j++){
              $aspectsId=$aspectOb->field("id")->where("name='{$aspectArr[$j]}'")->find();
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
     $document_root = C('DOCUMENT_ROOT');
     $phantomjs = c('PHANTOMJS_PATH');
     $dir = 'Data/html';
     $template = "Data/template.html"; 
     $template_html = file_get_contents($template);          
     $test_type=Input::getVar($post['test_type']);
     switch($test_type){
       case 1:
       //写入数据库
         $this->addOption($post["optionA"][$i],$testId);
         $this->addOption($post["optionB"][$i],$testId);
         $this->addOption($post["optionC"][$i],$testId);
         $this->addOption($post["optionD"][$i],$testId);
         //生成图
          $optionArr=array($post["optionA"][$i],$post["optionB"][$i],$post["optionC"][$i],$post["optionD"][$i]);
         //查找正确的选项位置
           switch($post['answer'][$i]){
             case '1':
               $answer=0;
               break;
             case '2':
               $answer=1;
               break;
             case '3':
               $answer=2;
               break;
             case '4':
               $answer=3;
               break;  

         }
           //随机生成题目选项数组
           for($k=0;$k<4;$k++){
             $arrnum=array();
             $abc=$answer."";
             $arrnum[$k]=$optionArr[$answer];  
             for($f=0;$f<4;$f++){
               if($f!=$k){
                 //生成随机数，判断随机数是否和$f重复，或已经被选了
                 while(true){
                   $num=rand(0,3);
                   $n=(string) $num;
                   if($num!=$answer&&!strpos($abc,$n)){
                     $abc.=$num;
                     $arrnum[$f]=$optionArr[$num]; 
                     break;
                   }
                 }
               }
             }
             $optionAll="";
             //生成题目图片
             $abcdArr=array("1","2","3","4");
             ksort($arrnum);
             foreach($arrnum as $key=>$val){
                 $optionNum=$key+1;
                 $optionAll==''?$optionAll.=Input::getVar($post["content"][$i])."<br/>"."(".$optionNum.")".".".str_replace('<br/>','',Input::getVar($val)):$optionAll.='<br/>'."(".$optionNum.")".".".str_replace('<br/>','',Input::getVar($val));  

           }

             unlink('Data/html/' .$testId.'_'.$k.'.html');
             $new = str_replace('{REPLACE_HOLDER}',$optionAll, $template_html);
             $html_name = $dir . '/' . $testId.'_'.$k. '.html';
             file_put_contents($html_name, $new);


             $command="cd $document_root;$phantomjs rasterize.js "."Data/html/".$testId.'_'.$k.".html Storage/image480/".$testId.'_'.$k.".gif";
             exec($command);

             $data['test_id']=$testId;
             $data['image480'] = "Storage/image480/{$testId}_{$k}.gif";
             $data['answer']=$abcdArr[$k];
             $td->add($data);                
           }
           break;
      case 2:
        $this->addOption($post["optionA"][$i],$testId);
        $this->addOption($post["optionB"][$i],$testId);
        $this->addOption($post["optionC"][$i],$testId);
        $this->addOption($post["optionD"][$i],$testId);    
        //添加多选题定义随机组合
         //重新排序后添加图片得出答案
        $optionArr=array($post["optionA"][$i],$post["optionB"][$i],$post["optionC"][$i],$post["optionD"][$i]);
        $abcdArr=array("1","2","3","4");
        for($g=0;$g<4;$g++){
          $randStr="";
          $randArr=array();
          $numFirst=rand(0,3);
          $randStr.=$abcdArr[$numFirst];
          $randArr[0]=$optionArr[$numFirst];
          while(true){
            $num=rand(0,3);
            if(!strpos($randStr,$abcdArr[$num])&&$abcdArr[$num]!=$randStr[0]){
              $randStr.=$abcdArr[$num];
              array_push($randArr,$optionArr[$num]);
              if(strlen($randStr)==4){
                 break;
              }   
            }                     
          }
          $optionAll="";
          foreach($randArr as $key=>$val){
              $optionNum=$key+1;
              $optionAll==''?$optionAll.= Input::getVar($post["content"][$i])."<br/>"."(".$optionNum.")".".".str_replace('<br/>','',Input::getVar($val)):$optionAll.='<br/>'."(".$optionNum.")".".".str_replace('<br/>','',Input::getVar($val));    

          }                    
          //根据答案查找位置
          $answerStr="";
          for($q=0;$q<strlen($post['answer'][$i]);$q++){
            $answerOption=strpos($randStr,$post['answer'][$i][$q])+1;
            $answerStr==""?$answerStr.=$answerOption:$answerStr.=",".$answerOption;
          }
          $answerArr=explode(",",$answerStr);
          sort($answerArr);
          $answerFinal=implode("",$answerArr);
          //生成图片

          unlink('Data/html/' .$testId.'_'.$g.'.html');
          $new = str_replace('{REPLACE_HOLDER}',$optionAll, $template_html);
          $html_name = $dir . '/' . $testId.'_'.$g. '.html';
          file_put_contents($html_name, $new);

          $command="cd $document_root;$phantomjs rasterize.js "."Data/html/".$testId.'_'.$g.".html Storage/image480/".$testId.'_'.$g.".gif";
          exec($command);    

          $data['test_id']=$testId;
          $data['image480'] = "Storage/image480/{$testId}_{$g}.gif";
          $data['answer']=$answerFinal;
          $td->add($data);  

         }
        break;
      case 3:
        $this->addOption($post["optionA"][$i],$testId);
        $this->addOption($post["optionB"][$i],$testId);

        unlink('Data/html/' .$testId.'.html');
        $replace_content="<p>".Input::getVar($post["content"][$i])."</p><p>(1).".Input::getVar($post["optionA"][$i])."</p><p>(2).".Input::getVar($post["optionB"][$i])."</p>";
        $new = str_replace('{REPLACE_HOLDER}', $replace_content, $template_html);
        $html_name = $dir . '/' . $testId. '.html';
        file_put_contents($html_name, $new);

        $command="cd $document_root;$phantomjs rasterize.js "."Data/html/".$testId.".html Storage/image480/".$testId.".gif";
        exec($command);        

        $data['test_id']=$testId;
        $data['image480'] = "Storage/image480/{$testId}.gif";
        $data['answer']=Input::getVar($post["answer"][$i]);
        $td->add($data);   
        break;
      case 4:
         $setsType=Input::getVar($post["setsType"][$i]);
         $post['test_type']=$setsType;
         $this->addPicture($testId,$post,$i);
         break;
      case 5:
        unlink('Data/html/' .$testId.'.html');
        $replace_content="<p>".Input::getvar($post["content"][$i])."</p>";
        $new = str_replace('{REPLACE_HOLDER}', $replace_content, $template_html);
        $html_name = $dir . '/' . $testId. '.html';
        file_put_contents($html_name, $new);

        $command="cd $document_root;$phantomjs rasterize.js "."Data/html/".$testId.".html Storage/image480/".$testId.".gif";
        exec($command);      

        $data['test_id']=$testId;
        $data['image480'] = "Storage/image480/{$testId}.gif";
        $td->add($data);            
    }  
  }

    /*
    *添加试题选择项
    */
    public function addOption($option,$testId){
     $optionOb=M("test_choice");
     $data['option']=Input::getVar($option);
     $data['test_id']=$testId;
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
       $aspects=Input::getVar($_POST["aspects"]);
       $dat["answer"]=Input::getVar($_POST["eaAnswer"]);
       $data["answer"]=Input::getVar($_POST["answer"]);
       $data["point"]=Input::getVar($_POST["point"]);
       $data["content"]=Input::getVar($_POST["question"]);
       $data["level"]=Input::getVar($_POST["level"]);
       $testId=Input::getVar($_POST["id"]);
       $test_type=Input::getVar($_POST["test_type"]);
       $sets_type=Input::getVar($_POST["sets_type"]);
       $choiceA=Input::getVar($_POST["choiceA"]);
       $choiceB=Input::getVar($_POST["choiceB"]);
       $choiceC=Input::getVar($_POST["choiceC"]);
       $choiceD=Input::getVar($_POST["choiceD"]);
       if($test_type==4){
          $test_type=$sets_type;  
       }
       $test->where('id='.$testId)->save($data);
       if($test_type==5){
        $essay->where("test_id=".$testId)->save($dat);
       }
       $aspectArr=json_decode($aspects);
       @$test_aspects->where("test_id=".$testId)->delete();
       for($i=0;$i<count($aspectArr);$i++){
          $aspectsId=$aspectsOb->where("name='".$aspectArr[$i]."'")->getField("id");
          $datt['aspects_id']=$aspectsId;
          $datt['test_id']=$testId;
          $test_aspects->add($datt);   
       }
       $test_id=$test_choice->field("id")->where("test_id=".$testId)->select();
       if($test_type==1||$test_type==2){
          $test_choice->where('id='.$test_id["0"]["id"])->save(array("option"=>$choiceA));
          $test_choice->where('id='.$test_id["1"]["id"])->save(array("option"=>$choiceB));
          $test_choice->where('id='.$test_id["2"]["id"])->save(array("option"=>$choiceC));
          $test_choice->where('id='.$test_id["3"]["id"])->save(array("option"=>$choiceD));
       }else if($test_type==3){
          $test_choice->where('id='.$test_id["0"]["id"])->save(array("option"=>$choiceA));
          $test_choice->where('id='.$test_id["1"]["id"])->save(array("option"=>$choiceB));
       }
    }
    /*
      *获取题目选项
    */
    public function getTestOption(){
      $test_choice=M("test_choice");
      $option=$test_choice->field('option')->where("test_id=".$_POST['testId'])->select();
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
        for($i=0;$i<4;$i++){
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
          for($j=0;$j<4;$j++){
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