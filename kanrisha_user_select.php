<?php
//0. SESSION開始！！
session_start();

require_once('funcs.php'); //php02発展の記載。includeより一般的、間違ってると動かなくなる
$pdo = db_conn();      //DB接続関数
// funcs.phpで作ったログインしてないとselect.phpが開けないコード
sschk();
// funcs.phpで作った管理者か確認するコード
kanri();


//２．データ登録SQL作成
$stmt = $pdo->prepare("SELECT * FROM gs_personal");
$status = $stmt->execute();

//３．データ表示
$view = "";
if ($status == false) {
  //SQLエラーの場合
  sql_error1($stmt);
} else {
  while ($res = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $view .= '<tr><td hidden>' . h($res['id']) . '</td>';
    $view .= '<td>' . h($res['personalid']) . '</td>';
    $view .= '<td><a href="colleaguepage.php? id='.h($res["id"]). '&personalid=' . h($res["personalid"]) . '">[' . h($res['name']) . ']</td>';
    $view .= '<td>' . h($res['title']) . '</td>';
    $view .= '<td>' . h($res['department']) . '</td>';
    $view .= '<td><a href="tel:' . h($res['tel']) . '">' . h($res['tel']) . '</a></td>';
    $view .= '<td><a href="mailto:' . h($res['email']) . '">' . h($res['email']) . '</a></td>';
    $view .= '<td><a href="https://teams.microsoft.com/l/chat/0/0?users= ' . h($res['email']) . '">Teams</a></td>';
     // 個別ページ・申請ページに飛ぶ
     $view .= '<td><a href="request_smb.php? name='.h($res["name"]). '&personalid=' . h($res["personalid"]) . '">';
     $view .= '[Share依頼]</td>';
     // 個別ページ・申請ページに飛ぶ
     $view .= '<td><a href="kanrisha_detail_user.php? id='.h($res["id"]).'">';
     $view .= '[編集]</td>';
     $view .= '<td><a href="kanrisha_delete_user.php? id='.h($res["id"]).'">';
     $view .= '[削除]</td></tr>';
  }
};

// 経歴を表示
//4-1. ログインした人の経歴、SMB情報のSQL(gs_keireki)を引っ張る
// 10人分のテーブルを作るのでテーブル名にpersonal IDを入れるけど実用するときはもっといい方法あるか要確認
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search_keyword'])) {
  $search_keyword = $_POST['search_keyword'];
  // 検索クエリを実行する
  // 検索が一部の項目だけなので全体に反映できるように要修正
  $stmt_keireki = $pdo->prepare("SELECT * FROM gs_keireki WHERE 
name LIKE :search_keyword OR
personalid LIKE :search_keyword OR
belongs LIKE :search_keyword OR
naiyou LIKE :search_keyword OR 
item1 LIKE :search_keyword OR
item2 LIKE :search_keyword OR 
item3 LIKE :search_keyword");

$stmt_keireki->bindValue(':search_keyword', "%$search_keyword%", PDO::PARAM_STR);
$status_keireki = $stmt_keireki->execute();
  // もし検索されない場合全表示
} else {
  // 検索フォームが送信されていない場合は、全てのデータを取得する
  $stmt_keireki = $pdo->prepare("SELECT * FROM gs_keireki");
  $status_keireki = $stmt_keireki->execute();
};

//4-2．データ表示
$viewkeireki = "";
if ($status_keireki == false) {
// SQLエラーの場合の処理
sql_error2($stmt_keireki);
} else {
// データの取得が成功した場合の処理
while ($reskeireki = $stmt_keireki->fetch(PDO::FETCH_ASSOC)) {
  $viewkeireki .= '<tr><td style="display:none;">' . h($reskeireki['id']) . '</td>';
  $viewkeireki .= '<td style="display:none;">' . h($reskeireki['personalid']) . '</td>';
  $viewkeireki .= '<td><a href="colleaguepage.php? personalid=' . h($reskeireki["personalid"]) . '">[' . h($reskeireki['name']) . ']</td></td>';
  $viewkeireki .= '<td>' . h($reskeireki['kaishi']) . '</td>';
  $viewkeireki .= '<td>' . h($reskeireki['owari']) . '</td>';
  $viewkeireki .= '<td>' . h($reskeireki['belongs']) . '</td>';
  $viewkeireki .= '<td>' . h($reskeireki['naiyou']) . '</td>';
  $viewkeireki .= '<td>' . h($reskeireki['item1']) . '</td>';
  $viewkeireki .= '<td>' . h($reskeireki['item2']) . '</td>';
  $viewkeireki .= '<td>' . h($reskeireki['item3']) . '</td></tr>';
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
  <title>Share MC Brain User 一覧(管理者ページ)</title>
  <!-- <link rel="stylesheet" href="css/style.css"> -->
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
  <div id="mypage_header">
    <div class="mypagehead"><h3>SMCBユーザー・経歴/MC Brain 一覧(管理者ページ)</h3></div>
    <div  id="menu"><?php include("kanrisha_menu.php"); ?></div>
    </div>
  </header>
  <!-- Head[End] -->

  <!-- Main[Start] -->
  <div class="space"></div>
  <div class="space"></div>
  <div class="user">
    <div class="obi">ユーザー 一覧</div>
  <div>User詳細は氏名をクリック</div><br>
    <!-- phpで設定したviewを引っ張る -->
    <div class="table_container">
    <table id="usertable">
      <thead>
      <tr class="tablehead">
        <td class="id" hidden>ID</td>
        <td class="personalid">Personal ID</td>
        <td class="name">氏名</td>
        <td class="title">役職</td>
        <td class="department">所属</td>
        <td class="tel">電話番号</td>
        <td class="email">emial</td>
        <td class="teams">Teamsチャット</td>
        <td class="teams">Share依頼</td>
        <td></td><td></td>
      </tr>
      </thead>
      <tbody>
      <?= $view ?>
      </tbody>
    </table>
    </div>
  </div>

  <!--経歴/MC Brain 一覧  -->
  <div class="user">
  <div class="obi">経歴/MC Brain 一覧</div>
  <div>キーワードで検索しBrainを持つ方を探しましょう。</div>
  <div>
    <form action="user_select.php" method="post">
      <input type="text" name="search_keyword" placeholder="検索キーワードを入力">
      <input type="submit" value="検索">
    </form>
  </div>  
  <div><button><a href="user_select.php">すべて表示</a></button></div>
  <div class="table_container">
    <table id="usertable">
      <thead>
      <tr class="tablehead">
      <td class="id" style="display:none;">ID</td>
      <td class="personalid" style="display:none;">氏名</td>
      <td class="name" >氏名</td>
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
    </div>
  </div>
  <!-- Main[End] -->
<div style="height: 50px"></div>
<ul>
		<li><a href="kanrisha_mypage.php">管理者ページに移動する</a></li>
	</ul>
</body>

</html>