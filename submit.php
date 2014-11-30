<?php	
	require_once './idiorm.config.php';

	header('Content-Type: application/json');

	function clearTag($data) {
			// Fix &entity\n;
			$data = str_replace(array('&amp;','&lt;','&gt;'), array('&amp;amp;','&amp;lt;','&amp;gt;'), $data);
			$data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
			$data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
			$data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');

			// Remove any attribute starting with "on" or xmlns
			$data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);
	 
			// Remove javascript: and vbscript: protocols
			$data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
			$data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
			$data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);
			// Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
			$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
			$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
			$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);
	 
			// Remove namespaced elements (we do not need them)
			$data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);
			do {
					// Remove really unwanted tags
					$old_data = $data;
					$data = preg_replace_callback('#<(/*)?(applet|input|form|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', function($m){(empty($m[1]) ? '&lt;' . $m[2] . '&gt;' : '&lt;/' . $m[2] . '&gt;');}, $data);
			}

			while ($old_data !== $data);

			return $data;
	}

	$data = json_decode($_REQUEST['data']);

	if($_REQUEST['isHalf'] && !is_null($data)) {
		$ranking = ORM::for_table('mh_ranking')->create();
		$ranking->save();

		$name_array = array();
		$rank_data = array();

		for($i = 0; $i < count($data); $i++) {
			$value = $data[$i];
			$rank_data[] = $value->point + (4 - $i);
		}

		$wind_data = $rank_data;
		array_multisort($rank_data, SORT_DESC);

		for($i = 0; $i < count($rank_data); $i++) {
			$rank_value = $rank_data[$i];

			$wind_kind = array_search($rank_value, $wind_data);

			if($wind_kind == -1) {
				exit('{"message" : "error", "reason" : "심각한 오류가 발생하였습니다."}');
			}

			$value = $data[$wind_kind];

			$name = $value->name;
			
			// if(array_search($name, $name_array) != -1) {
			// 	exit('{"message" : "error", "reason" : "같은 닉네임이 존재합니다."}');
			// }

			$name_array[$i] = $name;

			if($name == '') {
				exit('{"message" : "error", "reason" : "닉네임이 비어있습니다."}');
			}

			$nickname = ORM::for_table('mh_nickname')->where('nickname', $name)->find_one();

			if($nickname == false) {
				exit('{"message" : "error", "reason" : "등록되지 않은 닉네임입니다."}');
			}

			$log = ORM::for_table('mh_log')->create();
			$log->user_id = $nickname->id;
			$log->point = $value->point;
			$log->wind_type = $wind_kind;
			$log->grade = $i;
			$log->ranking_id = $ranking->id;

			$log->save();

			$ranking->{'log_' . ($i + 1) . '_id'} = $log->id;
		}

		$ranking->is_half = $_REQUEST['isHalf'];
		$ranking->save();

		echo '{"message" : "success"}';
	}
	else {
		header('HTTP/1.1 400 Bad Request');
	}
