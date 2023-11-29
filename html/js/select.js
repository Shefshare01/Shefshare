
function fetchData(file) {
// AJAX를 이용하여 PHP 스크립트 호출
    var xhr = new XMLHttpRequest()
    xhr.onreadystatechange =
        function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
            // 성공적으로 데이터를 받아왔을 때 처리
            document.getElementById("result").innerHTML = xhr.responseText;
            }
        }
    xhr.open("POST", file, true)
    xhr.send()
}


