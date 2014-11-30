"use strict";

(function($) {
	
	var flag = false;

	$('.is-half').click(function (){
		var $this = $(this);
		$('.is-half').each(function() {
			var $_this = $(this);
			if($this[0] != $_this[0]) {
				$_this.removeClass('btn-primary');
				$_this.addClass('btn-default');
			}
			else {
				$_this.addClass('btn-primary');
				$_this.removeClass('btn-default');
			}
		});
	});
	var substringMatcher = function(strs) {
	  return function findMatches(q, cb) {
	    var matches, substrRegex;
	    matches = [];
	    substrRegex = new RegExp(q, 'i');
	    $.each(strs, function(i, str) {
	      if (substrRegex.test(str)) {
	        matches.push({ value: str });
	      }
	    });
	    cb(matches);
	  };
	};

	$('.box.name').typeahead(null, {
		name: 'nickname',
		displayKey: 'value',
		source: substringMatcher(nickname)
	});

	$('#form-data').submit(function(e) {
		if(flag)
			return false;
		
		flag = true;
		e.preventDefault();

		var $this = $(this);
		var data = new Array(); // class -> day
		var sum = 0;

		for(var i = 0; i < 4; i++) {
			var point_box = $('#tableBody input.point').eq(i);
			data[i] = new Object();

			data[i]['name'] = $('#tableBody input.name:not([readonly])').eq(i).val();
			
			if(data[i]['name'] == '') {
				flag = false;
				alert('이름이 비어있습니다.')
				return false;
			}

			var point = point_box.val();

			if(typeof(point) == 'string') {
				point = parseInt(point);
			}

			data[i]['point'] = point;
			sum += point;
		}

		if(sum != 0) {
			alert('합이 0이 아닙니다.[' + sum + '점 오차]');
			flag = false;
			return false;
		}

		var json_data = JSON.stringify(data);
		$.post('./submit.php', {'isHalf' : $('.is-half.btn-primary').attr('data-value'),'data' : json_data}).done(function(data) {
			if(data['message'] == 'success') {
				alert('업로드되었습니다.');
				$('input[type=text]').val('');
			}
			else {
				alert(data['reason']);
			}
			flag = false;
		}).fail(function(data) {
			alert('알 수 없는 에러가 발생하였습니다.');
			flag = false;
		});

		return false;
	});

	$(".box.point").keyup(function (e) {
		var input_val = $(this).val();
		var replaced_val = input_val.replace(/[^0-9\-]/gi,'');

		if(replaced_val != input_val)
			$(this).val(replaced_val);

		// alert(e.keyCode);
        if ($.inArray(e.keyCode, [0, 46, 8, 9, 27, 13, 110, 190, 189]) !== -1 ||
             // Allow: Ctrl+A
            (e.keyCode == 65 && e.ctrlKey === true) || 
             // Allow: home, end, left, right
            (e.keyCode >= 35 && e.keyCode <= 39)) {
                 return;
        }
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });

    $('.box.point').focusout(function() {
    	var empty_point_input = null;
    	var is_one_input_left = true;
    	var point_sum = 0;

    	$(".box.point").each(function() {
    		var $this = $(this);

    		if($this.val().length == 0 && empty_point_input == null) {
    			empty_point_input = $(this);
    			return true;
    		}
    		else if($this.val().length == 0){
    			is_one_input_left = false;
    			return false;
    		}

    		point_sum += parseInt($this.val());
    	});

    	if(is_one_input_left && empty_point_input) {
    		empty_point_input.val(-1 * point_sum);
    	}
    })
})(jQuery);