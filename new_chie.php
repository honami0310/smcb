<?php
//0. SESSION開始！！
session_start();

require_once('funcs.php');
//1. DB接続関数
$pdo = db_conn();
// funcs.phpで作ったログインしてないとselect.phpが開けないコード
sschk();

// user_select.phpで選んだidを取得、そのidの人の情報を表示したい
$personalname = $_SESSION["name"];
$personalid = $_SESSION["personalid"];

?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <title>Request SMCB</title>
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
  <header>
    <div class="obi">知恵袋新規作成</div>
  </header>
  <!-- Head[End] -->

  <!-- Main[Start] -->
  <!-- insert.phpにpost方式で飛ばす -->
  <!-- 質問：登録後にinsert_book.phpに飛べない、どうしたいいの？ -->
  <form method="post" action="insert_new_chie.php">
    <!-- このjumbotronが複雑、質問 -->
    <div>
      <!-- オフラインでも知恵袋でも必要な入力項目 -->
      <fieldset>
        <legend>SMCB事項</legend>
        <table>
          <tr>
            <td class="blue"><label>依頼者：</td>
            <td><input type="text" name="requestname" value="<?php echo $personalname ?>" readonly></td>
            <td><input type="text" name="requestid" value="<?php echo $personalid ?>" readonly></td></label></td>
          </tr>
          <tr>
            <td class="blue"><label>回答者：</td>
            <td colspan="2">回答者が決定している場合はユーザー一覧から知恵袋を作成ください</td></label></td>
          </tr>
          <tr>
            <td class="blue"><label>大項目：</td>
            <td colspan="2"><select name="bigitem">
            <option value="選択してください">選択してください</option>
            <option value="新規事業">新規事業</option>
            <option value="事業投資">事業投資</option>
<option value="貿易">貿易</option>
<option value="社内業務">社内業務</option>
<option value="その他">その他</option>
            </select>
            </label></td>
          </tr>
          <tr>
            <td class="blue"><label>中項目：</td>
            <td colspan="2"><select name="miditem">
            <option value="選択してください">選択してください</option>
            <option value="会計・税務">会計・税務</option>
            <option value="営業業務">営業業務</option>
<option value="社内庶務">社内庶務</option>
<option value="法務">法務</option>
<option value="内部統制・コンプラ・サステナ">内部統制・コンプラ・サステナ</option>
<option value="人事">人事</option>
<option value="その他">その他</option>
</select>
            </label></td>
          </tr>
          <tr>
            <td class="blue"><label>タイトル：</td>
            <td colspan="2"><input type="text" name="title" id="title"></label></td>
          </tr>
          <tr>
            <td class="blue"><label>オフライン/知恵袋：</td>
            <td colspan="2"><input type="text" name="onoroff" value="知恵袋" readonly></label></td>
          </tr>
          <tr>
            <td class="blue"><label>#(任意)</td>
            <td><input type="text" name="item1"></label></td><td>#は検索タグとなります</td>
          </tr>
          <tr>
            <td class="blue"><label>#(任意)</td>
            <td colspan="2"><input type="text" name="item2"></label></td>
          </tr>
          <tr>
            <td class="blue"><label>#(任意)</td>
            <td colspan="2"><input type="text" name="item3"></label></td>
          </tr>
          <tr>
            <td class="blue"><label>提案・問合わせ内容：</td>
            <td colspan="2"><textarea name="comment"></textarea></label></td>
          </tr>
          <!-- 質問or回答(非表示)ここでは必ず質問となる -->
          <tr style="display: none;"><label>
              <td><input type="text" name="qa" value="q"></td>
            </label></tr>
        </table>
      </fieldset>
      <div class="space"></div>
      <tr><input type="submit" value="送信">
    </div>
  </form>

  <div style='height: 10px'></div>
  <ul>
    <?php if ($_SESSION["kanri_flg"] != 1) : ?>
      <li><a href="mypage.php" >マイページに戻る</a></li>
    <?php else : ?>
      <li><a href="kanrisha_mypage.php" >管理者ページに移動する</a></li>
    <?php endif; ?>
  </ul>
  <!-- Main[End] -->

</body>

</html>