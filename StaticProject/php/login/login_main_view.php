<!--
/**
* ログイン画面を表示する
*
* ログイン画面を表示する
* ログインが失敗した場合はエラーを表示する
* ログインが成功した場合はトップページに遷移する
*
* システム名：ログインシステム
* 作成者：朴
* 作成日：2017/05/18
* 最終更新日：2017/05/18
* レビュー担当者：
* レビュー日：
* バージョン：1.0
*/
-->
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

<title>ログイン画面</title>
<link href="../../css/login_main.css" rel="stylesheet" type="text/css" />
</head>
<body>

<div id="login_wrap">
    <form action="login_main.php" method="post">
    <span>ログイン画面</span>
    <div id="error"><span><?php print $msg;?></span></div>
    <div><input type="text" id="login_id" name="login_id" value="IDを入力してください"
      onblur="if (this.value == '') {this.value = 'IDを入力してください';}"
      onfocus="if (this.value == 'IDを入力してください') {this.value = '';}"></div>
    <div><input type="password" id="login_pass"name="login_pass" value="PASS"
      onblur="if (this.value == '') {this.value = 'PASS';}"
      onfocus="if (this.value == 'PASS') {this.value = '';}"></div>
    <input type="submit" id="login_sub">
    </form>
</div>
</body>
</html>
