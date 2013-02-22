<?php
import('ORG.Util.Input');
import("ORG.Page.Page");
class TestAction extends Action
{
    public function _initialize()
    {
        if(!Session::is_set('manager')) {
            $this->redirect('Auth/login');
        }
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
    *分类获取题目信息内容
    */
     public function index()
    { 
        $test=M("test");
        $casetest=M("casetest");
        if($this->isAjax()){
            $type=Input::getVar($_GET["type"]);
            switch($type){
              case 1:
                $count=$test->where("test_type=1")->count();  
                $page=new Page($count,1);  
                $page->setConfig('theme', "%totalRow% %header% %nowPage%/%totalPage% 页 %upPage%  %first%  %prePage%  %linkPage%  %nextPage% %downPage% %end%");
                $show=$page->show();  
                $list = $test->where("test_type=1")->limit($page->firstRow.','.$page->listRows)->select();
                foreach($list as $key=>$val){
                    echo "<tr><td>...</td><td>".mb_substr($val["content"],0,40,"utf-8")."...".
                         "</td><td>".$val["answer"]."</td><td>".$val["point"].
                         "</td><td><a href='javascript:void(0)' onclick='openUpdateTest(".$val["id"].")'>
                         修改</a>&nbsp&nbsp&nbsp&nbsp<a href='javascript:void(0)' onclick='del(".$val["id"].")'>
                         删除</a></td></tr>";
                    if($key==count($list)-1){
                      echo "<tr><td colspan='5'>".$show."</td></tr>";

                    }
                }
                break;
              case 2:
                $count=$test->where("test_type=2")->count();  
                $page=new page($count,1);
                $page->setConfig('theme', "%totalRow% %header% %nowPage%/%totalPage% 页 %upPage%  %first%  %prePage%  %linkPage%  %nextPage% %downPage% %end%");  
                $show=$page->show();  
                $list = $test->where("test_type=2")->limit($page->firstRow.','.$page->listRows)->select();
                foreach($list as $key=>$val){
                    echo "<tr><td>...</td><td>".mb_substr($val["content"],0,40,"utf-8")."...".
                         "</td><td>".$val["answer"]."</td><td>".$val["point"].
                         "</td><td><a href='javascript:void(0)' onclick='openUpdateTest(".$val["id"].")'>
                         修改</a>&nbsp&nbsp&nbsp&nbsp<a href='javascript:void(0)' onclick='del(".$val["id"].")'>
                         删除</a></td></tr>";
                    if($key==count($list)-1){
                      echo "<tr><td colspan='5'>".$show."</td></tr>";

                    }

                }
                break;
              case 3:
                $count=$test->where("test_type=3")->count();  
                $page=new page($count,1);
                $page->setConfig('theme', "%totalRow% %header% %nowPage%/%totalPage% 页 %upPage%  %first%  %prePage%  %linkPage%  %nextPage% %downPage% %end%");  
                $show=$page->show();  
                $list = $test->where("test_type=3")->limit($page->firstRow.','.$page->listRows)->select();
                foreach($list as $key=>$val){
                    echo "<tr><td>...</td><td>".mb_substr($val["content"],0,40,"utf-8")."...".
                         "</td><td>".$val["answer"]."</td><td>".$val["point"].
                         "</td><td><a href='javascript:void(0)' onclick='openUpdateTest(".$val["id"].")'>
                         修改</a>&nbsp&nbsp&nbsp&nbsp<a href='javascript:void(0)' onclick='del(".$val["id"].")'>
                         删除</a></td></tr>";
                    if($key==count($list)-1){
                      echo "<tr><td colspan='5'>".$show."</td></tr>";
                    }
                }
                break;
              case 4:
                $count=$casetest->count();  
                $page=new page($count,1);
                $page->setConfig('theme', "%totalRow% %header% %nowPage%/%totalPage% 页 %upPage%  %first%  %prePage%  %linkPage%  %nextPage% %downPage% %end%");  
                $show=$page->show();  
                $list =$casetest->limit($page->firstRow.','.$page->listRows)->select();
                foreach($list as $key=>$val){
                    echo "<tr><td>".mb_substr($val["description"],0,40,"utf-8")."..."."</td><td>...</td><td>...</td><td>...</td>
                          <td><a href='javascript:void(0)' onclick='openUpdateSets(".$val["id"].")'>
                         修改</a>&nbsp&nbsp&nbsp&nbsp<a href='javascript:void(0)' onclick='del(".$val["id"].")'>
                         删除</a></td></tr>";
                    if($key==count($list)-1){
                      echo "<tr><td colspan='5'>".$show."</td></tr>";

                    }
                }
            }
        }else{
          $this->display();
         }
    }
    /*
     *对题目分类添加
    */
    public function add()
    {
       if($this->isPost()){
          $descriptionOb=M("casetest");
          $testOb=M("test");
          $aspectOb=M("aspects");
          $test_aspectsOb=M("test_aspects");
          $test_type=Input::getVar($_POST['test_type']);
          if($test_type==4){
            $pid=$this->addCasetest($descriptionOb,$_POST);
          }else{
            $pid="";
          }
          for($i=0;$i<count($_POST["content"]);$i++){
            $aspectArr= json_decode(str_replace("\\","",$_POST["name"][$i]),true);
            $testId=$this->addTest($testOb,$_POST,$pid,$i,$test_type);
            for($j=0;$j<count($aspectArr);$j++){
              $aspectsId=$aspectOb->field("id")->where("name='{$aspectArr[$j]}'")->find();
              $this->addTestAspect($test_aspectsOb,$_POST,$testId,$aspectsId["id"]);
            }     
          } 
            $this->redirect('Test/index/test_type/'.$test_type);    
        }else{
             $cate=M("category");
             $arrCate=$cate->select();
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
          $data["pid"]=$pid;
          $data['cat_id']=Input::getVar($post["category"]);
          $data["content"]=Input::getVar($post["content"][$i]);
          $data["level"]=Input::getVar($post["level"][$i]);
          $data["answer"]=Input::getVar($post["answer"][$i]);
          if($test_type!=4){
            $data["test_type"]=$test_type;
          }else{
             $data["test_type"]=strlen($data["answer"])>1?"2":"1";
          }
          $data["point"]=Input::getVar($post["point"][$i]);
          $data["author"]=$_SESSION['username'];
          $data["date"]=time();
          if($model->add($data)){
            return mysql_insert_id();
          }
      }else{
          echo "表单令牌错误";
      }

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
        $id=Input::getVar($_POST["id"]);
      $aspectsId=$test_aspects->field("aspects_id")->where("test_id=".$id)->select();
      $aspectsName=array();
      for($i=0;$i<count($aspectsId);$i++){
        $aspectsName[]=$aspects->field("name")->where("id=".$aspectsId[$i]["aspects_id"])->find();
      }
      $titleInfo=$test->where("id=".$id)->find();
      $titleInfo["name"]=$aspectsName;
        echo json_encode($titleInfo);
    }
    /*
    *修改题目
    */
    public function updateTest(){
       $test=M("test");
       $test_aspects=M("test_aspects");
       $aspectsOb=M("aspects");
       $aspects=Input::getVar($_POST["aspects"]);
       $data["answer"]=Input::getVar($_POST["answer"]);
       $data["point"]=Input::getVar($_POST["point"]);
       $data["content"]=Input::getVar($_POST["question"]);
       $data["level"]=Input::getVar($_POST["level"]);
       $testId=Input::getVar($_POST["id"]);
       $test->where('id='.$testId)->save($data);
       $aspectArr=explode(",",str_replace("\"","",str_replace("]","",str_replace("[","",$aspects))));
       @$test_aspects->where("test_id=".$testId)->delete();
       for($i=0;$i<count($aspectArr);$i++){
          $aspectsId=$aspectsOb->where("name='".$aspectArr[$i]."'")->getField("id");
          $datt['aspects_id']=$aspectsId;
          $datt['test_id']=$testId;
          $test_aspects->add($datt);   
       }
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
      if($type!=4){
        if($test->where("id=".$id)->delete()&&$test_aspects->where("test_id=".$id)->delete()){
          echo true;
        }else{
          echo false;
        }
      }else{  
        $testId=$test->field("id")->where("pid=".$id)->select();
        for($i=0;$i<count($testId);$i++){
          $test_aspects->where("test_id=".$testId[$i]["id"])->delete();
        }
        if($casetest->where("id=".$id)->delete()&&$test->where("pid=".$id)->delete()){
          echo true;  
        }else{
          echo false;
        }

      }
    }
        
}
