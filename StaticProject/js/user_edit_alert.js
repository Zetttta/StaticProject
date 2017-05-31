function confirmUseredit(id){
  var check = confirm ("このまま実行するとユーザ情報が削除されます。");

  if(check){

    alert("削除しました。");
    location.href = "user_edit_delete.php?id=" + id;

  }else{
    alert("キャンセルしました。");
  }
}
