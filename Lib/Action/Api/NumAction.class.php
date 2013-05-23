<?php
	import('ORG.Util.Input');
	class NumAction extends Action{
		//获取数量
		public function num(){
			header("Content-type: text/html; charset=utf-8");
			$num=$_GET["type"];
			//查找知识点表的id和知识点
			$sql="SELECT count( id ) as num,name
					FROM (					
					SELECT test.id,test.test_type as type,test.cat_id, test.content, a.id AS aspectId, a.name AS name
					FROM test
					INNER JOIN test_aspects AS ta ON test.id = ta.test_id
					INNER JOIN aspects a ON ta.aspects_id = a.id
					WHERE test.cat_id =".$num."
					) AS ttt group by name";
/*			echo $sql;*/
			$m=M();
			$tests=$m->query($sql);
/*			echo "<pre>";
				print_r($tests);
			echo "</pre>";*/

			$num=0;
			echo "<table border:1px solid red>";
			foreach($tests as $k=>$v){
				echo "<tr>";
					echo "<td>知识点:".$v["name"]."</td>";
					echo "<td>数量:".$v["num"]."</td>";
				echo "</tr>";
				$num+=$v["num"];
			}
			echo "</table>";
			echo "总题目数:".$num;

		}
		//检验test_aspects
		public function check(){
			header("Content-type: text/html; charset=utf-8");
			$test=M();
			$sql0="select id from category";
			$cateId=$test->query($sql0);
			foreach($cateId as $catK=>$catV){
				$sql="select id from test where cat_id=".$catV["id"];
				$tests=$test->query($sql);	

				foreach($tests as $key=>$val){
					//查找相关知识点表的知识点id
					$sql2="select aspects_id from test_aspects where test_id=".$val["id"];
					$qq=$test->query($sql2);	
	
	
					//查找知识点表的知识点名称
					$sql3="select name from aspects where id=".$qq[0]["aspects_id"];
					$kk=$test->query($sql3);
					//查找知识点表的id
					$sql4="select id from aspects where name='".$kk[0]["name"]."' and cat_id=".$catV["id"];
					$tt=$test->query($sql4);	
					//修改test_aspets的知识关联id
					$sql5="update test_aspects set aspects_id=".$tt[0]["id"]." where test_id=".$val["id"];
					$test->query($sql5);
				}
			}

		}
		//查找题目与知识点点不匹配的题目id
		public function kk(){
			header("Content-type: text/html; charset=utf-8");
			$m=M();
			$sql="SELECT test.id,ta.aspects_id as asId
					FROM test
					INNER JOIN test_aspects AS ta ON test.id = ta.test_id
					WHERE test.cat_id =5 group by id" ;
			$test=$m->query($sql);

			$num=0;
			foreach($test as $key=>$val){
				$sql="select * from aspects where id=".$val["asId"];
				$bb=$m->query($sql);
				if(!$bb[0]["id"]){
					$num++;
					echo $val["id"]."<br/>";
				}
			}
			echo "数量为:".$num;
		}

	}
?>
