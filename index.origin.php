<?php
	require './idiorm.config.php';
	$mh_nickname = ORM::for_table('mh_nickname')->select('nickname')->find_many();
	$mh_nickname_array = array();

	foreach ($mh_nickname as $value) {
		$mh_nickname_array[] = $value->nickname;
	}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
	<meta charset="UTF-8">
	<title>마작입력기</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
	<meta name=" apple-mobile-web-app-title" content="마작 입력기">

	<link rel="stylesheet" href="./stylesheets/style.min.css">
	<link rel="stylesheet" href="./stylesheets/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="./stylesheets/addtohomescreen.min.css">
	<link rel="apple-touch-icon" href="./imgs/icon.png">

	<script src="./javascripts/jquery.js"></script>	
	<!--[if lt IE 8]>
	    <script src="http://www.json.org/json2.js"></script>
	<![endif]-->
	<script src="./javascripts/bootstrap.min.js"></script>
	<script src="./javascripts/addtohomescreen.min.js"></script>

	<script>addToHomescreen();var nickname = <?= json_encode($mh_nickname_array)?>;</script>
</head>
<body>
	<div id="contents">
	<noscript>
		스크립트 사용을 허가해주세요.
	</noscript>
	<form id="form-data">
		<h2 style="margin-top: 0px;text-align: center;">점수 입력기</h2>
		<hr>
		<div class="btn-group btn-group-justified">
			<div class="btn-group">
				<button type="button" class="is-half btn btn-default" data-value="east">동장</button>
			</div>
			<div class="btn-group">
				<button type="button" class="is-half btn btn-primary" data-value="half">반장</button>
			</div>
			<div class="btn-group">
				<button type="button" class="is-half btn btn-default" data-value="all">전장</button>
			</div>
		</div>
		<hr>
		<div style="margin: 0 auto;">
			<table id="time-table" class="table table-bordered">
				<thead>
					<tr>
						<td class="seat">자리</td>
						<td class="nick">닉네임</td>
						<td class="point">점수</td>
					</tr>
				</thead>
				<tbody id="tableBody">
					<tr>
						<td class="seat">첫동</td>
						<td class="nick"><input type="text" name="nick_name_1" class="box name" autocomplete="off" data-index="0"></td>
						<td class="nick"><input type="text" name="point_1" class="box point" autocomplete="off" data-index="0"></td>
					</tr>
					<tr>
						<td class="seat">첫남</td>
						<td class="nick"><input type="text" name="nick_name_2" class="box name" autocomplete="off" data-index="1"></td>
						<td class="nick"><input type="text" name="point_2" class="box point" autocomplete="off" data-index="1"></td>
					</tr>
					<tr>
						<td class="seat">첫서</td>
						<td class="nick"><input type="text" name="nick_name_3" class="box name" autocomplete="off" data-index="2"></td>
						<td class="nick"><input type="text" name="point_3" class="box point" autocomplete="off" data-index="2"></td>
					</tr>
					<tr>
						<td class="seat">첫북</td>
						<td class="nick"><input type="text" name="nick_name_4" class="box name" autocomplete="off" data-index="3"></td>
						<td class="nick"><input type="text" name="point_4" class="box point" autocomplete="off" data-index="3"></td>
					</tr>
				</tbody>
			</table>
		</div>
		<button type="submit" class="btn btn-primary btn-submit"><span class="glyphicon glyphicon-floppy-disk"></span>&nbsp;보내기</button>
	</form>
	<footer id="copyright">
		Developed by <a href="http://github.com/Luavis">Luavis</a> for PZGS
	</footer>
	</div>
	<script src="./javascripts/typeahead.jquery.min.js"></script>
	<script src="./javascripts/app.min.js"></script>
</body>
</html>