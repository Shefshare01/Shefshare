function addAdmin(del) {
    var adminId = document.getElementById('adminId').value;
    var adminPassword = document.getElementById('adminPassword').value;


    $.ajax({
        type: 'POST',
        url : 'data_add.php',
        dataType:'text',
        data: {
            type: 'admin',
            id: adminId,
            pw: adminPassword,
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