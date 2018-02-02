// JavaScript Document
;(function (app, $) {
    app.payrecord_list = {
        init: function () {
            $(".date").datepicker({
                format: "yyyy-mm-dd",
            });
            
			$('.screen-btn').on('click', function(e) {
				e.preventDefault();
				var url				= $("form[name='searchForm']").attr('action');
				var start_date		= $("input[name='start_date']").val();
				var end_date		= $("input[name='end_date']").val(); 
				var refund_type = $("select[name='refund_type']").val();
				var keywords = $("input[name='keywords']").val();
				
				if (start_date != '' && end_date !='') {
					if (start_date >= end_date && (start_date != '' && end_date !='')) {
						var data = {
							message : "查询的开始时间不能大于结束时间！",
							state : "error",
						};
						ecjia.admin.showmessage(data);
						return false;
					} else {
						url += '&start_date=' + start_date + '&end_date=' +end_date
					}
				}
				
				if (refund_status != '') {
	                url += '&refund_type=' + refund_type;
	            }
                if (keywords != '') {
                    url += '&keywords=' + keywords;
                }
				
				ecjia.pjax(url);
			});
        }
    };
    
    app.payrecord_info = {
            init: function () {
                //平台进行打款
                $('.confirm_change_status').on('click', function(e) {
                	e.preventDefault();
    				var $this = $(this);
    				var url = $this.attr('data-href');
    				var type = $this.attr('data-type');
    				var action_note = $("#action_note").val();
    				var refund_id = $("#refund_id").val();
    				var option = {'type' : type, 'action_note' : action_note,'refund_id' : refund_id};
    				$.post(url, option, function(data){
    					ecjia.admin.showmessage(data);
    				})
    			});
            },
        };
    
})(ecjia.admin, jQuery);
 
// end