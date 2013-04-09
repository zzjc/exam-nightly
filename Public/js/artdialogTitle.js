
  var editor;
  var origin=artDialog.open.origin;
  var setsType=art.dialog.data("setsType");
  var cat_id=origin.$("#name").val();
  var test_type=origin.$("input[name='test_type'][type='radio']:checked").val();
  $(function(){ 
      $('#aspects').textext({
          plugins : 'tags prompt focus autocomplete ajax arrow',
          tagsItems : "",
          prompt : '选择知识点...',
          html: {
              hidden: '<input type="hidden" id="aspects_name" />'
          },
          ajax : {
              url : '/Data/aspects/'+cat_id+'.json?' + parseInt(Math.random()*100000000),
              dataType : 'json',
              cacheResults : false
          }
      });            
      var keditor;
      KindEditor.ready(function(K) {
          keditor=K.create('#editor_id');
          editor=keditor;               
      });
      switch(test_type)
   　{
　      case "1":
           $("#answer").html("<span>答案:</span>"+
                               "<input type='radio' name='answer[]' value='A'>A"+
                               "<input type='radio' name='answer[]' value='B'>B"+
                               "<input type='radio' name='answer[]' value='C'>C"+
                               "<input type='radio' name='answer[]' value='D'>D");        
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
            switch(setsType){
              case "1":
                $("#answer").html("<span>答案:</span>"+
                  "<input type='radio' name='answer[]' value='A'>A"+
                  "<input type='radio' name='answer[]' value='B'>B"+
                  "<input type='radio' name='answer[]' value='C'>C"+
                  "<input type='radio' name='answer[]' value='D'>D");        
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
            }
    }             
  })
  /*
    *添加题目选项
  */
  function openAddOption(){
    art.dialog.data("test_type",test_type);
    art.dialog.data("setsType",setsType);
    art.dialog.open("/Tpl/default/Admin/Test/addOption.html",{fixed: true,id:"testOption",width:520,height:210,title:'添加题目选项'}, false);         
  }
  /*
    *添加知识点
  */
  function addAspects(){
    art.dialog.data("cat_id",cat_id);
    art.dialog.open("/Tpl/default/Admin/Test/addAspects.html",{title:"添加知识点"});
  }
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
    var choiceA=$("[name='optionA[]']").val();
    var choiceB=$("[name='optionB[]']").val();
    var choiceC=$("[name='optionC[]']").val();
    var choiceD=$("[name='optionD[]']").val();
    var tips=answer==""?"答案不能为空":"";
    if(test_type!=3&&test_type!=4){
      if(choiceA==""||choiceB==""||choiceC==""||choiceD==""||choiceA==" "||choiceB==" "||choiceC==" "||choiceD==" "){
        tips+=tips==""?"题目选项不完整":",题目选项不完整" 
      }
    }else if(test_type==3){
      if(choiceA==""||choiceB==""||choiceA==" "||choiceB==" "||choiceC==" "||choiceD==" "){
        tips+=tips==""?"题目选项不完整":",题目选项不完整"
      }
    }else if(test_type==4){
      switch(setsType){
        case "1":
          if(choiceA==""||choiceB==""||choiceC==""||choiceD==""||choiceA==" "||choiceB==" "||choiceC==" "||choiceD==" "){
           tips+=tips==""?"题目选项不完整":",题目选项不完整"
          }           
        break;
        case "2":
          if(choiceA==""||choiceB==""||choiceC==""||choiceD==""||choiceA==" "||choiceB==" "||choiceC==" "||choiceD==" "){
           tips+=tips==""?"题目选项不完整":",题目选项不完整"
          }           
        break; 
        case "3":
          if(choiceA==""||choiceB==""||choiceA==" "||choiceB==" "||choiceC==" "||choiceD==" "){
           tips+=tips==""?"题目选项不完整":",题目选项不完整"
          }           
        break;             
      }
    }
    tips+=tips==""?aspects=="[]"?"知识点不能为空":"":aspects=="[]"?",知识点不能为空":"";
    tips+=tips==""?point==""?"分数不能为空":"":point==""?",分数不能为空":"";
    tips+=tips==""?question==""?"题干不为空":"":question==""?",题干不为空":"";
    if(tips==""){
      var detailOb=origin.$("#titleDetail");
      if(test_type!=3){
        detailOb.append("<div class='rule_done'><p>问题:"+question+
                              "</p><p>1."+choiceA+
                              "</p><p>2."+choiceB+
                              "</p><p>3."+choiceC+
                              "</p><p>4."+choiceD+
                              "</p><p>知识点:"+aspects+
                              "</p><span class='detailSplit'>分数:"+point+
                              "</span><span class='detailSplit'>答案:"+answer+
                              "</span><span class='detailSplit'>难度:"+level+
                              "</div>");
        var title_hide=$("<div class='title_hide'>"+
                            "<input type='hidden' name='content[]' value='"+question+"'/>"+
                            "<input type='hidden' name='optionA[]' value='"+choiceA+"'/>"+
                            "<input type='hidden' name='optionB[]' value='"+choiceB+"'/>"+
                            "<input type='hidden' name='optionC[]' value='"+choiceC+"'/>"+
                            "<input type='hidden' name='optionD[]' value='"+choiceD+"'/>"+
                            "<input type='hidden' name='name[]' value='"+aspects+"'/>"+
                            "<input type='hidden' name='answer[]' value='"+answer+"'/>"+
                            "<input type='hidden' name='point[]' value='"+point+"'/>"+
                            "<input type='hidden' name='level[]' value='"+level+"'/>"+
                            "<input type='hidden' name='setsType[]' value='"+setsType+"'/>"+
                          "</div>");
      }else{
        detailOb.append("<div class='rule_done'><p>问题:"+question+
                              "</p><p>1."+choiceA+
                              "</p><p>2."+choiceB+
                              "</p><p>知识点:"+aspects+
                              "</p><span class='detailSplit'>分数:"+point+
                              "</span><span class='detailSplit'>答案:"+answer+
                              "</span><span class='detailSplit'>难度:"+level+
                              "</div>");
        var title_hide=$("<div class='title_hide'>"+
                            "<input type='hidden' name='content[]' value='"+question+"'/>"+
                            "<input type='hidden' name='optionA[]' value='"+choiceA+"'/>"+
                            "<input type='hidden' name='optionB[]' value='"+choiceB+"'/>"+
                            "<input type='hidden' name='name[]' value='"+aspects+"'/>"+
                            "<input type='hidden' name='answer[]' value='"+answer+"'/>"+
                            "<input type='hidden' name='point[]' value='"+point+"'/>"+
                            "<input type='hidden' name='level[]' value='"+level+"'/>"+
                            "<input type='hidden' name='setsType[]' value='"+setsType+"'/>"+
                          "</div>");        
      }
      detailOb.append(title_hide);
      origin.$("#addText").css("display","inline");
      origin.$("#resetText").css("display","inline");
      origin.$("#titleSingle").css("display","none");
      art.dialog.close();
    }else{
      alert(tips);
    }
}    
