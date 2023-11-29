<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>관리자 페이지</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
    <style> 
        body {
            padding: 1% 100px 100px 5%;
        }
        .logout {
        	margin-bottom:20px;
        }
	#List{
	    margin-top:20px;
	}

    </style>
</head>
<body>

<?php

include 'mysqli.php';

$mysqli = new mysqli ('db.cwrsnrlxgnde.ap-northeast-2.rds.amazonaws.com', 'root', 'port2023', 'usbdata');
$db = new MysqliDb ($mysqli);

$res = $db->rawQuery('select * from member');
$res2 = $db->rawQuery('select * from vpid');

?>


<button type="button" class="btn btn-danger logout" onclick="logout()">로그아웃</button>


<ul class="nav nav-tabs" id="myTabs">
    <li class="nav-item">
        <a class="nav-link active" id="admin-tab" data-toggle="tab" href="#admin">관리자</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="vid-tab" data-toggle="tab" href="#vid">VID/PID</a>
    </li>
</ul>



<div class="tab-content mt-2">
    <div class="tab-pane fade show active" id="admin">
        <h4>관리자 정보</h4>
        <form id="adminForm">
            <div class="form-group">
                <label for="adminId">아이디:</label>
                <input type="text" class="form-control" id="adminId" required>
            </div>
            <div class="form-group">
                <label for="adminPassword">비밀번호:</label>
                <input type="password" class="form-control" id="adminPassword" required>
            </div>
            <button type="button" class="btn btn-primary" onclick="addAdmin(0)">추가</button>
             <button type="button" class="btn btn-primary" onclick="addAdmin(1)">삭제</button>
        </form>
        <div id="List">
            <h5>관리자 목록</h5>
            <ul id="UlList">
		
		<?php 
		foreach($res as $key => $value){
			echo "<li>".$value['id']."</li>";
		}
		?>


	    </ul>
        </div>
    </div>

    <div class="tab-pane fade" id="vid">
        <h4>VID/PID 정보</h4>
        <form id="vidForm">
            <div class="form-group">
                <label for="vidValue">VID:</label>
                <input type="text" class="form-control" id="vidValue" required>
            </div>
            <div class="form-group">
                <label for="pidValue">PID:</label>
                <input type="text" class="form-control" id="pidValue" required>
            </div>
            <button type="button" class="btn btn-primary" onclick="addVidPid(0)">추가</button>
            <button type="button" class="btn btn-primary" onclick="addVidPid(1)">삭제</button>
        </form>
        <div id="List">
            <h5>VID 목록</h5>
            <ul id="vidItemList">
                <?php
                foreach($res2 as $key => $value){
                        echo "<li>vid : ".$value['vid'].", pid:".$value['pid']."</li>";
                }
                ?>

	    </ul>
        </div>
    </div>
</div>

<div class="tab-content mt-2">
    <div class="tab-pane fade show active" id="admin">
        <h4>기록</h4>
        <div id="log">
    </div>


        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script type="text/javascript" src="http://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="/js/addAdmin.js"></script>
<script>
    function log() {
        $.ajax({
            type: 'POST',
            url : 'select_log.php',
            success: function(data){
                $('#log').html(data);
                location.reload();
            }
        });
    }


    function addVidPid(del) {
        var vidValue = document.getElementById('vidValue').value;
        var pidValue = document.getElementById('pidValue').value;

	        $.ajax({
                type: 'POST',
                url : 'data_add.php',
                dataType:'text',
                data: {
                  type: 'vpid',
                  vid: vidValue,
                  pid: pidValue,
                  del_check:del,
                },
                success: function(data){
                        alert(data);
			location.reload();
                },
                error:function(){
                        alert('실패');
                }
        });

    }

    function logout() {
            // 메시지 창 열기
            var result = window.confirm('로그아웃 하시겠습니까?');

            // 확인 버튼이 눌렸을 때 함수 실행
            if (result) {
                $.ajax({
                    type: 'POST',
                    url : 'logout.php',
                    success: function(){
                        location.replace('login.php');
                    }
                });
            }
    }

    var previousValue = 1;
    setInterval( function() {
        // 현재 값 가져오기
        var currentValue = 1;

        // 이전 값과 현재 값 비교
        if (currentValue !== previousValue) {
            // 값이 변했을 때 경고창 띄우기
            alert('변수가 변경되었습니다!');

            // 현재 값을 이전 값으로 업데이트
            previousValue = currentValue;
        }
    }, 1000)
</script>
</body>
</html>
