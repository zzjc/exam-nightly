 /*
  *sets_type材料分析题子题目类型
  *test_type单选；多选；判读题目类型
  *setsId材料分析题id
 */
 var editor;
 var optionArr;
 var origin=artDialog.open.origin;
 var test_type=art.dialog.data("type");
 var sets_type=art.dialog.data("sets_type");
 var id=art.dialog.data("id");
 var setsId=art.dialog.data("setsId");
 var categoryId=art.dialog.data("categoryId");
 $(function(){
     $(".essay_answer").hide();
     var keditor;
     KindEditor.ready(function(K) {
         keditor=K.create('#editor_id');
         editor=keditor;
   });
  $.ajax({
       url:"/Admin/Test/getTestOption",
       type:"post",
       data:"testId="+id,
       dataType:"text",
       success:function(d){
         optionArr=jQuery.parseJSON(d);
         switch(test_type){
          case "1":
              $("#option").html(
                                "<div class='option'>"+
                                "<span>选项:</span><br/><div class='rule_done'><p>1."+optionArr[0]['option']+
                                  "</p><p>2."+optionArr[1]['option']+
                                  "</p><span class='detailSplit'>3."+optionArr[2]['option']+
                                  "</span><p><span class='detailSplit'>4."+optionArr[3]['option']+
                                  "</div>"+"</p></span><span><a href='javascript:void(0)' onclick='openUpdateChoice("+d+','+test_type+")'>修改</a></span>"+
                                "</div>"+
                                "<input type='hidden' name='optionA[]' value='"+optionArr[0]['option']+"'>"+
                                "<input type='hidden' name='optionB[]' value='"+optionArr[1]['option']+"'>"+
                                "<input type='hidden' name='optionC[]' value='"+optionArr[2]['option']+"'>"+
                                "<input type='hidden' name='optionD[]' value='"+optionArr[3]['option']+"'>"
                                );
              $("#answer").html("<span>答案:</span>"+
                                  "<input type='radio' name='answer[]' value='1'>1&nbsp&nbsp&nbsp"+
                                  "<input type='radio' name='answer[]' value='2'>2&nbsp&nbsp&nbsp"+
                                  "<input type='radio' name='answer[]' value='3'>3&nbsp&nbsp&nbsp"+
                                  "<input type='radio' name='answer[]' value='4'>4"
                                  );        
                 break;
          case "2":
              $("#option").html(
                                "<div class='option'>"+
                                "<span>选项:</span><br/><div class='rule_done'><p>1."+optionArr[0]['option']+
                                  "</p><p>2."+optionArr[1]['option']+
                                  "</p><span class='detailSplit'>3."+optionArr[2]['option']+
                                  "</span><p><span class='detailSplit'>4."+optionArr[3]['option']+                          
                                  "</div>"+"</p></span><span><a href='javascript:void(0)' onclick='openUpdateChoice("+d+','+test_type+")'>修改</a></span>"+
                                "</div>"+           
                                "<input type='hidden' readonly name='optionA[]' value='"+optionArr[0]['option']+"'>"+
                                "<input type='hidden' readonly name='optionB[]' value='"+optionArr[1]['option']+"'>"+
                                "<input type='hidden' readonly name='optionC[]' value='"+optionArr[2]['option']+"'>"+
                                "<input type='hidden' readonly name='optionD[]' value='"+optionArr[3]['option']+"'>"
                                );
               $("#answer").html("<span>答案:</span>"+
                                 "<input type='checkbox' name='answer[]' value='1'>1&nbsp&nbsp&nbsp"+
                                 "<input type='checkbox'name='answer[]' value='2'>2&nbsp&nbsp&nbsp"+
                                 "<input type='checkbox' name='answer[]' value='3'>3&nbsp&nbsp&nbsp"+
                                 "<input type='checkbox' name='answer[]' value='4'>4");         
               break;
          case "3":
              $("#option").html(
                                "<div class='option'>"+
                                  "<span>选项:</span><br/><div class='rule_done'><p>1."+optionArr[0]['option']+
                                    "</p><p>2."+optionArr[1]['option']+   
                                  "</div>"+"</p></span><span><a href='javascript:void(0)' onclick='openUpdateChoice("+d+','+test_type+")'>修改</a></span>"+
                                 "</div>"+
                                "<input type='hidden' readonly name='optionA[]' value='"+optionArr[0]['option']+"'>"+
                                "<input type='hidden' readonly name='optionB[]' value='"+optionArr[1]['option']+"'>"
                              );    
              $("#answer").html("<span>答案:</span>"+
                                 "<input type='radio' name='answer[]' value='1'>1&nbsp&nbsp&nbsp"+
                                 "<input type='radio'name='answer[]' value='2'>2");       
               break;
          case "4":
             if(sets_type==1){
              $("#option").html(
                                "<div class='option'><span>选项:</span><br/><div class='rule_done'><p>1."+optionArr[0]['option']+
                                  "</p><p>2."+optionArr[1]['option']+
                                  "</p><span class='detailSplit'>3."+optionArr[2]['option']+
                                  "</span><p><span class='detailSplit'>4."+optionArr[3]['option']+
                                  "</div>"+"</p></span><span><a href='javascript:void(0)' onclick='openUpdateChoice("+d+','+sets_type+")'>修改</a></span>"+
                                "</div>"+
                                "<input type='hidden' name='optionA[]' value='"+optionArr[0]['option']+"'>"+
                                "<input type='hidden' name='optionB[]' value='"+optionArr[1]['option']+"'>"+
                                "<input type='hidden' name='optionC[]' value='"+optionArr[2]['option']+"'>"+
                                "<input type='hidden' name='optionD[]' value='"+optionArr[3]['option']+"'>"
                                );              
              $("#answer").html("<span>答案:</span>"+
                                  "<input type='radio' name='answer[]' value='1'>1&nbsp&nbsp&nbsp"+
                                  "<input type='radio' name='answer[]' value='2'>2&nbsp&nbsp&nbsp"+
                                  "<input type='radio' name='answer[]' value='3'>3&nbsp&nbsp&nbsp"+
                                  "<input type='radio' name='answer[]' value='4'>4"
                                  );   
             }else if(sets_type==2){
                $("#option").html(
                                  "<div class='option'>"+
                                  "<span>选项:</span><br/><div class='rule_done'><p>1."+optionArr[0]['option']+
                                    "</p><p>2."+optionArr[1]['option']+
                                    "</p><span class='detailSplit'>3."+optionArr[2]['option']+
                                    "</span><p><span class='detailSplit'>4."+optionArr[3]['option']+                          
                                    "</div>"+"</p></span><span><a href='javascript:void(0)' onclick='openUpdateChoice("+d+','+sets_type+")'>修改</a></span>"+
                                  "</div>"+           
                                  "<input type='hidden' readonly name='optionA[]' value='"+optionArr[0]['option']+"'>"+
                                  "<input type='hidden' readonly name='optionB[]' value='"+optionArr[1]['option']+"'>"+
                                  "<input type='hidden' readonly name='optionC[]' value='"+optionArr[2]['option']+"'>"+
                                  "<input type='hidden' readonly name='optionD[]' value='"+optionArr[3]['option']+"'>"
                                  );
                $("#answer").html("<span>答案:</span>"+
                      "<input type='checkbox' name='answer[]' value='1'>1&nbsp&nbsp&nbsp"+
                      "<input type='checkbox'name='answer[]' value='2'>2&nbsp&nbsp&nbsp"+
                      "<input type='checkbox' name='answer[]' value='3'>3&nbsp&nbsp&nbsp"+
                      "<input type='checkbox' name='answer[]' value='4'>4");   
             }else{
                $("#option").html(
                                  "<div class='option'><span>选项:</span><br/><div class='rule_done'><p>1."+optionArr[0]['option']+
                                      "</p><p>2."+optionArr[1]['option']+   
                                    "</div>"+"</p></span><span><a href='javascript:void(0)' onclick='openUpdateChoice("+d+','+sets_type+")'>修改</a></span>"+
                                   "</div>"+
                                  "<input type='hidden' readonly name='optionA[]' value='"+optionArr[0]['option']+"'>"+
                                  "<input type='hidden' readonly name='optionB[]' value='"+optionArr[1]['option']+"'>"
                                );   
                $("#answer").html("<span>答案:</span>"+
                       "<input type='radio' name='answer[]' value='1'>1&nbsp&nbsp&nbsp"+
                       "<input type='radio'name='answer[]' value='2'>2&nbsp&nbsp&nbsp");  
             }
             break;
          case "5":
            $(".essay_answer").show();
            $(".addOption").hide();
          break;
          }
        }
      })       
     //获取题目的具体信息方便修改
     $.ajax({
       url:"/Admin/Test/getTitleInfo",
       type:"post",
       data:"id="+id+"&test_type="+test_type,
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
         case "5":
           $("#point").val(titleInfo["point"]);
           $("#difficult").val(titleInfo["level"]);
           $("#essay_answer").val(titleInfo["eaAnswer"]["answer"]);
           editor.html(titleInfo["content"]);
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
             url :'/Data/aspects/'+categoryId+'.json?' + parseInt(Math.random()*100000000),
             dataType : 'json',
             cacheResults :false
           }
         });
       }
     })             
 })




  //判断题目类型和输出题目具体信息
 function sub(){
  var choiceA=$("[name='optionA[]']").val();
  var choiceB=$("[name='optionB[]']").val();
  var choiceC=$("[name='optionC[]']").val();
  var choiceD=$("[name='optionD[]']").val();
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
  var eaAnswer=$("#essay_answer").val();
 	var point=document.getElementById('point').value;
 	var question=editor.html();
  question = question.replace(/%/g, "%25");  
  question= question.replace(/\&/g, "%26");  
  question= question.replace(/\+/g, "%2B");
  var level=$("#difficult").val();
  switch(test_type){
    case "5":
      var tips=eaAnswer==""?"答案不能为空":"";
    break;
    default:
      var tips=answer==""?"答案不能为空":"";
  }

  if(test_type==1&&test_type==2){
    if(choiceA==""||choiceB==""||choiceC==""||choiceD==""){
      tips+=tips==""?"题目选项不完整":",题目选项不完整" 
    }
  }else if(test_type==3){
    if(choiceA==""||choiceB==""){
      tips+=tips==""?"题目选项不完整":",题目选项不完整"
    }
  }else if(test_type==4){
    switch(sets_type){
      case "1":
        if(choiceA==""||choiceB==""||choiceC==""||choiceD==""){
         tips+=tips==""?"题目选项不完整":",题目选项不完整"
        }           
      break;
      case "2":
        if(choiceA==""||choiceB==""||choiceC==""||choiceD==""){
         tips+=tips==""?"题目选项不完整":",题目选项不完整"
        }           
      break; 
      case "3":
        if(choiceA==""||choiceB==""){
         tips+=tips==""?"题目选项不完整":",题目选项不完整"
        }           
      break;             
    }
    
  }  
  tips+=tips==""?aspects=="[]"?"知识点不能为空":"":aspects=="[]"?",知识点不能为空":"";
  tips+=tips==""?point==""?"分数不能为空":"":point==""?",分数不能为空":"";
  tips+=tips==""?question==""?"题干不为空":"":question==""?",题干不为空":"";
  if(tips!=""){
    alert(tips);
  }else{  
     $.ajax({
       url:"/Admin/Test/updateTest",
       type:"post",
       data:"aspects="+aspects+"&answer="+answer+"&eaAnswer="+eaAnswer+"&point="+point+
            "&question="+question+"&level="+level+"&id="+id+"&test_type="+test_type+
            "&choiceA="+choiceA+"&choiceB="+choiceB+"&choiceC="+choiceC+"&choiceD="+choiceD+"&sets_type="+sets_type,
       dataType:"text",
       success:function(d){
        //随即生成4张图片
          if(test_type!=4){
            $.ajax({
              url:"/Admin/Picture/update_html",
              type:"post",
              data:"testId="+id+"&choiceA="+choiceA+"&choiceB="+choiceB+"&choiceC="+choiceC+"&choiceD="+choiceD+"&answer="+answer+"&test_type="+test_type,
              dataType:"text",
              success:function(ed){
                testInfo();
              }
            })
          }else{
            $.ajax({
              url:"/Admin/Picture/update_html",
              type:"post",
              data:"testId="+id+"&test_type="+test_type+"&choiceA="+choiceA+"&choiceB="+choiceB+
                   "&choiceC="+choiceC+"&choiceD="+choiceD+"&answer="+answer+"&eaAnswer="+eaAnswer+"&sets_type="+sets_type,
              dataType:"text",
              success:function(fd){
                setsTitleInfo();
              }
            })
          }
       }    
     })
   }
 	}




  //获得试题信息
function testInfo(){
    var p=origin.$("#page").val()?origin.$("#page").val():1;
    origin.$("#test tr:gt(0)").empty();
    $.ajax({
        url:"/Admin/Test/index",
        type:"get",
        data:"categoryId="+categoryId+"&type="+test_type+"&p="+p,
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