// JavaScript Document
;(function (app, $) {
    app.refund_list = {
        init: function () {
            $(".date").datepicker({
                format: "yyyy-mm-dd",
            });
            
			$('.screen-btn').on('click', function(e) {
				e.preventDefault();
				var url				= $("form[name='searchForm']").attr('action');
				var start_date		= $("input[name='start_date']").val();
				var end_date		= $("input[name='end_date']").val(); 
				var status = $("select[name='status']").val();
				var keywords = $("input[name='keywords']").val();
				
				if (start_date != '' && end_date !='') {
					if (start_date >= end_date && (start_date != '' && end_date !='')) {
						var data = {
							message : "查询的开始时间不能大于结束时间！",
							state : "error",
						};
						ecjia.merchant.showmessage(data);
						return false;
					} else {
						url += '&start_date=' + start_date + '&end_date=' +end_date
					}
				}
				
				if (status != '') {
	                url += '&status=' + status;
	            }
                if (keywords != '') {
                    url += '&keywords=' + keywords;
                }
				
				ecjia.pjax(url);
			});
        }
    };
    
    app.refund_info = {
        init: function () {
            $("#order-info").click(function(){
            	$(".order-info").toggle();
            });
            
            $("#address-info").click(function(){
            	$(".address-info").toggle();
            });
            
            $('.change_status').on('click', function(e) {
            	e.preventDefault();
				var $this = $(this);
				var url = $this.attr('data-href');
				var type = $this.attr('data-type');
				var action_note = $("#action_note").val();
				var refund_id = $("#refund_id").val();
				var option = {'type' : type, 'action_note' : action_note,'refund_id' : refund_id};
				$.post(url, option, function(data){
					ecjia.merchant.showmessage(data);
				})
			});
        },
    };
    
    app.return_info = {
            init: function () {
                $("#order-info").click(function(){
                	$(".order-info").toggle();
                });
                
                $("#address-info").click(function(){
                	$(".address-info").toggle();
                });
                
	            $("#modal").on('click', function (e) {
	            	e.preventDefault();
                    $("#note_btn").on('click', function (e) {
 	                	e.preventDefault();
 	                	var action_note = $("#action_note").val();
 	 	                var refund_id   = $("#refund_id").val();
 	                    var url = $("form[name='actionForm']").attr('action');
 	                    var option = {'type' : 'agree','refund_id' : refund_id, 'action_note' : action_note};
 	                    $.post(url, option, function (data) {
 	                         ecjia.merchant.showmessage(data);
 	                         location.href = data.url;
 	                    }, 'json');
 	                });
				});
                
               	$('.change_status_disagree').on('click', function(e) {
    	    		e.preventDefault();
    				var $this = $(this);
    				var url = $this.attr('data-href');
    				var action_note = $("#action_note").val();
    				var refund_id = $("#refund_id").val();
    				var option = {'type' : 'disagree', 'action_note' : action_note,'refund_id' : refund_id};
    				$.post(url, option, function(data){
    					ecjia.merchant.showmessage(data);
    				})
    			});
            },
        };
    
})(ecjia.merchant, jQuery);
 
// end