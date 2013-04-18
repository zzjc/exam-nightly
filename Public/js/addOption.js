 var test_type=art.dialog.data("test_type");
 var setsType=art.dialog.data("setsType");
 if(setsType){
    test_type=setsType;
 }
 var origin=artDialog.open.origin;
 var valA=origin.$("[name='optionA[]']").val();
 var valB=origin.$("[name='optionB[]']").val();
 var valC=origin.$("[name='optionC[]']").val();
 var valD=origin.$("[name='optionD[]']").val();
 $(function(){     
     if(test_type==3){
        $("#option").empty();
        $("#option").append(
            "<div >选项A:<textarea id=\"editor_A\" name='optionA[]' class='test_box'></textarea> </div>"+
            "<div >选项B:<textarea id=\"editor_B\" name='optionB[]' class='test_box'></textarea></div>");
     }
     if(valA){
        $("[name='optionA[]']").val(valA);
        $("[name='optionB[]']").val(valB);
        $("[name='optionC[]']").val(valC);
        $("[name='optionD[]']").val(valD);
     }
    $("[name='optionA[]']").focus();
 })

 function addOption(){
    var editor_A=$("[name='optionA[]']").val();
    var editor_B=$("[name='optionB[]']").val();
    var editor_C=$("[name='optionC[]']").val();
    var editor_D=$("[name='optionD[]']").val();
    if(!editor_A&&!editor_B&&!editor_C&&!editor_D){
      alert("选项不完整");

    }else{
      origin.$(".rule_done").empty();
      if(test_type!=3){
          origin.$(".rule_done").append("<p>选项:</p>"+
                                        "<p>1."+editor_A+
                                        "</p><p>2."+editor_B+
                                        "</p><p><span class='detailSplit'>3."+editor_C+
                                        "</span></p><p><span class='detailSplit'>4."+editor_D+
                                        "</span></p><div id='update_option'><a href='javascript:void(0)' onclick='openUpdateOption()'>修改选项</a></div>");
          origin.$("[name='optionA[]']").val(editor_A);
          origin.$("[name='optionB[]']").val(editor_B);
          origin.$("[name='optionC[]']").val(editor_C);
          origin.$("[name='optionD[]']").val(editor_D);
          origin.$(".addOption").hide();
          art.dialog.close();
      }else{
          origin.$(".rule_done").append("<p>1.:"+editor_A+
                                        "</p><p>2."+editor_B+
                                        "</p><div id='update_option'><a href='javascript:void(0)' onclick='openUpdateOption()'>修改选项</a></div>");
          origin.$("[name='optionA[]']").val(editor_A);
          origin.$("[name='optionB[]']").val(editor_B);
          origin.$(".addOption").hide();
          art.dialog.close();
      }
  }
 }
