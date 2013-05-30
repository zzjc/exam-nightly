<?php
	require 'Common/Json.php';
	import('ORG.Util.Input');
	class PictureAction extends Action{
		/*
		    *修改题目时重新生成图片
		*/
		private $picUrl;
	    public function update_html(){
	        header("content-type:text/html;charset=utf8");
	        $this->picUrl="cd ".C('DOCUMENT_ROOT').";".c('PHANTOMJS_PATH')." rasterize.js ";
	        $testId=Input::getVar($_POST["testId"]);
	        $test_type=Input::getVar($_POST["test_type"]);
	        $sets_type=Input::getVar($_POST["sets_type"]);
	        $option=explode(",",$_POST["option"]);
			$dir = 'Data/html';
			$template = "Data/template.html"; 
			$template_html = file_get_contents($template);	        
	        if($test_type==4){
	        	$test_type=$sets_type;
	        }      
	        $answer=Input::getVar($_POST["answer"]);
	        $eaAnswer=Input::getVar($_POST["eaAnswer"]);	
	        $html_name = $dir . '/' . $testId . '.html';       
	        //重新生成html
	        $exam_test = M('test');
	        $content=input::getVar($_POST["question"]);
			$new = str_replace('{REPLACE_HOLDER}',$content, $template_html);
	        if($content){
	        	switch ($test_type){
    			    case "5":
    			        $replace_content="<p>".$content."</p>";
    			        $new = str_replace('{REPLACE_HOLDER}', $replace_content, $template_html);
    			        $html_name = $dir . '/' . $testId. '.html';
    			        file_put_contents($html_name, $new);
    			          //生成图片			            				
				        $url=$this->picUrl."Data/html/".$testId.".html Storage/image480/".$testId.".gif";
				        exec($url);			            			            			    	
    			        break;
 					default:
			             file_put_contents($html_name, $new);
			         	 $url=$this->picUrl."Data/html/".$testId.".html Storage/image480/".$testId.".gif";
			             exec($url);
			             for($i=0;$i<10;$i++){
			          		unlink('Storage/image480/'.$testId."_".$i.".gif");
			             }
				         for($j=0;$j<count($option);$j++){
				             $hn=str_replace('{REPLACE_HOLDER}',input::getVar($option[$j]), $template_html);
				             $html_num = $dir . '/' . $testId. '_'.$j.'.html';
				             file_put_contents($html_num,$hn);
				             $url=$this->picUrl."Data/html/".$testId."_{$j}.html Storage/image480/".$testId."_{$j}.gif";
				             exec($url);
				         }
		        	  break;    			        
		        }
		    }else{
		         echo '修改失败';  
		     }
	    }
	}