/*
@功能：购物车页面js
@作者：diamondwang
@时间：2013年11月14日
*/

$(function(){
	
	//减少
	$(".reduce_num").click(function(){
		var amount = $(this).parent().find(".amount");
		if (parseInt($(amount).val()) <= 1){
			alert("商品数量最少为1");
		} else{
			$(amount).val(parseInt($(amount).val()) - 1);
                        
                        // 触发更新
                        ajaxUpdataData(this,parseInt($(amount).val()));
		}
		//小计
		var subtotal = parseFloat($(this).parent().parent().find(".col3 span").text()) * parseInt($(amount).val());
		$(this).parent().parent().find(".col5 span").text(subtotal.toFixed(2));
		//总计金额
		getTotal();
	});

	//增加
	$(".add_num").click(function(){
		var amount = $(this).parent().find(".amount");
		$(amount).val(parseInt($(amount).val()) + 1);
                
                // 触发更新
                ajaxUpdataData(this,parseInt($(amount).val()));
		
		//小计
		var subtotal = parseFloat($(this).parent().parent().find(".col3 span").text()) * parseInt($(amount).val());
		$(this).parent().parent().find(".col5 span").text(subtotal.toFixed(2));
		//总计金额
		getTotal();
	});

	//直接输入
	$(".amount").blur(function(){
		if (parseInt($(this).val()) < 1){
			alert("商品数量最少为1");
			$(this).val(1);
		}
                var amount = $(this).parent().find(".amount");
                ajaxUpdataData(this,parseInt($(amount).val()));
		//小计
		var subtotal = parseFloat($(this).parent().parent().find(".col3 span").text()) * parseInt($(this).val());
		$(this).parent().parent().find(".col5 span").text(subtotal.toFixed(2));
		
                getTotal();
	});
        
        getTotal();
});
function getTotal(){
    //总计金额
    var total = 0;
    $(".col5 span").each(function(){
            total += parseFloat($(this).text());
    });

    $("#total").text(total.toFixed(2));
}

function ajaxUpdataData(target,goods_number){
    var tr = $(target).parent().parent('tr')
    var goods_id = tr.attr('goods_id');
    var goods_attr_ids = tr.attr('goods_attr_ids');
    $.ajax({
        type: "post",
        url: "edit",
        data: "goods_id="+goods_id+"&goods_attr_ids="+goods_attr_ids+"&goods_number="+goods_number,
        success: function (data) {
            console.log(data);
        }
    });
}