 var test_type=art.dialog.data("test_type");
 var setsType=art.dialog.data("setsType");
 if(setsType){
    test_type=setsType;
 }
 var origin=artDialog.open.origin;
 $(function(){     
     if(test_type==3){
        $("#option").empty();
        $("#option").append(
            "<div >选项A:<textarea id=\"editor_A\" name='optionA[]' class='test_box'> </textarea> </div>"+
            "<div >选项B:<textarea id=\"editor_B\" name='optionB[]' class='test_box'> </textarea></div>");
     }
    $("[name='optionA[]']").focus();
 })

 function addOption(){
    var editor_A=$("#editor_A").val();
    var editor_B=$("#editor_B").val();
    var editor_C=$("#editor_C").val();
    var editor_D=$("#editor_D").val();
    origin.$(".rule_done").empty();
    if(test_type!=3){
        origin.$(".rule_done").append("<p>选项:</p>"+
                                      "<p>1."+editor_A+
                                      "</p><p>2."+editor_B+
                                      "</p><span class='detailSplit'>3."+editor_C+
                                      "</span><p><span class='detailSplit'>4."+editor_D+"</span>");
        origin.$("[name='optionA[]']").val(editor_A);
        origin.$("[name='optionB[]']").val(editor_B);
        origin.$("[name='optionC[]']").val(editor_C);
        origin.$("[name='optionD[]']").val(editor_D);
        art.dialog.close();
    }else{
        origin.$(".rule_done").append("<p>1.:"+editor_A+
                                      "</p><p>2."+editor_B+
                                      "</p>");
        origin.$("[name='optionA[]']").val(editor_A);
        origin.$("[name='optionB[]']").val(editor_B);
        art.dialog.close();
    }
 }
