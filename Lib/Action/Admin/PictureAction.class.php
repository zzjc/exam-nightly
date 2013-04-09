<?php
	require 'Common/Json.php';
	import('ORG.Util.Input');
	class PictureAction extends Action{
	    public function update_html(){
	        header("content-type:text/html;charset=utf8");
	        $testId=Input::getVar($_POST["testId"]);
	        $test_type=Input::getVar($_POST["test_type"]);
	        $sets_type=Input::getVar($_POST["sets_type"]);
	        if($test_type==4){
	        	$test_type=$sets_type;
	        }
	        $choiceA=Input::getVar($_POST["choiceA"]);
	        $choiceB=Input::getVar($_POST["choiceB"]);
	        $choiceC=Input::getVar($_POST["choiceC"]);
	        $choiceD=Input::getVar($_POST["choiceD"]);
	        $answer=Input::getVar($_POST["answer"]);	       
	        //重新生成html
	        $dir = 'Data/html';
	        $template = "Data/template.html"; 
	        $template_html = file_get_contents($template);
	        $exam_test = M('test');
	        $testDevice=M("test_device");
	        $content=$exam_test->where("id=".$testId)->field('content')->find();
	        $device_id=$testDevice->where("test_id=".$testId)->field("id")->select();
	        if($content){
	        	$optionArr=array($choiceA,$choiceB,$choiceC,$choiceD);
	        	$abcdArr=array("A","B","C","D");
	        	switch ($test_type){
 					case "1":
			           switch($answer){
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
			           //思路没到的话,先随机插入，到了$k的话插入$k对应答案,随后继续插入，到了4个就结束
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
			                 $optionAll==''?$optionAll.=$content["content"]."<br/>".$optionNum.".".str_replace('<br/>','',$val):$optionAll.='<br/>'.$optionNum.".".str_replace('<br/>','',$val);  			

			          	 }
			             $dir = 'Data/html';
			             $template = "Data/template.html"; 
			             $template_html = file_get_contents($template);
			             unlink('Data/html/' .$testId.'_'.$k.'.html');
			             $new = str_replace('{REPLACE_HOLDER}',$optionAll, $template_html);
			             $html_name = $dir . '/' . $testId.'_'.$k. '.html';
			             file_put_contents($html_name, $new);
			             //生成图片
			             $document_root = C('DOCUMENT_ROOT');
			             $phantomjs = C('PHANTOMJS_PATH');
			             $command ="cd $document_root;$phantomjs rasterize.js "."Data/html/".$testId.'_'.$k.".html Storage/image480/".$testId.'_'.$k.".gif";
			             exec($command); 			             

			             $data['test_id']=$testId;
			             $data['image480'] = "Storage/image480/{$testId}_{$k}.gif";
			             $data['answer']=$abcdArr[$k]; 
			             $testDevice->where("id=".$device_id[$k]["id"])->save($data);
		        	  }
		        	  break;
		        	case "2":
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
						        $optionAll==''?$optionAll.= $content["content"]."<br/>".$optionNum.".".str_replace('<br/>','',$val):$optionAll.='<br/>'.$optionNum.".".str_replace('<br/>','',$val);    							           }                    
						    //根据答案查找位置
						    $answerStr="";
						    for($q=0;$q<strlen($answer);$q++){
						      $answerOption=strpos($randStr,$answer[$q])+1;
						      $answerStr==""?$answerStr.=$answerOption:$answerStr.=",".$answerOption;
						    }
						    $answerArr=explode(",",$answerStr);
						    sort($answerArr);
						    $answerFinal=implode("",$answerArr);
						    $dir = 'Data/html';
						    $template = "Data/template.html"; 
						    $template_html = file_get_contents($template);
						    unlink('Data/html/' .$testId.'_'.$g.'.html');
						    $new = str_replace('{REPLACE_HOLDER}',$optionAll, $template_html);
						    $html_name = $dir . '/' . $testId.'_'.$g. '.html';
						    file_put_contents($html_name, $new);
						     //生成图片
	    			        $document_root = C('DOCUMENT_ROOT');
	    			        $phantomjs = C('PHANTOMJS_PATH');
	    			        $command ="cd $document_root;$phantomjs rasterize.js "."Data/html/".$testId.'_'.$g.".html Storage/image480/".$testId.'_'.$g.".gif";
	           				exec($command);	

						    $data['image480'] = "Storage/image480/{$testId}_{$g}.gif";
						    $data['answer']=$answerFinal;
						    $testDevice->where("id=".$device_id[$g]["id"])->save($data);
						}
						break;
					default:
    			        $dir = 'Data/html';
    			        $template = "Data/template.html"; 
    			        $template_html = file_get_contents($template);
    			        unlink('Data/html/' .$testId.'.html');
    			        $replace_content="<p>".$content["content"]."</p><p>1.".$choiceA."</p><p>2.".$choiceB."</p";
    			        $new = str_replace('{REPLACE_HOLDER}', $replace_content, $template_html);
    			        $html_name = $dir . '/' . $testId. '.html';
    			        file_put_contents($html_name, $new);
    			          //生成图片
    			        $document_root = C('DOCUMENT_ROOT');
    			        $phantomjs = C('PHANTOMJS_PATH');
    			        $command ="cd $document_root;$phantomjs rasterize.js "."Data/html/".$testId.".html Storage/image480/".$testId.".gif";
           				exec($command);   
					            				
    			        $data['image480'] = "Storage/image480/{$testId}.gif";
    			        $data['answer']=Input::getVar($answer);
    			        $testDevice->where("test_id=".$testId)->save($data);									        		
		        }
		    }else{
		         echo '修改失败';  
		     }
	    }
	}