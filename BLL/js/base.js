$(document).ready(function(){
	
	//结果类型 按钮 选择属性事件
	if (($('input[name="hid"][value="0"]').attr("checked"))==='checked'){
		$('.hidflag').css('display','none');
	}
	//结果类型 按钮 点击事件
	$('input[name="hid"][value="1"]').bind("click",function(){
	 	$('.hidflag').css('display','inline');

	});

	$('input[name="hid"][value="0"]').bind("click",function(){
	 	$('.hidflag').css('display','none');

	});

	/*
	$(".option").focus(function(){
			$(this).val('');
	})
	*/



	//查看日志内容 按钮 点击事件处理
	$("#showlog").bind("click",function(){
	
		if(!$('#source').val()){
			alert("日志地址没有填写");
			return false;

		};
		$.ajax({
		url:"ajax/showlog.php",
                type:"GET",
                dataType:"html",
                data:"filename="+$('#source').val(),
                cache:false,
                timeout: 60000,
                error: function(){
                        alert('Error loading HTML document');
                },
                success:function(html){
                        if(html) {
                               $("#description").text(html);
                        }
                        else {
				alert("不存在文件或文件为空");
                        }
                }
        });
		




	});

});