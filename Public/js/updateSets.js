/*
*setsId题组id
*test_type题目类型
*categoryId考试科目类别
*/
var setsId=art.dialog.data("id");
var origin=artDialog.open.origin;
var test_type=art.dialog.data("type");
var categoryId=art.dialog.data("categoryId");
$(function(){ 
    var keditor;
    KindEditor.ready(function(K) {
        keditor=K.create('#editor_id');
        editor=keditor;
    }); 
    $.ajax({
      url:"/Admin/Test/getTitleSetsInfo",
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
          var test_type=titleInfo[i]["test_type"];
          for(var j=0;j<titleInfo[i]["name"].length;j++){
            aspects+=aspects?","+titleInfo[i]["name"][j]["name"]:titleInfo[i]["name"][j]["name"];
          }
          singleTitleOb.append("<div class='rule_done'><p>问题:"+content+
                              "</p><p>知识点:"+aspects+
                              "</p><span class='detailSplit'>分数:"+point+
                              "</span><span class='detailSplit'>答案:"+answer+
                              "</span><span class='detailSplit'>难度:"+level+
                              "</span><span><a href='javascript:void(0)' onclick='openUpdateTest("+testId+","+setsId+","+test_type+")'>修改</a></span>"+
                              "</div>");
            aspects="";
        }
      }
    })

})
//修改材料阅读题材料
function updateDescription(){
  var description=editor.html();
  if(description!=""){
    $.ajax({
        url:"/Admin/Test/updateDescription",
        type:"post",
        data:"id="+setsId+"&description="+description,
        dataType:"text",
        success:function(d){
          testInfo();
        }
      })
  }else{
    alert("题干不能为空");
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