<?php
echo "done";
// 0 session開始、
// 1 DB接続
// 3-1 ログインした人の電話帳
// 3-2. ログインした人の経歴、SMB情報
// 3-3. SMB実績カウント
// 3-4 回答したSMB一覧
// 3-5 質問したSMB一覧

//0. SESSION開始！！
session_start();

require_once('funcs.php');
//1. DB接続関数
$pdo = db_conn();
// funcs.phpで作ったログインしてないとselect.phpが開けないコード
sschk();

// sessionに入れたpersonal idを取得
$personalid = $_SESSION["personalid"];

//3-1. ログインした人の電話帳情報部分のSQL(gs_personal)を引っ張る
// `personalid` = :personalid
$stmt = $pdo->prepare("SELECT * FROM gs_personal WHERE `personalid` = :personalid");
// PDO::PARAM_STRがSTRかINTかその他かちゃんと確認！エラーになると気づきずらい
$stmt->bindValue(':personalid', $personalid, PDO::PARAM_STR);
$status_personal = $stmt->execute();

//3-1．データ表示
$view = "";
$myname = "";

if ($status_personal === false) {
  //SQLエラーの場合。funcsから引用
  sql_error1($stmt);
} else {
  //Selectデータの数だけ自動でループしてくれる
  //FETCH_ASSOC=http://php.net/manual/ja/pdostatement.fetch.php
  // $resはすべて入ってる(どこで設定してるか再確認)
  // fetchとするとぐるぐる回して情報引っ張ってるらしい、詳細不明
  while ($res = $stmt->fetch(PDO::FETCH_ASSOC)) {
    // 下記htmlのtableの中身
    // hでfuncs.php適用して守ってる
    // 電話帳から引っ張ってくる前提なのでこの部分は編集できない設定にする(未編集)
    $view .= '<tr><td rowspan="3" width=20%><img src="image/' .h($res['icon']). '.png" alt="header" id="icon"></td>';
    $view .= '<td class="blue" width=30%>氏名：</td><td width=50%>' . h($res['name']) . '</td></tr>';
    $view .= '<tr><td class="blue">Personal ID：</td><td>' . h($res['personalid']) . '</td></tr>';
    $view .= '<tr><td class="blue">役職：</td><td>' . h($res['title']) . '</td></tr>';
    $view .= '<tr><td class="blue" style="display:none;">ID：</td><td style="display:none;">' . h($res['id']) . '</td></tr>';

    $view .= '<tr><td class="blue">所属：</td><td colspan="2">' . h($res['department']) . '</td></tr>';
    $view .= '<tr><td class="blue">電話番号：</td><td colspan="2">' . h($res['tel']) . '</td></tr>';
    $view .= '<tr><td class="blue">emial：</td><td colspan="2"><a href="mailto:' . h($res['email']) . '">' . h($res['email']) . '</a></td>';
    $view .= '<tr><td class="blue">Teams chat：</td><td colspan="2"><a href="https://teams.microsoft.com/l/chat/0/0?users= ' . h($res['email']) . '">Teams</a></td></tr>';

    $myname .= h($res['name']);
  };
};

//3-2. ログインした人の経歴、SMB情報のSQL(gs_keireki)を引っ張る
// 10人分のテーブルを作るのでテーブル名にpersonal IDを入れるけど実用するときはもっといい方法あるか要確認
if ($personalid !== null) {
  $stmt_keireki = $pdo->prepare("SELECT * FROM gs_keireki WHERE `personalid` = :personalid");
$stmt_keireki->bindValue(':personalid', $personalid, PDO::PARAM_STR);
  $status_keireki = $stmt_keireki->execute();
} else {
  echo "cannot find personal ID";
};

