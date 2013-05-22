var categoryId;
var type;
var aspect;

//删除试题
function del(id){
    var type=$("#type").val();
    var p=$("#page").val()?$("#page").val():1;
    $.ajax({
        url:"/Admin/Test/del",
        async:false,
        type:"post",
        data:"type="+type+"&id="+id,
        dataType:"text",
        success:function(d){
            if(d){
               testInfo(p);
            }else{
                alert("删除失败");
            }     
        }
    })
}

//获得试题信息
function testInfo(page){
    categoryId=$("#name").val();
    type=$("#type").val();
    aspectId=$("#aspects").val();
    var p=parseInt(page);
    if(!p){
        p=1;
    }
    $("#test tr:gt(0)").empty();
    $.ajax({
        url:"/Admin/Test/index",
        type:"get",
        data:"categoryId="+categoryId+"&type="+type+"&p="+p+"&aspectId="+aspectId+"&from="+$("#from").val()+"&to="+$("#to").val(),
        dataType:"text",
        success:function(d){
           $("#test").append(d);
        }
    })
}
//获取知识点
function getAspect(){
    $.ajax({
        url:"/Admin/Test/getAspect",
        type:"post",
        data:"categoryId="+categoryId,
        dataType:"text",
        success:function(d){
            var arrAsp=jQuery.parseJSON(d);
            var str="<option value='0'>------全部知识点------</option>";
            $("#aspects").empty();
            for(var i=0;i<arrAsp.length;i++){
                str+="<option value='"+arrAsp[i]["id"]+"'>"+arrAsp[i]["name"]+"</option>";
            }
            $("#aspects").append(str);
        }
    })

}
$(document).ready(function(){
    $( "#from" ).datepicker({
        defaultDate: "+1w",
        changeYear: true,
        changeMonth: true,
        numberOfMonths: 1,
        onClose: function( selectedDate ) {
            $( "#to" ).datepicker( "option", "minDate", selectedDate );
            if($("#from").val()&&$("#to").val()){
                testInfo();
            }
        }
    });
    $( "#to" ).datepicker({
        defaultDate: "+1w",
        changeMonth: true,
        numberOfMonths: 1,
        onClose: function( selectedDate ) {
            $( "#from" ).datepicker( "option", "maxDate", selectedDate );
            if($("#from").val()&&$("#to").val()){
                testInfo();
            }            
        }
    });    
    $("#type").bind("change", testInfo);
    $("#name").bind("change", testInfo);
    $("#name").bind("change",getAspect);
    $("#aspects").bind("change",testInfo);
    testInfo();

})