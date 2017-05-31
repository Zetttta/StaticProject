/**
 * ���̃t�@�C���̊T�v����
 *�f�[�^�x�[�X
 * ���̃t�@�C���̏ڍא���
 *
 * �V�X�e�����FDB
 * �쐬�ҁF���C�m��
 * �쐬���F5/18
 * �ŏI�X�V���F
 * ���r���[�S���ҁF
 * ���r���[���F
 * �o�[�W�����F0.1
 */
/* DB�쐬 */
DROP DATABASE IF EXISTS drag_db;
CREATE DATABASE drag_db CHARACTER SET sjis COLLATE sjis_japanese_ci;

/* ���[�U���쐬 */
CREATE USER trainee@localhost;

/* �����t�^ */
GRANT ALL PRIVILEGES ON drag_db.* TO trainee IDENTIFIED BY 'password';

/* AUTOCOMMIT���� */
SET AUTOCOMMIT=0;

/* DB�I�� */
USE drag_db;


/* �X�܃}�X�^�쐬 */
CREATE TABLE drag_db.m_store
( 
	store_id   INT(10) PRIMARY KEY NOT NULL,/* �����X��ID */
	store_name VARCHAR(20)NOT NULL,/* �X�ܖ� */
	store_address VARCHAR(100)NOT NULL,/* �Z�� */
	store_phone VARCHAR(20)NOT NULL,/* �d�b�ԍ� */
	store_delete BOOLEAN/* �폜�t���O */
)ENGINE = INNODB;
/* �X���b�h�}�X�^�쐬 */
CREATE TABLE drag_db.m_thread
( 
	thread_id   INT(10) PRIMARY KEY NOT NULL,/* �X���b�hID */
	thread_title VARCHAR(50) NOT NULL,/* �X���b�h�^�C�g�� */
	thread_fade BOOLEAN,/* ����J�t���O */
	thread_delete BOOLEAN  /* �폜�t���O */
)ENGINE = INNODB;

/*���m�点�}�X�^*/
CREATE TABLE drag_db.m_notice
(
	notice_id INT PRIMARY KEY NOT NULL,-- ���m�点ID
	notice_title VARCHAR(50) NOT NULL,/* ���m�点���� */
	notice_start DATETIME(6),-- �f�ڊJ�n
	notice_end DATETIME(6),-- �f�ڏI��
  notice_content VARCHAR(1000) NOT NULL,-- ���m�点���e
  notice_delete BOOLEAN -- �폜�t���O
)ENGINE = INNODB;

/*��E�}�X�^*/
CREATE TABLE drag_db.m_post
(
	post_id INT(20) PRIMARY KEY NOT NULL,-- ��EID
	post_name CHAR(20) NOT NULL,-- ��E��
	post_delete BOOLEAN-- �폜�t���O
)ENGINE = INNODB;

/*����}�X�^*/
CREATE TABLE drag_db.m_corner
(
	corner_id INT(20) PRIMARY KEY NOT NULL,-- ����ID
	corner_name CHAR(20) NOT NULL,-- ���ꖼ
	corner_delete BOOLEAN-- �폜�t���O
)ENGINE = INNODB;
/* ���[�U�}�X�^�쐬 */
CREATE TABLE drag_db.m_user
( 
	user_id   CHAR(10) PRIMARY KEY NOT NULL,/* ���[�UID */
	user_pw   CHAR(20) NOT NULL,/* �p�X���[�h */
	user_email  VARCHAR(50) NOT NULL,/* ���[���A�h���X */
	user_name   VARCHAR(20) NOT NULL,/* ���O */
	store_id   INT(10) NOT NULL,/* �����X��ID */
	user_profile   VARCHAR(300),/* ���[�U�v���t�@�C�� */
	user_manager   BOOLEAN,/* �Ǘ��҃t���O */
	user_delete   BOOLEAN,/* �폜�t���O */
	corner_id   INT(20) NOT NULL,/* �����ID */
	post_id INT(20) NOT NULL,/* ��EID */
	user_right VARCHAR(10) NOT NULL,/* ���i */
	FOREIGN KEY(store_id) REFERENCES m_store(store_id),
	FOREIGN KEY(corner_id) REFERENCES m_corner(corner_id),
	FOREIGN KEY(post_id) REFERENCES m_post(post_id)
)ENGINE = INNODB;
/* ���X�|���X�}�X�^�쐬 */
CREATE TABLE drag_db.m_response
( 
	response_id INT(10) NOT NULL,/* ���X�|���XID */
	thread_id   INT(10) NOT NULL,/* �X���b�hID */
	user_id   CHAR(10) NOT NULL,/* �쐬��(���[�UID) */
	response_date DATETIME(6) NOT NULL,/* �쐬���� */
	response_inner VARCHAR(1500) NOT NULL,/* ���X�|���X���e */
	response_delete BOOLEAN, /* �폜�t���O */
	PRIMARY KEY(thread_id,response_id),
	FOREIGN KEY(thread_id) REFERENCES m_thread(thread_id),
	FOREIGN KEY(user_id) REFERENCES m_user(user_id)
	
)ENGINE = INNODB;
/*�Q���҃}�X�^*/
CREATE TABLE drag_db.m_entry
(
	entry_id INT(100) PRIMARY KEY NOT NULL,-- �Q����ID
	user_id CHAR(10) NOT NULL,-- �Q���Ж��i���[�UID�j
	thread_id INT(10)NOT NULL,/* �X���b�hID */
	entry_delete BOOLEAN,-- �폜�t���O
	FOREIGN KEY(thread_id) REFERENCES m_thread(thread_id),
  FOREIGN KEY(user_id) REFERENCES drag_db.m_user(user_id)
)ENGINE = INNODB;

/*GJ�}�X�^*/
CREATE TABLE drag_db.m_gj
(
	gj_id INT(10) PRIMARY KEY NOT NULL,/* GJID */
	response_id INT(10) NOT NULL,/* ���X�|���XID */
	thread_id INT(10) NOT NULL,/* �X���b�hID */
	user_id   CHAR(10) NOT NULL,/* ���[�UID */
	FOREIGN KEY (thread_id,response_id) REFERENCES m_response(thread_id,response_id),
	FOREIGN KEY(user_id) REFERENCES m_user(user_id)
)ENGINE = INNODB;

commit;

 