//3-2．データ表示
$viewkeireki = "";
if ($status_keireki == false) {
  // SQLエラーの場合の処理
  sql_error2($stmt_keireki);
} else {
  // データの取得が成功した場合の処理
  while ($reskeireki = $stmt_keireki->fetch(PDO::FETCH_ASSOC)) {
    $viewkeireki .= '<td style="display:none;">' . h($reskeireki['id']) . '</td>';
    $viewkeireki .= '<td>' . h($reskeireki['kaishi']) . '</td>';
    $viewkeireki .= '<td>' . h($reskeireki['owari']) . '</td>';
    $viewkeireki .= '<td>' . h($reskeireki['belongs']) . '</td>';
    $viewkeireki .= '<td>' . h($reskeireki['naiyou']) . '</td>';
    $viewkeireki .= '<td>' . h($reskeireki['item1']) . '</td>';
    $viewkeireki .= '<td>' . h($reskeireki['item2']) . '</td>';
    $viewkeireki .= '<td>' . h($reskeireki['item3']) . '</td>';
    $viewkeireki .= '<td><a href="detail_keireki.php? id=' . h($reskeireki["id"]) . '">';
    $viewkeireki .= '[更新]</td>';
    $viewkeireki .= '<td><a href="delete_keireki.php?id=' . h($reskeireki["id"]) . '">';
    $viewkeireki .= '[削除]</td></tr>';
  };
};

// 3-3. SMB実績のカウント
// 3-3-1. 回答数
$view_a_count = "";
$a_count_sql = "SELECT COUNT(*) FROM gs_smcb_list WHERE responserid = :personalid";
$stmt_a_count = $pdo->prepare($a_count_sql); // prepareメソッドを使用してステートメントを準備する
$stmt_a_count->bindValue(':personalid', $personalid, PDO::PARAM_STR); // bindValueを使用してパラメータをバインドする
$stmt_a_count->execute(); // クエリを実行する
$a_count = $stmt_a_count->fetchColumn(); // カウント結果を取得する
// 3-3-2. 質問数数
$view_q_count = "";
$q_count_sql = "SELECT COUNT(*) FROM gs_smcb_list WHERE requestid = :personalid";
$stmt_q_count = $pdo->prepare($q_count_sql); // prepareメソッドを使用してステートメントを準備する
$stmt_q_count->bindValue(':personalid', $personalid, PDO::PARAM_STR); // bindValueを使用してパラメータをバインドする
$stmt_q_count->execute(); // クエリを実行する
$q_count = $stmt_q_count->fetchColumn(); // カウント結果を取得する
// 3-3-3. star平均
$view_star_ave = "";
$star_ave_sql = "SELECT ROUND(AVG(reviewstar),1) FROM gs_smcb_list WHERE responserid = :personalid AND reviewstar IS NOT NULL";
$stmt_star_ave = $pdo->prepare($star_ave_sql); // prepareメソッドを使用してステートメントを準備する
$stmt_star_ave->bindValue(':personalid', $personalid, PDO::PARAM_STR); // bindValueを使用してパラメータをバインドする
$stmt_star_ave->execute(); // クエリを実行する
$star_ave = $stmt_star_ave->fetchColumn(); // カウント結果を取得する
// 3-3-4. 神回答数
$spstar_count = "";
$spstar_count_sql = "SELECT COUNT(*) FROM gs_smcb_list WHERE responserid = :personalid AND spstar IS NOT NULL";
$stmt_spstar_count = $pdo->prepare($spstar_count_sql); // prepareメソッドを使用してステートメントを準備する
$stmt_spstar_count->bindValue(':personalid', $personalid, PDO::PARAM_STR); // bindValueを使用してパラメータをバインドする
$stmt_spstar_count->execute(); // クエリを実行する
$spstar_count = $stmt_spstar_count->fetchColumn(); // カウント結果を取得する

if (!empty($avg_sql)) {
  $stmt_avg = $pdo->query($avg_sql);
  // 以降の処理を行います
} else {
  // $avg_sqlが空の場合の処理
};
// 3-3-5. SMBスコア(=回答数×評価+質問数/2+神評価数×10 )を出す
$view_smb_score = "";
$smb_score = number_format($a_count * $star_ave + $q_count / 2 + $spstar_count*10,1);


// 3-4. 自分の回答したSMBを引っ張る
// SQLを実行
$stmt_mysmb_a = $pdo->prepare("SELECT * FROM gs_smcb_list WHERE responserid = :personalid");
// PDO::PARAM_STRがSTRかINTかその他かちゃんと確認！エラーになると気づきずらい
$stmt_mysmb_a->bindValue(':personalid', $personalid, PDO::PARAM_STR);
$status_mysmb_a = $stmt_mysmb_a->execute();

