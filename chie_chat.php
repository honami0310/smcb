<?php
//0. SESSION開始！！
session_start();
// userのpersonal id取得
$realPersonalid = $_SESSION["personalid"];
$realUsername = $_SESSION["name"];
$kanrisha = $_SESSION["kanri_flg"];

require_once('funcs.php'); //php02発展の記載。includeより一般的、間違ってると動かなくなる
$pdo = db_conn();      //DB接続関数
// funcs.phpで作ったログインしてないとselect.phpが開けないコード
sschk();

// postされた該当のthred idを取得
$threadid = $_GET['threadid'];

// SMCB評価の際にpostするためループ外で名前を定義
$responsername ="";
$responserid = "";

//2-1．返信送信時のpost内容が重複しないように該当のスレッドの最後の情報だけSQLから取得(全部にすると全部の内容がpostされてしまう)
$stmtlast = $pdo->prepare("SELECT * FROM chiebukuro WHERE threadid = :threadid ORDER BY id DESC LIMIT 1");
// PDO::PARAM_STRがSTRかINTかその他かちゃんと確認！エラーになると気づきずらい
$stmtlast->bindValue(':threadid', $threadid, PDO::PARAM_STR);
$statuslast = $stmtlast->execute();


//2-2．データ表示
$threadname = "";
if ($statuslast == false) {
  //SQLエラーの場合
  sql_error1($stmt);
} else {
  while ($reslast = $stmtlast->fetch(PDO::FETCH_ASSOC)) {
    $threadname .= h($reslast['threadname']);
    // $responsername .= h($reslast['responsername']);
    // $responserid .= h($reslast['responserid']);
  };
};

//4-1．ベストアンサーだけを取得(回答の一番上に表示)、SMBC評価時にBAの回答者情報に上書きするため3より先に記載
$stmtba = $pdo->prepare("SELECT * FROM chiebukuro WHERE threadid = :threadid AND best = 1");
$stmtba->bindValue(':threadid', $threadid, PDO::PARAM_STR);
$statusba = $stmtba->execute();

//4-2．データ表示
$viewba = "";
$resetId ="";

if ($statusba == false) {
  //SQLエラーの場合
  sql_error1($stmt);
} else {
  while ($resba = $stmtba->fetch(PDO::FETCH_ASSOC)) {
    // baを変える用に$をとっておく
    $resetId .= h($resba['id']);
    // SMCB評価入力時、回答者が未設定の場合BAの人とするために$をとっておく
    $responserid .= h($resba['personalid']);
    $responsername .= h($resba['name']);
    // それ以外、見せる用
    $viewba .= '<tr><td>' . h($resba['daytime']) . '</td>';
    $viewba .= '<td>' . h($resba['name']) . '</td></tr>';
    $viewba .= '<tr><td colspan="2" style=" width: 75%;
    overflow-wrap: break-word;
    word-wrap: break-word; /* 古いブラウザ用のバージョン */
    text-align: right;">' . h($resba['comment']) . '</td></tr>';
  };
};

//3-1．該当のスレッドのQだけを取得(一番上に表示)
$stmtq = $pdo->prepare("SELECT * FROM chiebukuro WHERE threadid = :threadid AND qa = 'q'");
// PDO::PARAM_STRがSTRかINTかその他かちゃんと確認！エラーになると気づきずらい
$stmtq->bindValue(':threadid', $threadid, PDO::PARAM_STR);
$statusq = $stmtq->execute();

//３-2．データ表示
$requestid = "";
$daytime = "";
$requestname = "";
$responsernameToView = "";
$qComment = "";
$conclude = "";

if ($statusq == false) {
  //SQLエラーの場合
  sql_error1($stmt);
} else {
  while ($resq = $stmtq->fetch(PDO::FETCH_ASSOC)) {
    $requestid .= h($resq['personalid']);
    $daytime.= h($resq['daytime']);
    $requestname.= h($resq['name']);
    if ($resq['responsername']!==null){
    $responsernameToView .= h($resq['responsername']);
    }
    $qComment .= h($resq['comment']);
    if ($requestid == $realPersonalid) {
    $conclude .= '<a href="evaluate_smcb.php?id=' . h($resq["threadid"]) . '&responserid=' . h($responserid) . '&responsername=' . h($responsername) . '">[知恵袋を完了してSMBの結果を入力]</a>';};
  };
};

//5．該当のスレッドをSQLから取得(Qと、BA以外)
$stmt = $pdo->prepare("SELECT * FROM chiebukuro WHERE threadid = :threadid AND qa != 'q'");
// PDO::PARAM_STRがSTRかINTかその他かちゃんと確認！エラーになると気づきずらい
$stmt->bindValue(':threadid', $threadid, PDO::PARAM_STR);
$status = $stmt->execute();

