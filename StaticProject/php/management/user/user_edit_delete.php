<?php
/*
 * このファイルの概要説明：削除したいユーザの非公開フラグをtrueに変える
 * このファイルの詳細説明：ユーザ編集画面で削除ボタンを押下されるとこの画面に遷移し、
 　　　　　　　　　　　　　削除フラグをtrueに変える処理が行われる。
 *
 * システム名：ユーザーの削除(非公開フラグへ変更)
 * 作成者：坂田恵未
 * 作成日：2017/5/23
 * 最終更新日：2017/5/23
 * レビュー担当者：
 * レビュー日：
 * バージョン：1.0
*/

//DBに接続します。
require_once("../../../function/database_session.php");
$dbh=connectDB();
$user = getUser();
$manager = getManager();
loginIdentify();
$manager_decision = Manager($user);


$sql ="UPDATE drag_db.m_user ";
$sql.="SET user_delete=true, user_id=:user_edit_id ";
$sql.="WHERE user_id=:user_edit_id and user_delete=false ";
$sth =$dbh->prepare($sql);

$sth->bindValue(":user_edit_id",$_GET["id"]);


$sth->execute();

header("Location: user_select.php");
exit;
