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
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search_keyword'])) {
  $search_keyword = $_POST['search_keyword'];
  // 検索クエリを実行する
  // 検索が一部の項目だけなので全体に反映できるように要修正
  $stmt = $pdo->prepare("SELECT * FROM gs_smcb_list WHERE 
    requestname LIKE :search_keyword OR
    requestid LIKE :search_keyword OR 
    responsername LIKE :search_keyword OR
    responserid LIKE :search_keyword OR 
    bigitem LIKE :search_keyword OR
    miditem LIKE :search_keyword OR
    title LIKE :search_keyword OR
    onoroff LIKE :search_keyword OR
    item1 LIKE :search_keyword OR
    item2 LIKE :search_keyword OR
    item3 LIKE :search_keyword");

  $stmt->bindValue(':search_keyword', "%$search_keyword%", PDO::PARAM_STR);
  $status = $stmt->execute();
  // もしソートが実施されたらこのパターン
} elseif (isset($_GET['sort_column']) && isset($_GET['sort_order'])) {

  // ソートした場合の内容を受け取る
  $sort_column = isset($_GET['sort_column']) ? $_GET['sort_column'] : 'id';
  $sort_order = isset($_GET['sort_order']) ? $_GET['sort_order'] : 'desc';

  // ソートされた場合の検索結果
  $sql = "SELECT * FROM gs_smcb_list ORDER BY $sort_column $sort_order";

  // SQLを実行
  $stmt = $pdo->prepare($sql);
  $status = $stmt->execute();
} else {
  // 検索フォームが送信されていない場合は、全てのデータを取得する
  $stmt = $pdo->prepare("SELECT * FROM gs_smcb_list");
  $status = $stmt->execute();
}
//３．データ表示
$view = "";
if ($status == false) {
  //SQLエラーの場合
  sql_error1($stmt);
} else {
  while ($res = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $view .= '<tr><td hidden>' . h($res['id']) . '</td>';
    // $view .= '<td>' . h($res['requestname']) . '</td>';
    $view .= '<td><a href="colleaguepage.php? personalid=' . h($res["requestid"]) . '">[' . h($res['requestname']) . ']</td>';
    $view .= '<td hidden>' . h($res['requestid']) . '</td>';
    // $view .= '<td>' . h($res['responsername']) . '</td>';
    $view .= '<td>';
if(isset($res["responserid"])) {
    $view .= '<a href="colleaguepage.php?personalid=' . h($res["responserid"]) . '">[' . h($res['responsername']) . ']</a>';
}else{$view .='未決定';
}
$view .= '</td>';
    $view .= '<td hidden>' . h($res['responserid']) . '</td>';
    $view .= '<td>' . h($res['bigitem']) . '</td>';
    $view .= '<td>' . h($res['miditem']) . '</td>';
    $view .= '<td>' . h($res['title']) . '</td>';
    $view .= '<td>' . h($res['onoroff']) . '</td>';
    $view .= '<td>' . h($res['requestdaytime']) . '</td>';
    $view .= '<td>' . h($res['smbdaytimestart']) . '</td>';
    $view .= '<td>' . h($res['smbdaytimeend']) . '</td>';
    $view .= '<td hidden>' . h($res['reviewstar']) . '</td>';
    // $view .= '<td>' . h($res['spstar']) . '</td>';
            // 神回答は見せる。1の場合は★、なければ"ー"
            if ($res["spstar"] == '1'){
              $view .= '<td>★</td>';
            }else{
              $view .= '<td>ー</td>';
            };
    $view .= '<td>' . h($res['reviewcomment']) . '</td>';
    $view .= '<td>' . h($res['item1']) . '</td>';
    $view .= '<td>' . h($res['item2']) . '</td>';
    $view .= '<td>' . h($res['item3']) . '</td>';
    if ($res["requestid"] == $_SESSION["personalid"]) {
      $view .= '<td><a href="evaluate_smcb.php? id=' . h($res["id"]) . '">[SMCBの結果を入力]</a></td>';
  }else{
    $view .= '<td></td>'; 
  }
  if ($res["onoroff"] == '知恵袋'){
    $view .= '<td><a href="chie_chat.php? threadid=' . h($res["id"]) . '">[知恵袋を見る]</a></td>';
  }
    else {
      $view .= '<td></td>';
    };
    $view .= '<td><a href="kanrisha_detail_smcb.php? id=' . h($res["id"]) . '">[SMCBを編集]</a></td>';
    $view .= '<td><a href="kanrisha_delete_smcb.php? id=' . h($res["id"]) . '">[SMCBを削除]</a></td>';
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
    <div class="space"></div>
    <div class="obi">Share MC Brain一覧(管理者ページ)</div>
    <div id="menu"><?php include("kanrisha_menu.php"); ?></div>
  </header>
  <!-- Head[End] -->

  <!-- Main[Start] -->
  <div class="k_personal">
  <div class="k_left">
    <div class="haikei">
    <h4 class="obi_left">検索・ソート</h4>
    <table class="personal2">
    <div>
    <form action="kanrisha_smcb_list.php" method="post">
      <input type="text" name="search_keyword" placeholder="検索キーワードを入力">
      <input type="submit" value="検索">
    </form>
  </div>
  </table>
  <div>
    <form action="kanrisha_smcb_list.php" method="get">
      <select name="sort_column">
        <option value="0">ソート項目を選択</option>
        <option value="requestid" <?php if (isset($_GET['sort_column']) && $_GET['sort_column'] === 'requestid') echo ' selected'; ?>>依頼者年次</option>
        <option value="responserid" <?php if (isset($_GET['sort_column']) && $_GET['sort_column'] === 'responserid') echo ' selected'; ?>>回答者年次</option>
        <option value="onoroff" <?php if (isset($_GET['sort_column']) && $_GET['sort_column'] === 'onoroff') echo ' selected'; ?>>知恵袋→オフライン</option>
        <option value="requestdaytime" <?php if (isset($_GET['sort_column']) && $_GET['sort_column'] === 'requestdaytime') echo ' selected'; ?>>依頼日</option>
        <option value="smbdaytimestart" <?php if (isset($_GET['sort_column']) && $_GET['sort_column'] === 'smbdaytimestart') echo ' selected'; ?>>回答日</option>
        <option value="spstar" <?php if (isset($_GET['sort_column']) && $_GET['sort_column'] === 'spstar') echo ' selected'; ?>>神回答</option>
      </select>
      <select name="sort_order">
        <option value="error" <?php if (isset($_GET['sort_order']) && $_GET['sort_order'] === 'error') echo ' selected'; ?>>降順or昇順を選択</option>
        <option value="desc" <?php if (isset($_GET['sort_order']) && $_GET['sort_order'] === 'desc') echo ' selected'; ?>>降順</option>
        <option value="asc" <?php if (isset($_GET['sort_order']) && $_GET['sort_order'] === 'asc') echo ' selected'; ?>>昇順</option>
      </select>
      <input type="submit" value="ソート">
    </form>
  </div>
  <div><button><a href="kanrisha_smcb_list.php">すべて表示</a></button></div>

  </div>
  </div>
  </div>



  <div class="table_container_forsmcb">
    <!-- phpで設定したviewを引っ張る -->
    <table id="smbatable">
    <thead>
      <tr class="tablehead">
        <td class="id" rowspan="2" hidden>No.：</td>
        <td class="requestname" rowspan="2">依頼者：</td>
        <!-- <td class="requestid" rowspan="2">ID：</td> -->
        <td class="responsername"rowspan="2">回答者：</td>
        <!-- <td class="responserid"rowspan="2">ID：</td> -->
        <td class="bigitem" rowspan="2">大項目：</td>
        <td class="miditem" rowspan="2">中項目：</td>
        <td class="title" rowspan="2">タイトル：</td>
        <td class="onoroff" rowspan="2">オフライン/知恵袋：</td>
        <td class="requestdaytime" rowspan="2">依頼日時：</td>
        <td class="smbdaytimestart" rowspan="2">SMCB実施開始：</td>
        <td class="smbdaytimeend" rowspan="2">終了：</td>
        <td class="reviewstar" hidden rowspan="2">STAR：</td>
        <td class="reviewstar" rowspan="2">神評価：</td>
        <td class="reviewcomment" rowspan="2">コメント：</td>
        <td class="item1" colspan="3">検索タグ</td>
        <td rowspan="2" ></td><td rowspan="2" ></td><td rowspan="2" ></td><td rowspan="2" ></td></tr>
        <tr><td class="item1">#</td>
        <td class="item2">#</td>
        <td class="item3">#</td>
      </thead><tbody>
      <?= $view ?>
      </tbody>
    </table>
  </div>
  <!-- Main[End] -->
  <div style="height: 50px"></div>
  <ul>
    <li><a href="kanrisha_mypage.php">管理者ページに移動する</a></li>
  </ul>
</body>

</html>