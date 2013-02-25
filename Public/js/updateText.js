 /*
  *sets_type题组子题目类型
  *test_type题目类型
  *setsId题组id
 */
 var editor;
 var origin=artDialog.open.origin;
 var test_type=art.dialog.data("type");
 var sets_type=art.dialog.data("sets_type");
 var id=art.dialog.data("id");
 var setsId=art.dialog.data("setsId");
 $(function(){
     var keditor;
     KindEditor.ready(function(K) {
         keditor=K.create('#editor_id');
         editor=keditor;
   });   
     switch(test_type){
      case "1":
          $("#answer").html("<span>答案:</span>"+
                              "<input type='radio' name='answer[]' value='A'>A"+
                              "<input type='radio' name='answer[]' value='B'>B"+
                              "<input type='radio' name='answer[]' value='C'>C"+
                              "<input type='radio' name='answer[]' value='D'>D"
                              );        
             break;
      case "2":
           $("#answer").html("<span>答案:</span>"+
                             "<input type='checkbox' name='answer[]' value='A'>A"+
                             "<input type='checkbox'name='answer[]' value='B'>B"+
                             "<input type='checkbox' name='answer[]' value='C'>C"+
                             "<input type='checkbox' name='answer[]' value='D'>D");         
           break;
      case "3":
          $("#answer").html("<span>答案:</span>"+
                             "<input type='radio' name='answer[]' value='A'>对"+
                             "<input type='radio'name='answer[]' value='B'>错");       
           break;
      case "4":
         if(sets_type==1){
          $("#answer").html("<span>答案:</span>"+
                              "<input type='radio' name='answer[]' value='A'>A"+
                              "<input type='radio' name='answer[]' value='B'>B"+
                              "<input type='radio' name='answer[]' value='C'>C"+
                              "<input type='radio' name='answer[]' value='D'>D"
                              );   
         }else if(sets_type==2){
            $("#answer").html("<span>答案:</span>"+
                  "<input type='checkbox' name='answer[]' value='A'>A"+
                  "<input type='checkbox'name='answer[]' value='B'>B"+
                  "<input type='checkbox' name='answer[]' value='C'>C"+
                  "<input type='checkbox' name='answer[]' value='D'>D");   
         }else{
            $("#answer").html("<span>答案:</span>"+
                   "<input type='radio' name='answer[]' value='A'>对"+
                   "<input type='radio'name='answer[]' value='B'>错");  

         }
   }
     //获取题目的具体信息方便修改
     $.ajax({
       url:"/Admin/Test/getTitleInfo",
       type:"post",
       data:"id="+id,
       dataType:"text",
       success:function(d){
        var titleInfo=jQuery.parseJSON(d);
        var aspects=[];
        for(var i=0;i<titleInfo["name"].length;i++){
          aspects.push(titleInfo["name"][i]["name"]);
        }
        switch(test_type){
         case "1":
           $("input[type='radio'][value='"+titleInfo["answer"]+"']").attr("checked",true);
           $("#point").val(titleInfo["point"]);
           $("#difficult").val(titleInfo["level"]);
           editor.html(titleInfo["content"]);
           break;
         case "2":
           var answer=titleInfo["answer"].split("");
           for(var i=0;i<answer.length;i++){
               $("input[type='checkbox'][value='"+answer[i]+"']").attr("checked",true);
            }
            $("#point").val(titleInfo["point"]);
            $("#difficult").val(titleInfo["level"]);
            editor.html(titleInfo["content"]);
           break;
         case "3":
           $("input[type='radio'][value='"+titleInfo["answer"]+"']").attr("checked",true);
           $("#point").val(titleInfo["point"]);
           $("#difficult").val(titleInfo["level"]);
           editor.html(titleInfo["content"]);
           break;
         case "4":
           if(sets_type==1){
              $("input[type='radio'][value='"+titleInfo["answer"]+"']").attr("checked",true);
              $("#point").val(titleInfo["point"]);
              $("#difficult").val(titleInfo["level"]);
              editor.html(titleInfo["content"]);
           }else if(sets_type==2){
              var answer=titleInfo["answer"].split("");
              for(var i=0;i<answer.length;i++){
                  $("input[type='checkbox'][value='"+answer[i]+"']").attr("checked",true);
               }
               $("#point").val(titleInfo["point"]);
               $("#difficult").val(titleInfo["level"]);
               editor.html(titleInfo["content"]);
           }else{
               $("input[type='radio'][value='"+titleInfo["answer"]+"']").attr("checked",true);
               $("#point").val(titleInfo["point"]);
               $("#difficult").val(titleInfo["level"]);
               editor.html(titleInfo["content"]);
           }
           break;
        } 
        $('#aspects').textext({
           plugins:'tags prompt focus autocomplete ajax arrow',
           tagsItems:aspects,
           prompt:'输入知识点...',
           html: {
             hidden: '<input type="hidden" id="aspects_name" />'
           },
           ajax : {
             url :'/Data/aspects/1.json?' + parseInt(Math.random()*100000000),
             dataType : 'json',
             cacheResults :false
           }
         });
       }
     })             
 })
  //判断题目类型和输出题目具体信息
 function sub(){
 	var aspects=document.getElementById('aspects_name').value;
     var answerMulti=$("input[name='answer[]'][type='checkbox']:checked");
     var answerSingle=$("input[name='answer[]'][type='radio']:checked").val();
     if(answerMulti!=""){
       var multiAnswer="";
         for(var i=0;i<answerMulti.length;i++){
             multiAnswer+=answerMulti[i].value;   
             }
     }
 	var answer=answerSingle?answerSingle:multiAnswer;
 	var point=document.getElementById('point').value;
 	var question=editor.html();
     var level=$("#difficult").val();
     $.ajax({
       url:"/Admin/Test/updateTest",
       type:"post",
       data:"aspects="+aspects+"&answer="+answer+"&point="+point+"&question="+question+"&level="+level+"&id="+id,
       dataType:"text",
       success:function(d){
          if(test_type!=4){
            testInfo();
          }else{
            setsTitleInfo();
          }
       }    
     })
 		}
 //获得试题信息
 function testInfo(){
    var p=origin.$("#page").val()?origin.$("#page").val():1;
    alert(p);
    origin.$("#test tr:gt(0)").empty();
    $.ajax({
        url:"/Admin/Test/index",
        type:"get",
        data:"type="+test_type+"&p="+p,
        dataType:"text",
        success:function(d){
         origin.$("#test").append(d);
         art.dialog.close();
        }
    })
 }
