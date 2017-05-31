/**
 * このファイルの概要説明
 * headMenuの展開処理
 * 
 * このファイルの詳細説明
 * マウスを重ねた際にメニュー内容を展開、離れた際に格納する
 * それぞれのメニューにマウスを重ねた際に背景色を変化させる
 *
 * システム名：    headMenu用JavaScript
 * 作成者：        伊藤尚輝
 * 作成日：        2017/05/18/14:00
 * 最終更新日：    2017/05/18/17:00
 * レビュー担当者：
 * レビュー日：    
 * バージョン：    
 */


$(function() {

    // マウスホバー時メニュー展開処理
    $('.menuRange').hover(function() {
        // メニューを展開
        $("#headMenuMypage").animate({'top':'25px'},200);
        // 管理者でない場合はManagementの処理を飛ばして、Logoutの表示位置を変えている
        $("#headMenuLogout").animate({'top':'50px'},200);
    // マウスが離れた時の処理
    }, function() {
        // メニューを格納
        $("#headMenuMypage").animate({'top':'0px'},200);
        // 管理者でない場合はManagementの処理を飛ばして、Logoutの表示位置を変えている
        $("#headMenuManagement").animate({'top':'0px'},200);
        $("#headMenuLogout").animate({'top':'0px'},200);
    });
    
    // マウスホバー時背景色変更処理
    // マイページ
    $('#headMenuMypage').hover(function() {
        // 色を変更
        $("#headMenuMypage").css('background', '#8DC1DD');
    // マウスが離れた時の処理
    }, function() {
        // 色を戻す
        $("#headMenuMypage").css('background', '');
    });
    // ログアウト
    $('#headMenuLogout').hover(function() {
        // 色を変更
        $("#headMenuLogout").css('background', '#8DC1DD');
    // マウスが離れた時の処理
    }, function() {
        // 色を戻す
        $("#headMenuLogout").css('background', '');
    });
});