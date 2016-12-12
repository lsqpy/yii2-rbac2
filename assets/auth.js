//全选
$(function(){
    $("input[level=1]").click(function(){
        var isChecked = $(this).prop("checked");
        $(this).parents("tr").find("input").prop("checked",isChecked);
    });

    //选择权限
    $("#auth_item_access").on("change", function () {
        $("#authitem-name").val($(this).val());
    });


    //选择组
    $(".select_group").on("click", function () {
        $(this).parents("div.input-group").find("input[name='group[]']").val($(this).html());
    });

    //+组名
    $(".join_group").on("click", function () {
        var checked = $(this).prop("checked");
        var decollator = '-';
        var value = $(this).parents("div.input-group").find("input[name='description[]']").val();
        var group = $(this).parents("tr").find("td:eq(1)").find("input[name='group[]']").val();

        if (checked) {
            $(this).parents("div.input-group").find("input[name='description[]']").val(group + decollator + value);
        } else {
            $(this).parents("div.input-group").find("input[name='description[]']").val(value.replace(group + decollator, ""));
        }
    });

    //同上
    $(".ditto").on("click", function () {
        var checked = $(this).prop("checked");
        var group = $(this).parents("tr").prev().find("td:eq(1)").find("input[name='group[]']").val();
        if (checked) {
            $(this).parents("div.input-group").find("input[name='group[]']").val(group);
        } else {
            $(this).parents("div.input-group").find("input[name='group[]']").val("");
        }
    });

});
