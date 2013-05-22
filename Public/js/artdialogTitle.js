  var editor;
  var origin=artDialog.open.origin;
  var setsType=art.dialog.data("setsType")==""?"":art.dialog.data("setsType");
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
            cacheResults : true
        }
    });

    switch(test_type){
          case "5":
            $(".essay_answer").show();
            $(".addOption").hide();
          break;
          default:
            $(".essay_answer").hide();
          break;
    }     

    var keditor;
    KindEditor.ready(function(K) {
        keditor=K.create('#editor_id', {
                afterTab : function() {
                  switch(test_type){
                    case "5":
                      $("#essay_answer").focus();
                      break;
                    default:
                      if($("[name='option[]']").val()){
                        $("#update_option a").focus();
                      }else{
                        $(".addOption span a").focus();
                      }
                  }
                }
              });
        keditor.focus();
        editor=keditor;               
    });

    $(".point").bind("change",coo);
    $("#difficult").bind("change",coo);
    if($.cookie("point")){
      document.getElementById('point').value=$.cookie("point");
    }
    if($.cookie("level")){
      $("#difficult").val($.cookie("level"));
    }

    $(".btn").blur(function(){
      keditor.focus();
    })            
  })

  /*
    *生成answer的个数
  */
  function createAns(){
      var optionLen=$("#option [name='option[]']").length;
      var str="<span>答案:</span>";
      switch(test_type){
          case "1":
            for(var i=1;i<optionLen+1;i++){
              if(i==1){
                str+="<input type='radio' name='answer[]' value='"+i+"' checked='checked'>("+i+")&nbsp&nbsp&nbsp";
              }else{
                str+="<input type='radio' name='answer[]' value='"+i+"'>("+i+")&nbsp&nbsp&nbsp";
              }
            }
           $("#answer").html(str);        
          break;
         case "2":
              for(var i=1;i<optionLen+1;i++){
                if(i==1){
                  str+="<input type='checkbox' name='answer[]' value='"+i+"'>("+i+")&nbsp&nbsp&nbsp";
                }else{
                  str+="<input type='checkbox' name='answer[]' value='"+i+"'>("+i+")&nbsp&nbsp&nbsp";
                }
              }
              $("#answer").html(str);                                          
              break;
          case "3":
              for(var i=1;i<optionLen+1;i++){
                if(i==1){
                  str+="<input type='radio' name='answer[]' value='"+i+"' checked='checked'>("+i+")&nbsp&nbsp&nbsp";
                }else{
                  str+="<input type='radio' name='answer[]' value='"+i+"'>("+i+")&nbsp&nbsp&nbsp";
                }
              }
              $("#answer").html(str);        
               break;
          case "4":
            switch(setsType){
              case "1":
                for(var i=1;i<optionLen+1;i++){
                  if(i==1){
                    str+="<input type='radio' name='answer[]' value='"+i+"' checked='checked'>("+i+")&nbsp&nbsp&nbsp";
                  }else{
                    str+="<input type='radio' name='answer[]' value='"+i+"'>("+i+")&nbsp&nbsp&nbsp";
                  }
                }
                $("#answer").html(str);         
              break;
              case "2":
                for(var i=1;i<optionLen+1;i++){
                  if(i==1){
                    str+="<input type='checkbox' name='answer[]' value='"+i+"'>("+i+")&nbsp&nbsp&nbsp";
                  }else{
                    str+="<input type='checkbox' name='answer[]' value='"+i+"'>("+i+")&nbsp&nbsp&nbsp";
                  }
                }
                $("#answer").html(str);          
                break;
              case "3":
                for(var i=1;i<optionLen+1;i++){
                  if(i==1){
                    str+="<input type='radio' name='answer[]' value='"+i+"' checked='checked'>("+i+")&nbsp&nbsp&nbsp";
                  }else{
                    str+="<input type='radio' name='answer[]' value='"+i+"'>("+i+")&nbsp&nbsp&nbsp";
                  }
                }
                $("#answer").html(str);          
                break;
            }
            break;
    }
  }
  /*
    *生成答案和分数的cookie记录
  */
  function coo(){
    var point=document.getElementById('point').value;
    var level=$("#difficult").val();
    $.cookie("point", point); 
    $.cookie("level", level);
  }
  /*
    *添加题目选项
  */
  function openAddOption(){
    art.dialog.data("test_type",test_type);
    art.dialog.data("setsType",setsType);
    art.dialog.open("/Tpl/default/Admin/Test/addOption.html",{fixed: true,id:"testOption",width:510,height:510,title:'添加题目选项'}, false);         
  }
  /*
    *修改题目选项
  */
  function openUpdateOption(){
    art.dialog.data("test_type",test_type);
    art.dialog.data("setsType",setsType);
    art.dialog.open("/Tpl/default/Admin/Test/addOption.html",{fixed: true,id:"testOption",width:510,height:520,title:'添加题目选项'}, false);      
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
	var answer="";
  var aspects=document.getElementById('aspects_name').value;
  if(test_type!=5){
    var answerMulti=$("input[name='answer[]'][type='checkbox']:checked");
    var answerSingle=$("input[name='answer[]'][type='radio']:checked").val();
    if(answerMulti!=""){
        var multiAnswer="";
        for(var i=0;i<answerMulti.length;i++){
            multiAnswer+=answerMulti[i].value;   
         }
    }
  	 answer=answerSingle?answerSingle:multiAnswer;
  }else{
    answer=$("#essay_answer").val();
  }
	var point=document.getElementById('point').value;
  if(editor){
	 var question=editor.html();
  }
    var level=$("#difficult").val();
    var tips=answer==""?"答案不能为空":"";
    var optionLen=$("#option [name='option[]']").length;
    tips+=tips==""?question==""?"题干不为空":"":question==""?",题干不为空":"";
    if(test_type!=5){
      tips+=tips==""?optionLen==0?"选项不完整":"":optionLen==0?",选项不完整":"";
    }
    tips+=tips==""?aspects=="[]"?"知识点不能为空":"":aspects=="[]"?",知识点不能为空":"";
    tips+=tips==""?point==""?"分数不能为空":"":point==""?",分数不能为空":"";
    if(tips==""){
      var detailOb=origin.$("#titleDetail");
      var num=origin.$(".num:last").val();
      if(!num&&num!=0){
        num=0;
      }else{
        num=parseInt(num)+1;
      }
      var j="";
      var optionStr="";
      var optionHid="";
      for(var i=0;i<optionLen;i++){
        j=i+1;
        optionStr+="</p><p>"+j+":"+$("[name='option[]']")[i].value;
        optionHid+="<input type='hidden' name='option["+num+"][]' value='"+$("[name='option[]']")[i].value+"'/>"
      }
      if(test_type!=5){
        detailOb.append("<div class='rule_done'><p>问题:"+question+optionStr+
                              "</p><p>知识点:"+aspects+
                              "</p><span class='detailSplit'>分数:"+point+
                              "</span><span class='detailSplit'>答案:"+answer+
                              "</span><span class='detailSplit'>难度:"+level+
                              "</div>");
        var title_hide=$("<div class='title_hide'>"+
                            "<input type='hidden' name='content[]' value='"+question+"'/>"+optionHid+
                            "<input type='hidden' name='name[]' value='"+aspects+"'/>"+
                            "<input type='hidden' name='cat_id' value='"+cat_id+"'/>"+
                            "<input type='hidden' name='answer[]' value='"+answer+"'/>"+
                            "<input type='hidden' name='point[]' value='"+point+"'/>"+
                            "<input type='hidden' name='level[]' value='"+level+"'/>"+
                            "<input type='hidden' name='setsType[]' value='"+setsType+"'/>"+
                            "<input type='hidden' class='num' value='"+num+"'>"+
                          "</div>");
      }else if(test_type==5){
        detailOb.append("<div class='rule_done'><p>问题:"+question+
                      "</p><p><span class='detailSplit'>答案:"+answer+
                      "</span></p><p>知识点:"+aspects+
                      "</p><span class='detailSplit'>分数:"+point+
                      "</span><span class='detailSplit'>难度:"+level+
                      "</span></div>");
        var title_hide=$("<div class='title_hide'>"+
                            "<input type='hidden' name='content[]' value='"+question+"'/>"+          
                            "<input type='hidden' name='name[]' value='"+aspects+"'/>"+
                            "<input type='hidden' name='cat_id' value='"+cat_id+"'/>"+
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
