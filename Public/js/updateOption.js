 var optionArr=art.dialog.data("option");
 var test_type=art.dialog.data("test_type");
 var origin=artDialog.open.origin;
 var orOption=origin.$("[name='option[]']");
 var orOplen=origin.$("[name='option[]']").length;
 var area="";
 var num=0;
 for(var i=0;i<orOplen;i++){
  num=i+1;
  area+="<div class='ops'><span class='choice'>"+num+".</span><textarea name='option[]' class='test_box'>"+orOption[i].value+"</textarea><a href='javascript:void(0)' onclick='delOption("+i+")' class='del'>-删除</a></div>";
 }
 $(function(){       
  $("#option").append(area);   
 })
 /*
  *添加选项
*/
  function addOption()
  {
   var num;
   var pnum;
   var last=$("#option .choice:last").html();
   if(!last){
    num=0;
   }else{
    num=last.match(/\d+/);
   }
   pnum=num;
   num++;
   $("#option").append("<div class='ops'>"+
                        "<span class='choice'>"+num+".</span><textarea  name='option[]' class='test_box' ></textarea><a href='javascript:void(0)' onclick='delOption("+pnum+")' class='del'>删除</a>"+
                       "</div>");
 }
  /*
  *删除选项
 */
 function delOption(c){
    $(".del").each(function(i){
      if(i==c){
        $(this).parent("div").remove();

        $(".choice").each(function(i){
          i++;
          $(this).text(i+".");
        })

        $(".ops a").each(function(i){
          $(this).attr("onclick","delOption("+i+")");

        })
      }

    })
 }
 function updateOption(){
  if(return_val()){
    var orRule="<span>选项:</span><br><div class='rule_done'>";
    var option=$("[name='option[]']");
    var oplen=option.length;
    var hidOpt="";
    origin.$(".rule_done").empty();
    origin.$("#option").empty();
    num=0;
    for(var j=0;j<oplen;j++){
      num=j+1;
      orRule+="<p>"+num+"."+option[j].value+"</p>";
      hidOpt+="<input type='hidden' name='option[]' value='"+option[j].value+"'>";
      if(num>=oplen){
        orRule+="</div><a href='javascript:void(0)' onclick='openUpdateChoice()'>修改</a>";
      }

    }
    str=orRule+hidOpt;
    origin.$("#option").html(orRule+hidOpt);
    origin.createAns();
    art.dialog.close();
  }else{
    alert("请填写完整选项");
  }
 }
 /*
    *验证选项是否为空
 */
 function return_val(){
   var len=$("[name='option[]']").length;
   for(var j=0;j<len;j++){
     if($("[name='option[]']")[j].value==""){     
       return false;
       break;     
     }else if(j+1>=len){
       return true;
     }
   }
 }