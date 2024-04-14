<?php
//0. SESSION開始！！
session_start();

ini_set('display_errors', 'On'); // エラーを表示させるようにしてください
error_reporting(E_ALL); // 全てのレベルのエラーを表示してください

require_once('funcs.php');
//1. DB接続関数
$pdo = db_conn();
// funcs.phpで作ったログインしてないとselect.phpが開けないコード
sschk();

// user_select.phpで選んだidを取得、そのidのsmb情報を表示したい
$id = $_GET["id"];
  if (isset($_GET["responserid"])) {
    $chie_responserid = $_GET["responserid"];
    $chie_responsername = $_GET["responsername"];

    echo $chie_responserid;
    echo $chie_responsername;
  };



//２．データ登録SQL作成
$stmt   = $pdo->prepare("SELECT * FROM gs_smcb_list WHERE id = :id "); //SQLをセット
$stmt->bindValue(":id", $id, PDO::PARAM_INT);
$status = $stmt->execute(); //SQLを実行→エラーの場合falseを$statusに代入

//３．データ表示
$view = ""; //HTML文字列作り、入れる変数
if ($status == false) {
  //SQLエラーの場合
  sql_error1($stmt);
} else {
  //SQL成功の場合
  $originalResponsername = "";
  $originalResponserid = "";
  $res = $stmt->fetch();
  if (isset($res['responsername'])) {
    $originalResponsername .= h($res['responsername']);
    $originalResponserid .= h($res['responserid']);
  };
}

?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <title>Evaluate SCMB</title>
  <style>
    div {
      padding: 10px;
      font-size: 16px;
    }
  </style>
  <link rel="stylesheet" href="css/style_new.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>

