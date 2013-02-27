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
       $("#titleSingle").removeAttr("disabled");
       $("#titleSets").removeAttr("disabled");
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
     }
    $(function(){
      type();
      $("[name='test_type']").bind("change",type);
      $("#titleSets").bind("click",selectSetsType);
      $("#titleSingle").bind("click",openTitleSingleDel);
    })