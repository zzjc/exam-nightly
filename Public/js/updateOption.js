 var optionArr=art.dialog.data("option");
 var test_type=art.dialog.data("test_type");
 var origin=artDialog.open.origin;
 var editor_A;
 var editor_B;
 var editor_C;
 var editor_D;
 $(function(){
     editor_A=$("#editor_A");
     editor_B=$("#editor_B");
     editor_C=$("#editor_C");
     editor_D=$("#editor_D");          
     if(test_type!=3){
        editor_A.val(origin.$("[name='optionA[]']").val());
        editor_B.val(origin.$("[name='optionB[]']").val());
        editor_C.val(origin.$("[name='optionC[]']").val());
        editor_D.val(origin.$("[name='optionD[]']").val());

     }else{
        $("#option").empty();
        $("#option").append(
            "<div >选项A:<textarea id=\"editor_A\" name='optionA[]' class='test_box'> </textarea> </div>"+
            "<div >选项B:<textarea id=\"editor_B\" name='optionB[]' class='test_box'> </textarea></div>");
        editor_A=$("#editor_A");
        editor_B=$("#editor_B");
        editor_A.val(origin.$("[name='optionA[]']").val());
        editor_B.val(origin.$("[name='optionB[]']").val());
     }    
 })

 function updateOption(){
    origin.$(".rule_done").empty();
    if(test_type!=3){
        origin.$(".rule_done").append("<p>1."+editor_A.val()+
                                      "</p><p>2."+editor_B.val()+
                                      "</p><span class='detailSplit'>3."+editor_C.val()+
                                      "</span><p><span class='detailSplit'>4."+editor_D.val()+"</span>");
        origin.$("[name='optionA[]']").val(editor_A.val());
        origin.$("[name='optionB[]']").val(editor_B.val());
        origin.$("[name='optionC[]']").val(editor_C.val());
        origin.$("[name='optionD[]']").val(editor_D.val());
        art.dialog.close();
    }else{
        origin.$(".rule_done").append("<p>1."+editor_A.val()+
                                      "</p><p>2."+editor_B.val()+
                                      "</p>");
        origin.$("[name='optionA[]']").val(editor_A.val());
        origin.$("[name='optionB[]']").val(editor_B.val());
        art.dialog.close();
    }
 }
