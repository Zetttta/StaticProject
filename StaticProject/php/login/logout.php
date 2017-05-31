<?php
/**
* ログアウト
*
* ログアウトをします。
* ログオウトのボタンを押せばconfirmから確認を要求します。
* 確認をすればこのページに飛ばしてログアウトします。
* 取り消しすれば元の画面に戻ります。
*
*
* システム名：ログアウト
* 作成者：朴
* 作成日：2017/05/24
* 最終更新日：2017/05/24
* レビュー担当者：
* レビュー日：
* バージョン：1.0
*/
require_once("../../function/database_session.php");//関数を読み込んでDBとsessionを読み込みます。



$_SESSION = array();
session_destroy();
header( "Location:login_main.php" );