// 3-4. 自分のSMBの表示
$view_mysmb_a = "";
$spstarToShow1 = "";
if ($status_mysmb_a == false) {
  //SQLエラーの場合
  sql_error1($stmt);
} else {
  while ($res_mysmb_a = $stmt_mysmb_a->fetch(PDO::FETCH_ASSOC)) {
    $spstarToShow1 .= $res_mysmb_a['spstar'];
    $view_mysmb_a .= '<tr><td style="display:none;">' . h($res_mysmb_a['id']) . '</td>';
    $view_mysmb_a .= '<td>' . h($res_mysmb_a['requestname']) . '</td>';
    $view_mysmb_a .= '<td>' . h($res_mysmb_a['requestid']) . '</td>';
    $view_mysmb_a .= '<td>' . h($res_mysmb_a['responsername']) . '</td>';
    $view_mysmb_a .= '<td>' . h($res_mysmb_a['responserid']) . '</td>';
    $view_mysmb_a .= '<td>' . h($res_mysmb_a['bigitem']) . '</td>';
    $view_mysmb_a .= '<td>' . h($res_mysmb_a['miditem']) . '</td>';
    $view_mysmb_a .= '<td>' . h($res_mysmb_a['title']) . '</td>';
    $view_mysmb_a .= '<td>' . h($res_mysmb_a['onoroff']) . '</td>';
    $view_mysmb_a .= '<td>' . h($res_mysmb_a['requestdaytime']) . '</td>';
    $view_mysmb_a .= '<td>' . h($res_mysmb_a['smbdaytimestart']) . '</td>';
    $view_mysmb_a .= '<td>' . h($res_mysmb_a['smbdaytimeend']) . '</td>';
    // モチベのためにstar非表示
    $view_mysmb_a .= '<td hidden>' . h($res_mysmb_a['reviewstar']) . '</td>';
    // 神回答は見せる。1の場合は★、なければ"ー"
    if ($res_mysmb_a["spstar"] == '1'){
      $view_mysmb_a .= '<td>★</td>';
    }else{
      $view_mysmb_a .= '<td>ー</td>';
    };
    $view_mysmb_a .= '<td>' . h($res_mysmb_a['reviewcomment']) . '</td>';
    $view_mysmb_a .= '<td>' . h($res_mysmb_a['item1']) . '</td>';
    $view_mysmb_a .= '<td>' . h($res_mysmb_a['item2']) . '</td>';
    $view_mysmb_a .= '<td>' . h($res_mysmb_a['item3']) . '</td>';
    if ($res_mysmb_a["onoroff"] == '知恵袋'){
      $view_mysmb_a .= '<td><a href="chie_chat.php? threadid=' . h($res_mysmb_a["id"]) . '">[知恵袋を見る]</a></td></tr>';
    }else{
      $view_mysmb_a .= '<td></td></tr>';
    };
  };
};

// 3-5. 自分の質問したSMBを引っ張る
// SQLを実行
$stmt_mysmb_q = $pdo->prepare("SELECT * FROM gs_smcb_list WHERE requestid = :personalid");
// PDO::PARAM_STRがSTRかINTかその他かちゃんと確認！エラーになると気づきずらい
$stmt_mysmb_q->bindValue(':personalid', $personalid, PDO::PARAM_STR);
$status_mysmb_q = $stmt_mysmb_q->execute();

