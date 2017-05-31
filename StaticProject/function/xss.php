<!--
 このファイルの概要説明
 XSS攻撃対策用関数
 
 このファイルの詳細説明
 XSS攻撃対策として、GET&POSTやデータベース等から受け取った値をechoする場合はこの関数を使用し、
 phでタグ等を無効化し出力する。
 
 講義で提示されたものを参考にしました。
 
 システム名：    XSS攻撃対策用関数
 作成者：        伊藤尚輝
 作成日：        2017/05/19/9:44
 最終更新日：    2017/05/19/9:44
 レビュー担当者：
 レビュー日：    
 バージョン：    
-->


<?php
    // XSS対策としてhtmlspecialcharsをかける関数
    // 戻り値としてエスケープ済みの文字列を返します
    function h($str)
    {
        return nl2br(htmlspecialchars($str, ENT_QUOTES, "UTF-8"));
    }

    // XSS対策としてhtmlspecialcharsをかけ、それをprintする関数
    // 戻り値はありません
    function ph($str)
    {
        echo h($str);
    }
