//删除试题
function del(id){
    var type=$("#type").val();
    $.ajax({
        url:"/exam/Admin/Test/del",
        async:false,
        type:"post",
        data:"type="+type+"&id="+id,
        dataType:"text",
        success:function(d){
            if(d){
               testInfo();
            }else{
                alert("删除失败");
            }     
        }
    })
}
//获得试题信息
function testInfo(page){
    var type=$("#type").val();
    if(typeof(page)=="number"){
        var p=page;
    }else{
        var p=1;
    }
    $("#test tr:gt(0)").empty();
    $.ajax({
        url:"/exam/Admin/Test/index",
        type:"get",
        data:"type="+type+"&p="+p,
        dataType:"text",
        success:function(d){
            $("#test").append(d);
        }
    })
}
$(document).ready(function(){
    $("#type").bind("change", testInfo);
    testInfo();

})
 