<body>

  <!-- Head[Start] -->
  <div class="space"></div>
  <header>
    <div class="obi">Evaluate SMCB</div>
  </header>
  <!-- Head[End] -->

  <!-- Main[Start] -->
  <!-- insert.phpにpost方式で飛ばす -->
  <!-- 質問：登録後にinsert_book.phpに飛べない、どうしたいいの？ -->
  <form method="post" action="insert_evaluate_smcb.php">
    <!-- このjumbotronが複雑、質問 -->

    <div>
      <div></div>
    </div>
    <div>
      <fieldset>
        <legend>Evaluate SMCB</legend>
        <table>
          <tr>
            <td class="blue"><label>依頼者：</td>
            <td><input type="text" name="requestname" value="<?= $res["requestname"] ?>" readonly></td>
            <td><input type="text" name="requestid" value="<?= $res["requestid"] ?>" readonly></td></label></td>
          </tr>
          <tr>
            <td class="blue"><label>回答者：</td>
            <td><input type="text" name="responsername" id="responsername" value="<?= $originalResponsername ?>"></td>
            <td><input type="text" name="responserid" id="responserid" value="<?= $originalResponserid ?>"></td></label></td>
          </tr>
          <tr>
            <td class="blue"><label>大項目：</td>
            <td colspan="2"><input type="text" name="bigitem" value="<?= $res["bigitem"] ?>" readonly></label></td>
          </tr>
          <tr>
            <td class="blue"><label>中項目：</td>
            <td colspan="2"><input type="text" name="miditem" value="<?= $res["miditem"] ?>" readonly></label></td>
          </tr>
          <tr>
            <td class="blue"><label>タイトル：</td>
            <td colspan="2"><input type="text" name="title" value="<?= $res["title"] ?>" readonly></label></td>
          </tr>
          <tr>
            <td class="blue"><label>オフライン/知恵袋：</td>
            <td colspan="2"><select name="onoroff">
                <option value="オフライン" <?= $res["onoroff"] === "オフライン" ? "selected" : "" ?>>オフライン</option>
                <option value="知恵袋" <?= $res["onoroff"] === "知恵袋" ? "selected" : "" ?>>知恵袋</option>
              </select></label></td>
          </tr>
          <tr>
            <td class="blue"><label>依頼日時：</td>
            <td colspan="2"><input type="text" name="requestdaytime" value="<?= $res["requestdaytime"] ?>" readonly></label></td>
          </tr>
          <tr>
            <td class="blue"><label>SMCB実施日：</td>
            <td > 開始 <input type="datetime-local" name="smbdaytimestart" value="選択してください"></td>
            <td > 終了 <input type="datetime-local" name="smbdaytimeend" value="選択してください"></label></td>
          </tr>
          <tr>
            <td class="blue"><label>#(任意)</td>
            <td><input type="text" name="item1" value="<?= $res["item1"] ?>"></label></td><td>#は検索タグとなります</td>
          </tr>
          <tr>
            <td class="blue"><label>#(任意)</td>
            <td colspan="2"><input type="text" name="item2" value="<?= $res["item2"] ?>"></label></td>
          </tr>
          <tr>
            <td class="blue"><label>#(任意)</td>
            <td colspan="2"><input type="text" name="item3" value="<?= $res["item3"] ?>"></label></td>
          </tr>
          <tr>
            <td class="blue"><label>評価(1-5(5:大満足,3:普通,1:改善の余地あり))</td>
            <td><select type="text" name="reviewstar">
                <option>5</option>
                <option>4</option>
                <option>3</option>
                <option>2</option>
                <option>1</option>
              </select></label></td><td>※ユーザーから閲覧されません</td>
          </tr>
          <tr>
            <td class="blue"><label>神回答(1:神)</td>
            <td><select type="text" name="spstar" value="<?= isset($res["spstar"]) ? $res["spstar"] : "" ?>">
                <option>0</option>
                <option>1</option>
                </sepect>
                </label></td><td>※ユーザーから閲覧されます</td>
          </tr>
          <tr>
            <td class="blue"><label>コメント：</td>
            <td colspan="2"><input type="text" name="reviewcomment"></label></td>
          </tr>
        </table>
        <input type="hidden" name="id" value="<?= $res["id"] ?>">
        <div class="space"></div>
        <tr><input type="submit" value="送信">
      </fieldset>
    </div>
  </form>
  <div style='height: 10px'></div>
  <ul>
  <?php if ($_SESSION["kanri_flg"] != 1) : ?>
    <li><a href="smcb_list.php">SMCB一覧に戻る</a></li>
      <li><a href="mypage.php" >マイページに戻る</a></li>
    <?php else : ?>
      <li><a href="kanrisha_smcb_list.php">SMCB一覧に戻る</a></li>
      <li><a href="kanrisha_mypage.php" >管理者ページに移動する</a></li>
    <?php endif; ?>
  </ul>
  <!-- Main[End] -->

  <script>
    $(document).ready(function() {
      // もし$chie_responseridに内容が入っていたら、
      if ("<?php echo $chie_responserid; ?>" !== "") {
        // #responseridを$chie_responseridに上書きする
        $('#responserid').val("<?= $chie_responserid ?>");
      } else {
        // $chie_responsernameに内容がが入っていない場合は$originalResponsernameにする
        $('#responserid').val("<?= $originalResponserid ?>");
      };

      // もし$chie_responsernameに内容が入っていたら、
      if ("<?php echo $chie_responsername; ?>" !== "") {
        // #responsernameを$chie_responsernameに上書きする
        $('#responsername').val("<?= $chie_responsername ?>");
      } else {
        // $chie_responsernameに内容がが入っていない場合は$originalResponsernameにする
        $('#responsername').val("<?= $originalResponsername ?>");
      };

    });

    $(document).ready(function() {
      $('#change_spstar').click(function(event) {
        // 普通クリックするとsendされてしまうので、デフォルトのクリック動作を無効にする
        console.log("1");
        event.preventDefault();

        let spstar_input = $('#spstar_input');
        // 現在の値を取得
        let current_value = parseInt(spstar_input.val());
        // 値を切り替える
        let new_value = (current_value === 0) ? 1 : 0;
        // 新しい値を設定
        spstar_input.val(new_value);
      });
    });
  </script>

</body>

</html>