function setsTitleInfo(){
  var singleTitleOb=origin.$("#singleTitle");
  singleTitleOb.empty();
  $.ajax({
       url:"/Admin/Test/getTitleSetsInfo",
       type:"post",
       data:"id="+setsId,
       dataType:"text",
       success:function(d){
         var titleInfo=jQuery.parseJSON(d);
         var aspects="";
         for(var i=1;i<titleInfo.length;i++){
           var testId=titleInfo[i]["id"];
           var content=titleInfo[i]["content"];
           var point=titleInfo[i]["point"];
           var answer=titleInfo[i]["answer"];
           var level=titleInfo[i]["level"];
           var sets_type=titleInfo[i]["test_type"];
           for(var j=0;j<titleInfo[i]["name"].length;j++){
             aspects+=aspects?","+titleInfo[i]["name"][j]["name"]:titleInfo[i]["name"][j]["name"];
           }
           singleTitleOb.append("<div class='rule_done'><p>问题:"+content+
                               "</p><p>知识点:"+aspects+
                               "</p><span class='detailSplit'>分数:"+point+
                               "</span><span class='detailSplit'>答案:"+answer+
                               "</span><span class='detailSplit'>难度:"+level+
                               "</span><span><a href='javascript:void(0)' onclick='openUpdateTest("+testId+","+setsId+","+sets_type+")'>修改</a></span>"+
                               "</div>");
             aspects="";
         }
         art.dialog.close();
       }
     })
}