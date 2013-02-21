var test_type=art.dialog.data("type");
var setsId=art.dialog.data("id");
var origin=artDialog.open.origin;
$(function(){ 
    var keditor;
    KindEditor.ready(function(K) {
        keditor=K.create('#editor_id');
        editor=keditor;
    }); 
    $.ajax({
      url:"/exam/Admin/Test/getTitleSetsInfo",
      type:"post",
      data:"id="+setsId,
      dataType:"text",
      success:function(d){
        var titleInfo=jQuery.parseJSON(d);
        var description=titleInfo[0];
        editor.html(description);
        var singleTitleOb=$("#singleTitle");
        var aspects="";
        for(var i=1;i<titleInfo.length;i++){
          var testId=titleInfo[i]["id"];
          var content=titleInfo[i]["content"];
          var point=titleInfo[i]["point"];
          var answer=titleInfo[i]["answer"];
          var level=titleInfo[i]["level"];
          for(var j=0;j<titleInfo[i]["name"].length;j++){
            aspects+=aspects?","+titleInfo[i]["name"][j]["name"]:titleInfo[i]["name"][j]["name"];
          }
          singleTitleOb.append("<div class='rule_done'><p>问题:"+content+
                              "</p><p>知识点:"+aspects+
                              "</p><span class='detailSplit'>分数:"+point+
                              "</span><span class='detailSplit'>答案:"+answer+
                              "</span><span class='detailSplit'>难度:"+level+
                              "</span><span><a href='javascript:void(0)' onclick='openUpdateTest("+testId+","+setsId+")'>修改</a></span>"+
                              "</div>");
            aspects="";
        }
      }
    })

})
function updateDescription(){
  var description=editor.html();
  $.ajax({
      url:"/exam/Admin/Test/updateDescription",
      type:"post",
      data:"id="+setsId+"&description="+description,
      dataType:"text",
      success:function(d){
        art.dialog.close();
      }
    })

}