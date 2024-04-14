<?php
//XSS対応（ echoする場所で使用！それ以外はNG ）
function h($str)
{
  return htmlspecialchars($str, ENT_QUOTES);
}

//DB接続
function db_conn()
{
  try {
    $db_name = "gs_smcb";    //データベース名
    $db_id   = "root";      //アカウント名
    $db_pw   = "";      //パスワード：XAMPPはパスワード無しに修正してください。
    $db_host = "localhost"; //DBホスト


    //localhost以外(さくらサーバーなど)の場合参照するようにかき分ける処理。
    // Githubアップロード用に削除中。デスクトップ上の保存ID参照。
    
    return new PDO('mysql:dbname=' . $db_name . ';charset=utf8;host=' . $db_host, $db_id, $db_pw);
  } catch (PDOException $e) {
    exit('DB Connection Error:' . $e->getMessage());
  }
}

//SQLエラー
function sql_error1($stmt)
{
  //execute（SQL実行時にエラーがある場合）
  $error = $stmt->errorInfo();
  exit("SQLError:" . $error[2]);
};

//SQLエラー
function sql_error2($stmt_keireki)
{
  //execute（SQL実行時にエラーがある場合）
  $error = $stmt_keireki->errorInfo();
  exit("SQLError:" . $error[2]);
};

//リダイレクト
function redirect($file_name)
{
  header("Location: " . $file_name);
  exit();
}

//SessionCheck(スケルトン)そのユーザーがログインしているかどうかチェックする
// php04から未編集
function sschk()
{
  // chk_ssidはlogin.phpで設定したもの
  if ($_SESSION["chk_ssid"] != session_id()) {
    exit("LOGIN ERROR");
  } else {
    session_regenerate_id(true);
    $_SESSION["chk_ssid"] = session_id();
  }
}

function kanri()
{
  // chk_ssidはlogin.phpで設定したもの
  if ($_SESSION["kanri_flg"] != 1) {
    exit("管理者のみアクセスできるページです");
  }
}
