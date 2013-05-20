 var test_type=art.dialog.data("test_type");
 var setsType=art.dialog.data("setsType");
 if(setsType){
    test_type=setsType;
 }
 var origin=artDialog.open.origin;
 var orLen=origin.$("[name='option[]']").length;

 $(function(){
  for(var i=0;i<orLen;i++){
    if(i==0){
       $("[name='option[]']")[0].value=origin.$("[name='option[]']")[0].value;
       $("[name='option[]']")[0].focus();
    }else{
        local=i+1;
       $("#option").append("<div class='ops'><span class='choice'>"+local+".</span><textarea  name='option[]' class='test_box' >"+origin.$("[name='option[]']")[i].value+"</textarea><a href='javascript:void(0)' onclick='delOption("+i+")' class='del' >删除</a></div>");
    }
  }
  $(".addOption").click(function(){
    $("[name='option[]']:last").focus();


  })
  $(".btn").blur(function(){
      $("[name='option[]']:first")[0].focus();
  }) 
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

/*
  *确定添加或确定修改
*/
 function addAll(){
     var len=$("[name='option[]']").length;
     var local="";
      if(return_val()){
        origin.$(".rule_done").empty();
        origin.$("#option").empty();
        for(var i=0;i<len;i++){
          local=i+1;
          origin.$(".rule_done").append("<p><span class='detailSplit'>"+local+"."+$("[name='option[]']")[i].value+"</span></p>");     
          origin.$("#option").append("<input name='option[]' type='hidden' value='"+$("[name='option[]']")[i].value+"'>");
          if(i+1>=len){
            origin.$(".rule_done").append("<div id='update_option'><a href='javascript:void(0)' onclick='openUpdateOption()'>修改选项</a></div>");
          }
        }
        origin.$("#update_option a").focus();
        origin.$(".addOption").hide();
        origin.createAns();
        art.dialog.close();        

      }else{
        alert("选项内容不能为空");
      };
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