//３．データ表示
$view = "";
if ($status == false) {
  //SQLエラーの場合
  sql_error1($stmt);
} else {
  while ($res = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $view .= '<tr><td colspan="2">' . h($res['daytime']) . '</td>';
    $view .= '<td>' . h($res['name']) . '</td></tr>';
    $view .= '<tr><td colspan="3" class="comment" style=" width: 75%;
    overflow-wrap: break-word;
    word-wrap: break-word; /* 古いブラウザ用のバージョン */
    text-align: right;">' . h($res['comment']) . '</td>';
    if ($requestid == $_SESSION["personalid"] || $kanrisha=="1") {
      $view .= '<td><a href="update_ba.php? chooseBa=' .h($res["id"]).' &resetId=' .$resetId. ' &threadid=' .$threadid. '">ベストアンサーにする</a></td>';
  }
  if ($requestid == $_SESSION["personalid"] || $kanrisha=="1") {
    $view .= '<td><a href="delete_chat.php? id=' .h($res["id"]).' &threadid=' .$threadid. '">削除</a></td></tr>';
}else{
    $view .= '</tr>';
  };
  };
};
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <link rel="stylesheet" href="css/style.css">
  <title>[<?php echo $threadname ?>]についての知恵袋</title>
  <style>
    div {
      padding: 10px;
      font-size: 16px;
    }
  </style>
</head>

<body id="main">
  <!-- Head[Start] -->

  <header>
  <div class="space"></div>
    <div class="obi">[<?php echo $threadname ?>]についての知恵袋</div>
    <div id="menu"><?php if ($_SESSION["kanri_flg"] != 1) : ?>
    <?php include("menu.php"); ?>
    <?php else : ?>
      <?php include("kanrisha_menu.php"); ?>
    <?php endif; ?></div>
  </header>
  <!-- Head[End] -->

  <!-- Main[Start] -->

  <div class="chiehaikei">
    <table>
      <tr><td class="blue">質問/提案日時：</td><td><?php echo $daytime ?></td></tr>
      <tr><td class="blue">質問/提案者：</td><td><?php echo $requestname ?></td></tr>
      <tr><td class="blue">質問/提案内容：</td><td><?php echo $qComment ?></td></tr>
      <tr><td class="blue">回答者/ベストアンサー：</td><td id="responsernameToView"><?php echo $responsernameToView ?></td></tr>

      <tr><td colspan="2"><?php echo $conclude ?></td></tr>  
    </table>
  </div>
  <div class="chiehaikei">
  <div id="ba">
    <div class="chieobi_ba">ベストアンサー</div><br>
  <div class="space"></div>
    <table  class="chiekaitou">
      <tr><td class="blue">回答時間</td><td class="blue">氏名</td></tr>
    <?= $viewba ?>
    </table>
  </div>
  </div>
    <!-- phpで設定したviewを引っ張る -->
    <div class="chiehaikei">
    <div class="chieobi_kaitou">回答</div><br>
  <div class="space"></div>
    <table class="chie_tbody">
    <tr><td colspan="2" class="chieblue1">送信日時</td><td class="blue">氏名</td></tr>
      <?= $view ?>  
      </table>
  <div class="space"></div>
    </div>
    <div>
    <table class="henshin" >
      <form method="post" action="insert_chie.php">
        <tr>
          <td hidden><input name="threadid" value="<?= $threadid ?>" readonly></td>
        </tr>
        <tr>
          <td hidden><input name="threadname" value="<?= $threadname ?>" readonly></td>
        </tr>
        <tr>
          <td hidden><input name="personalid" value="<?= $realPersonalid ?>" readonly></td>
        </tr>
        <tr>
          <td hidden><input name="name" value="<?= $realUsername ?>" readonly></td>
        </tr>
        <tr>
          <td hidden><input name="qa" value="a" readonly></td>
        </tr>
        <tr>
          <td colspan="2" ><textarea name="comment" placeholder="返信を入力" class="comment"></textarea></td>
        </tr>
        <tr>
          <td><input type="submit" value="送信"></td>
        </tr>

      </form>
      </table>
  </div>

  <!-- Main[End] -->
  <ul>
  <?php if ($_SESSION["kanri_flg"] != 1) : ?>
      <li><a href="mypage.php" >マイページに戻る</a></li>
    <?php else : ?>
      <li><a href="kanrisha_mypage.php" >管理者ページに移動する</a></li>
    <?php endif; ?>
  </ul>

  <script>
    $(document).ready(function(){
      if ($.trim("<?= $viewba ?>") !==''){
      $('#ba').show();
      }
    });

    $(document).ready(function(){
      if ("<?= $responsername; ?>" !==""){
        $('#responsernameToView').text("<?= $responsername ?>");
      };
    });

  </script>
</body>

</html>