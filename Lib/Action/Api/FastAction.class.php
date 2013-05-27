<?php
	import('ORG.Util.Input');
	class FastAction extends Action{
		public function picture(){
			//生成除了材料分析题之外的所有题目
			$testModel=M();
			$choice=M("test_choice");
			$td=M("test_device");
			$type=Input::getVar($_GET["type"]);
			$html_name = $dir . '/' . $testId . '.html';  
			$dir = 'Data/html';
 			$template = "Data/template.html";
			$template_html = file_get_contents($template);	  
 			$sql="select content,id from test where test_type=".$type." and pid=0 order by id asc"; 
 			$tests=$testModel->query($sql);
			foreach($tests as $key=>$val){
				
				$tdArr["test_id"]=$val["id"];
				$tdArr["image480"]="Storage/image480/".$val['id'].".gif";
				$td->add($tdArr);

				$hn=str_replace('{REPLACE_HOLDER}',input::getVar($val["content"]), $template_html);
				$html_name = $dir . '/' .$val['id'] . '.html'; 
				file_put_contents($html_name,$hn);
				$url="phantomjs.exe rasterize.js "."Data/html/".$val['id'].".html Storage/image480/".$val['id'].".gif";
				exec($url);
				if($type!=5){
					$sql_option="select * from test_choice where test_id=".$val['id']." order by id asc";
					$option=$testModel->query($sql_option);
					foreach($option as $key2=>$val2){
						$data["id"]=$val2['id'];
						$data['image480']="Storage/image480/".$val['id']."_".$key2.".gif";
						$choice->save($data);
						$hop=str_replace('{REPLACE_HOLDER}',input::getVar($val2["option"]), $template_html);
						$html_opname = $dir . '/' .$val['id'] . '_'.$key2.'.html';
						file_put_contents($html_opname,$hop);
						$url_op="phantomjs.exe rasterize.js "."Data/html/".$val['id']."_".$key2.".html Storage/image480/".$val['id']."_".$key2.".gif";
						exec($url_op);
					}
				}
			}
		}
	}

?>