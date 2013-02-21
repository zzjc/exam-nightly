$(document).ready(function(){
    // 提交一组规则
    $('#submit_rule').click(function(){
        var type = $('#type').val();
        var type_text = $("#type>option:selected").text();
        var difficult_from = $("#difficult_from").val();
        var difficult_to = $('#difficult_to').val();
        var num = $(".number").val();
        var per_score = $(".perscore").val();
        var aspects_name = $('#aspects_name').val();
        var difficult = difficult_from + '-' + difficult_to;
        console.log(type_text);
        
        var total_score = parseInt(num) * parseInt(per_score);
        var rule = $("<div class='rule_done'>" +
            "<p>题型：" + type_text + ", 难度："+difficult+", 本规则总分："+total_score+"</p><p>题数："+num+",每题分数："+per_score+"</p>" +
            "<p>知识点："+ aspects_name +"</p>" +
            "</div>");
        $("#rule_list").append(rule);
        // 增加知识点隐藏域
        var rule_hide = $("<div class='rule_hide_done'>" +
            "<input type='hidden' name='type[]' value='"+ type +"' />" +
            "<input type='hidden' name='difficult[]' value='"+ difficult +"' />" +
            "<input type='hidden' name='number[]' value='"+ num +"' />" +
            "<input type='hidden' name='score[]' value='" + per_score + "' />" +
            "<input type='hidden' name='aspects[]' value='"+ aspects_name +"' />" +
            "</div>");
        $("#rule_hide_list").append(rule_hide);
        $(".new_rule").hide();
        $(".number").val('');
        $(".perscore").val('');
        $('#aspects_name').val();
        // 计算总分和总题数
        var current_score = parseInt($("#score").text());
        var new_current_score = current_score + total_score;
        $("#score").text(new_current_score);
        var current_total_num = parseInt($("#total_num").text());
        var new_current_total_num = current_total_num + parseInt(num);
        $("#total_num").text(new_current_total_num);
        $("#score_hidden").val(new_current_score);
        $("#total_hidden").val(new_current_total_num);
    });
    $("#add_rule").click(function(){
        $(".new_rule").toggle();
    });
});

// 表单验证 （临时）
function tmpl_validate()
{
    if($('#name').val() == '') {
        alert('试卷标题不能为空');
        return false;
    }
    if($('#description').val() == '') {
        alert('试卷说明不能为空');
        return false;
    }
    if ($('.rule_done').length < 1) {
        if ($('input.number').val() == '') {
            alert('题数不能为空');
            return false;
        }
        if ($('.perscore').val() == '') {
            alert('每题分数不能为空');
            return false;
        }
    }
}