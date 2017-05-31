/**
 * このファイルの概要説明
 *データベース
 * このファイルの詳細説明
 *
 * システム名：DB
 * 作成者：内海洋樹
 * 作成日：5/18
 * 最終更新日：
 * レビュー担当者：
 * レビュー日：
 * バージョン：0.1
 */
/* DB作成 */
DROP DATABASE IF EXISTS drag_db;
CREATE DATABASE drag_db CHARACTER SET sjis COLLATE sjis_japanese_ci;

/* ユーザを作成 */
CREATE USER trainee@localhost;

/* 権限付与 */
GRANT ALL PRIVILEGES ON drag_db.* TO trainee IDENTIFIED BY 'password';

/* AUTOCOMMIT無効 */
SET AUTOCOMMIT=0;

/* DB選択 */
USE drag_db;


/* 店舗マスタ作成 */
CREATE TABLE drag_db.m_store
( 
	store_id   INT(10) PRIMARY KEY NOT NULL,/* 所属店舗ID */
	store_name VARCHAR(20)NOT NULL,/* 店舗名 */
	store_address VARCHAR(100)NOT NULL,/* 住所 */
	store_phone VARCHAR(20)NOT NULL,/* 電話番号 */
	store_delete BOOLEAN/* 削除フラグ */
)ENGINE = INNODB;
/* スレッドマスタ作成 */
CREATE TABLE drag_db.m_thread
( 
	thread_id   INT(10) PRIMARY KEY NOT NULL,/* スレッドID */
	thread_title VARCHAR(50) NOT NULL,/* スレッドタイトル */
	thread_fade BOOLEAN,/* 非公開フラグ */
	thread_delete BOOLEAN  /* 削除フラグ */
)ENGINE = INNODB;

/*お知らせマスタ*/
CREATE TABLE drag_db.m_notice
(
	notice_id INT PRIMARY KEY NOT NULL,-- お知らせID
	notice_title VARCHAR(50) NOT NULL,/* お知らせ件名 */
	notice_start DATETIME(6),-- 掲載開始
	notice_end DATETIME(6),-- 掲載終了
  notice_content VARCHAR(1000) NOT NULL,-- お知らせ内容
  notice_delete BOOLEAN -- 削除フラグ
)ENGINE = INNODB;

/*役職マスタ*/
CREATE TABLE drag_db.m_post
(
	post_id INT(20) PRIMARY KEY NOT NULL,-- 役職ID
	post_name CHAR(20) NOT NULL,-- 役職名
	post_delete BOOLEAN-- 削除フラグ
)ENGINE = INNODB;

/*売場マスタ*/
CREATE TABLE drag_db.m_corner
(
	corner_id INT(20) PRIMARY KEY NOT NULL,-- 売場ID
	corner_name CHAR(20) NOT NULL,-- 売場名
	corner_delete BOOLEAN-- 削除フラグ
)ENGINE = INNODB;
/* ユーザマスタ作成 */
CREATE TABLE drag_db.m_user
( 
	user_id   CHAR(10) PRIMARY KEY NOT NULL,/* ユーザID */
	user_pw   CHAR(20) NOT NULL,/* パスワード */
	user_email  VARCHAR(50) NOT NULL,/* メールアドレス */
	user_name   VARCHAR(20) NOT NULL,/* 名前 */
	store_id   INT(10) NOT NULL,/* 所属店舗ID */
	user_profile   VARCHAR(300),/* ユーザプロファイル */
	user_manager   BOOLEAN,/* 管理者フラグ */
	user_delete   BOOLEAN,/* 削除フラグ */
	corner_id   INT(20) NOT NULL,/* 売り場ID */
	post_id INT(20) NOT NULL,/* 役職ID */
	user_right VARCHAR(10) NOT NULL,/* 資格 */
	FOREIGN KEY(store_id) REFERENCES m_store(store_id),
	FOREIGN KEY(corner_id) REFERENCES m_corner(corner_id),
	FOREIGN KEY(post_id) REFERENCES m_post(post_id)
)ENGINE = INNODB;
/* レスポンスマスタ作成 */
CREATE TABLE drag_db.m_response
( 
	response_id INT(10) NOT NULL,/* レスポンスID */
	thread_id   INT(10) NOT NULL,/* スレッドID */
	user_id   CHAR(10) NOT NULL,/* 作成者(ユーザID) */
	response_date DATETIME(6) NOT NULL,/* 作成日時 */
	response_inner VARCHAR(1500) NOT NULL,/* レスポンス内容 */
	response_delete BOOLEAN, /* 削除フラグ */
	PRIMARY KEY(thread_id,response_id),
	FOREIGN KEY(thread_id) REFERENCES m_thread(thread_id),
	FOREIGN KEY(user_id) REFERENCES m_user(user_id)
	
)ENGINE = INNODB;
/*参加者マスタ*/
CREATE TABLE drag_db.m_entry
(
	entry_id INT(100) PRIMARY KEY NOT NULL,-- 参加者ID
	user_id CHAR(10) NOT NULL,-- 参加社名（ユーザID）
	thread_id INT(10)NOT NULL,/* スレッドID */
	entry_delete BOOLEAN,-- 削除フラグ
	FOREIGN KEY(thread_id) REFERENCES m_thread(thread_id),
  FOREIGN KEY(user_id) REFERENCES drag_db.m_user(user_id)
)ENGINE = INNODB;

/*GJマスタ*/
CREATE TABLE drag_db.m_gj
(
	gj_id INT(10) PRIMARY KEY NOT NULL,/* GJID */
	response_id INT(10) NOT NULL,/* レスポンスID */
	thread_id INT(10) NOT NULL,/* スレッドID */
	user_id   CHAR(10) NOT NULL,/* ユーザID */
	FOREIGN KEY (thread_id,response_id) REFERENCES m_response(thread_id,response_id),
	FOREIGN KEY(user_id) REFERENCES m_user(user_id)
)ENGINE = INNODB;

commit;

 