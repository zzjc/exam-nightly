   function sessction(){
    var arrStr=document.cookie.split(";");
    for(var i=0;i<arrStr.length;i++){
      var temp="";
      temp=arrStr[i].split("=");
      if(/test_type/.test(temp[0])){
       var typeCookie=temp[1];
        break;
      };
    }
    if(typeCookie){
      $("input[name='test_type'][value='"+typeCookie+"'][type='radio']").get(0).checked=true;
    }
   }
   /*
   *判断题目类型
   */
   function type(){
       $("#addText").css("display","none");
       $("#resetText").css("display","none");
       var test_type=$("input[name='test_type'][type='radio']:checked").val();
       $("#titleSets").hide();
       $("#titleSingle").show();    
       $("#titleDetail").empty();
       switch(test_type)
   　　{
  　　   case "1":
   　　     $("#titleSingle").val("添加单选题");
            $("#description").hide();
            break;
         case "2":
            $("#titleSingle").val("添加多选题");
            $("#description").hide();
            break;
          case "3":
            $("#titleSingle").val("添加判断题");
            $("#description").hide();
            break;
          case "4":
            $("#description").show();
            $("#titleSets").show();
            $("#titleSingle").hide();    
　　   }
       $.ajax({
           url:"/Admin/Test/ses_type",
           type:"post",
           data:"test_type="+test_type,
           dataType:"text",
           success:function(d){
               
           }
       })    
     }
    $(function(){
      sessction();
      type();
      $("#name").bind("change",type);
      $("[name='test_type']").bind("change",type);
      $("#titleSets").bind("click",selectSetsType);
      $("#titleSingle").bind("click",openTitleSingleDel);
    })  
  var editor;
  KindEditor.ready(function(K) {
          editor=K.create('#editor_id');
  });
 //打开材料分析题的子题
 function selectSetsType(){
    art.dialog({
               title:'选择题目类型',
               follow: document.getElementById('titleSets'),
               content: "<input type='radio' value='1' name='setsType[]'/>单选题<br /><br />" +
                       "<input type='radio' value='2' name='setsType[]' />多选题<br /><br />"+
                       "<input type='radio' value='3' name='setsType[]'>判断题",
               ok: function () {             
                   var setsType=$("input[name='setsType[]']:checked").val();
                   switch(setsType){
                      case "1":
                        art.dialog.data("setsType",setsType);
                        art.dialog.open("/Tpl/default/Admin/Test/artdialogTitle.html",{fixed: true,id:'memdiv',width:500,height:460,title:'添加单选题'},false);
                      break;
                      case "2":
                        art.dialog.data("setsType",setsType);
                        art.dialog.open("/Tpl/default/Admin/Test/artdialogTitle.html",{fixed: true,id:'memdiv',width:500,height:460,title:'添加多选题'},false);
                        break;
                      case "3":
                        art.dialog.data("setsType",setsType);
                        art.dialog.open("/Tpl/default/Admin/Test/artdialogTitle.html",{fixed: true,id:'memdiv',width:500,height:460,title:'添加判断题'},false);
                        break;
                   }
               }
           });
  }
  //打开材料分析题
 function openTitleSetsDel(){
  art.dialog.open("/Tpl/default/Admin/Test/artdialogTitle.html",{fixed: true,id:'memdiv',width:500,height:480,title:'添加题目'}, false);
}
//打开单选；多选；判断
function openTitleSingleDel(){
  art.dialog.open("/Tpl/default/Admin/Test/artdialogTitle.html",{fixed: true,id:'memdiv',width:500,height:480,title:'添加题目'},false);
}