// 3-5. 自分のSMBの表示
$view_mysmb_q = "";
$spstarToShow2 = "";
if ($status_mysmb_q == false) {
  //SQLエラーの場合
  sql_error1($stmt);
} else {
  while ($res_mysmb_q = $stmt_mysmb_q->fetch(PDO::FETCH_ASSOC)) {
    $spstarToShow2 .= $res_mysmb_q['spstar'];
    $view_mysmb_q .= '<tr><td style="display:none;">' . h($res_mysmb_q['id'] ) . '</td>';
    $view_mysmb_q .= '<td>' . h($res_mysmb_q['requestname']) . '</td>';
    $view_mysmb_q .= '<td>' . h($res_mysmb_q['requestid']) . '</td>';
    $view_mysmb_q .= '<td>' . h($res_mysmb_q['responsername']) . '</td>';
    $view_mysmb_q .= '<td>' . h($res_mysmb_q['responserid']) . '</td>';
    $view_mysmb_q .= '<td>' . h($res_mysmb_q['bigitem']) . '</td>';
    $view_mysmb_q .= '<td>' . h($res_mysmb_q['miditem']) . '</td>';
    $view_mysmb_q .= '<td>' . h($res_mysmb_q['title']) . '</td>';
    $view_mysmb_q .= '<td>' . h($res_mysmb_q['onoroff']) . '</td>';
    $view_mysmb_q .= '<td>' . h($res_mysmb_q['requestdaytime']) . '</td>';
    $view_mysmb_q .= '<td>' . h($res_mysmb_q['smbdaytimestart']) . '</td>';
    $view_mysmb_q .= '<td>' . h($res_mysmb_q['smbdaytimeend']) . '</td>';
    // モチベのためにstar非表示
    $view_mysmb_q .= '<td hidden>' . h($res_mysmb_q['reviewstar']) . '</td>';
    // 神回答は見せる。1の場合は★、なければ"ー"
    if ($res_mysmb_q["spstar"] == '1'){
      $view_mysmb_q .= '<td>★</td>';
    }else{
      $view_mysmb_q .= '<td>ー</td>';
    };
    $view_mysmb_q .= '<td>' . h($res_mysmb_q['reviewcomment']) . '</td>';
    $view_mysmb_q .= '<td>' . h($res_mysmb_q['item1']) . '</td>';
    $view_mysmb_q .= '<td>' . h($res_mysmb_q['item2']) . '</td>';
    $view_mysmb_q .= '<td>' . h($res_mysmb_q['item3']) . '</td>';
    $view_mysmb_q .= '<td><a href="evaluate_smcb.php? id=' . h($res_mysmb_q["id"]) .'">[SMCBの結果を入力]</a></td>';
    if ($res_mysmb_q["onoroff"] == '知恵袋'){
      $view_mysmb_q .= '<td><a href="chie_chat.php? threadid=' . h($res_mysmb_q["id"]) . '">[知恵袋を見る]</a></td>';
    }else{
      $view_mysmb_q .= '<td></td>';
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
  <link rel="stylesheet" href="css/style.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <title>マイページ</title>
  <!-- <link rel="stylesheet" href="css/style.css"> -->
  <!-- <link href="css/bootstrap.min.css" rel="stylesheet"> -->
  <style>
    div {
      padding: 10px;
      font-size: 16px;
    }
  </style>
</head>

<body id="main">
  <!-- Head[Start] -->
  <div>
           <img src="image/nakadoori2.jpg" alt="backmyphoto" id="back">
            </div>
  <header>
    <div id="mypage_header">
    <div class="mypagehead"><h3><?= $myname?>さんのマイページ(あなたはシステム管理者です)</h3></div>
    <!-- 好きなとこに飛べるmenu.php-->
    <div id="menu"><?php include("kanrisha_menu.php"); ?></div>
    </div>
  </header>

  <!-- 3-3. 経歴一覧 -->
  <div class="space"></div>
  <div class="personal">
    <div class="left">
    <div class="haikei">
    <table class="personal2">
      <?= $view ?>
  </table>
  </div>
  <h3 class="obi_left">SMCB実績</h3>
  <table id="smb_left">
  <tr><td class="blue">回答したSMCB数：</td><td><?= $a_count ?></td><td class="blue">SMCBスコアに応じた評価</td><td class="blue">神回答評価</td></tr>
    <tr><td class="blue">質問したSMCB数：</td><td><?= $q_count ?></td><td rowspan="3" id="starRank"></td><td rowspan="3" id="spStar"></td></tr>
    <tr hidden><td>SMCB評価：</td><td><?= $star_ave ?></td></tr>
    <tr><td class="blue">SMCBスコア：</td><td><?= $smb_score ?></td></tr>
    <tr><td class="blue">神回答数：</td><td><?= $spstar_count ?></td></tr>
 
    </table>
    </div>
    <div id="keireki">
    <h3 class="obi">経歴・My Brain</h3>
    <div class="haikei2">
    <table id="keirekitable">
      <thead>
      <tr>
        <td class="id" style="display:none;">ID</td>
        <td class="personalid">From</td>
        <td class="name">to</td>
        <td class="title">所属</td>
        <td class="department">経験・業務内容</td>
        <td class="tel">#</td>
        <td class="email">#</td>
        <td class="teams">#</td>
      </tr>
      </thead>
      <tbody>
      <?= $viewkeireki ?>
      </tbody>
    </table>
    <ul>
      <li><a href="add_keireki.php">経歴・My Brainを追加</a></li>
    </ul>
    </div>
    </div>
    </div>
  

  <!-- 3-4. 回答したSMB一覧 -->
  <div class="kaitou_smb">
    <h3 class="obi">回答したSMCB</h3>
    <!-- phpで設定したviewを引っ張る -->
    <div class="table_container">
    <table id="smbatable">
      <thead>
      <tr class="tablehead">
        <td class="id" style="display:none;">No.：</td>
        <td class="requestname">依頼者：</td>
        <td class="requestid">ID：</td>
        <td class="responsername">回答者：</td>
        <td class="responserid">ID：</td>
        <td class="bigitem">大項目：</td>
        <td class="miditem">中項目：</td>
        <td class="title">タイトル：</td>
        <td class="onoroff">オフライン/知恵袋：</td>
        <td class="requestdaytime">依頼日時：</td>
        <td class="smbdaytimestart">SMCB実施開始：</td>
        <td class="smbdaytimeend">終了：</td>
        <!-- starはモチベのために見せない -->
        <td class="reviewstar" hidden>STAR：</td>
        <td class="reviewstar" >神回答：</td>
        <td class="reviewcomment">コメント：</td>
        <td class="item1">#</td>
        <td class="item2">#</td>
        <td class="item3">#</td>
        <td></td>
      </tr>
      </thead><tbody>
      <?= $view_mysmb_a ?>
      </tbody>
    </table>
    </div>
  </div>
  <!-- 3-5. 依頼したSMB一覧 -->
  <div class="kaitou_smb">
    <h3 class="obi">依頼したSMCB</h3>
    <!-- phpで設定したviewを引っ張る -->
    <div class="table_container">
    <table id="smbqtable">
      <thead>
      <tr class="tablehead">
        <td class="id" style="display:none;">No.：</td>
        <td class="requestname">依頼者：</td>
        <td class="requestid">ID：</td>
        <td class="responsername">回答者：</td>
        <td class="responserid">ID：</td>
        <td class="bigitem">大項目：</td>
        <td class="miditem">中項目：</td>
        <td class="title">タイトル：</td>
        <td class="onoroff">オフライン/知恵袋：</td>
        <td class="requestdaytime">依頼日時：</td>
        <td class="smbdaytimestart">SMCB実施開始：</td>
        <td class="smbdaytimeend">終了：</td>
        <td class="reviewstar" hidden>STAR：</td>
        <td class="reviewstar" >神回答：</td>
        <td class="reviewcomment">コメント：</td>
        <td class="item1">#</td>
        <td class="item2">#</td>
        <td class="item3">#</td>
        <td></td>
        <td></td>
      </tr>
      </thead>
      <tbody>
      <?= $view_mysmb_q ?>
      </tbody>
    </table>
  </div>
  </div>

  <script>
// SMBスコアでランクを変える
let smb_score = <?= $smb_score ?>;
let star_rank ="";

if (smb_score>=200){star_rank ="SMCBマスター"}
else if(200>smb_score && smb_score>=100){star_rank ="SMCBの玄人"}
else if(100>smb_score && smb_score>=50){star_rank ="かなりのSMCBユーザー！"}
else if(50>smb_score && smb_score>=20){star_rank ="どんどん使いこなそう♪"}
else{ star_rank ="あなたの隠れた知見をlet's SMCB!"};

$("#starRank").text(star_rank);

// 神回答のランクを変える
let spstar_count = <?= $spstar_count ?>;
let spStar ="";

if (spstar_count>=20){spStar ="スーパーエリート"}
else if(20>spstar_count && spstar_count>=10){spStar ="エリート"}
else if(10>spstar_count && spstar_count>=5){spStar ="アチーバー"}
else if(5>spstar_count && spstar_count>=3){spStar ="リーディングメンター"}
else{ star_rank ="チャレンジャー"};

$("#spStar").text(star_rank);



  </script>
</body>

</html>