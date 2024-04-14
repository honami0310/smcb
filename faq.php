<?php

//0. SESSION開始！！
session_start();

require_once('funcs.php');
//1. DB接続関数
$pdo = db_conn();
// funcs.phpで作ったログインしてないとselect.phpが開けないコード
sschk();

// sessionに入れたpersonal idを取得
$personalid = $_SESSION["personalid"];

?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="css/faq_style.css">
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
  <header>
    <div id="mypage_header">
    <div class="mypagehead"><h3>SMCBの使い方</h3></div>
    <!-- 好きなとこに飛べるmenu.php-->
    <div id="menu"><?php if ($_SESSION["kanri_flg"] != 1) : ?>
    <?php include("menu.php"); ?>
    <?php else : ?>
      <?php include("kanrisha_menu.php"); ?>
    <?php endif; ?>
  </div>
    </div>
  </header>

  <!-- SMCBとは -->
  <div class="space"></div>
  <div>
    <div ><h3 class="whatsmcb">SMCBとは、Share MC Brainの略。MCの英知を提供・提案・共有しさらなるMC Brainの向上を目指す場所です。</h2></div>
    <div ><h3 class="whatsmcb">皆様のご経験を、新たに取り掛かる社員にご教示頂いたり、詳しい方を探したり、新たなイディアを提案する場として活用してください。</h2></div>
  </div>
  <div>
    <div ><h3 >①まずはMy Brainを作りましょう</h2></div>
    <div>
    <?php if ($_SESSION["kanri_flg"] != 1) : ?>
      <a href="mypage.php" class="mennubutton">My Page</a>
    <?php else : ?>
      <a href="kanrisha_mypage.php"  class="mennubutton">My Page</a>
    <?php endif; ?>
    で、[経歴・My Brain]に過去の経歴や知見を入力しましょう</div>
    
    <div ><h3 >②詳しい人を探したいとき</h2></div>
    <div><?php if ($_SESSION["kanri_flg"] != 1) : ?>
      <a href="user_select.php" class="mennubutton">User一覧</a>
    <?php else : ?>
      <a href="kanrisha_user_select.php"  class="mennubutton">User一覧</a>
    <?php endif; ?>
    で、検索ツールを使い知見を持っていそうな人を探しましょう</div>
    
    <div><?php if ($_SESSION["kanri_flg"] != 1) : ?>
      <a href="smcb_list.php" class="mennubutton">SMCB一覧</a>
    <?php else : ?>
      <a href="kanrisha_smcb_list.php"  class="mennubutton">SMCB一覧</a>
    <?php endif; ?>
    で、同じく検索ツールを使い過去のSMCBを見つけることができます</div>
    <p>SMCBを依頼するときは、[Share依頼]から依頼事項を入力します。今後検索する方の参考となるよう#(ハッシュタグ)もご活用ください。</p>
    <p>オフライン(打ち合わせ)で聞く場合は[オフライン]、オープンチャットで質問する場合は[知恵袋]を選択します。</p>

    <div ><h3 >③提案・質問をしたいとき</h2></div>
    <div>新規事業やアイディア、協業提案、過去のSMCBにない質問を呼びかけるときは<a href="new_chie.php" class="menubutton">新規知恵袋</a>を作成ください。</div>
  
    <div ><h3 >④質問されたら、これ知ってる！があったら</h2></div>
    
    <div><?php if ($_SESSION["kanri_flg"] != 1) : ?>
      <a href="mypage.php" class="mennubutton">My Page</a>
    <?php else : ?>
      <a href="kanrisha_mypage.php"  class="mennubutton">My Page</a>
    <?php endif; ?>
    [回答したSMCB]にMy Brain共有依頼が出るので依頼者と打ち合わせ・チャットを実施しましょう。</div>
    <div><a href="smcb_list.php" class="menubutton">SMCB一覧</a>オフライン/知恵袋が[知恵袋]のものは誰でも回答できます、ぜひ質問に回答・提案に返答してください！</div>
    
    <div ><h3 >⑤SMCBが完了したら</h2></div>
    <p>依頼・提案したSMCBが完了したら以下を実施しましょう</p>
    <div><p>知恵袋の場合はまず<?php if ($_SESSION["kanri_flg"] != 1) : ?>
      <a href="mypage.php" class="mennubutton">My Page</a>
    <?php else : ?>
      <a href="kanrisha_mypage.php"  class="mennubutton">My Page</a>
    <?php endif; ?>
    [依頼したSMCB]の[知恵袋を見る]から一番良かった回答を[ベストアンサーにする]で選出の上、[知恵袋を完了してSMCBの結果を入力]に進み、評価を入力します。素晴らしい回答の場合は特別に[神回答]としてください。</p></div>
    <div><p>オフライン、もしくは知恵袋でベストアンサーを選択済みの場合</p><?php if ($_SESSION["kanri_flg"] != 1) : ?>
      <a href="mypage.php" class="mennubutton">My Page</a>
    <?php else : ?>
      <a href="kanrisha_mypage.php"  class="mennubutton">My Page</a>
    <?php endif; ?>
    [依頼したSMCB]、もしくは
    <?php if ($_SESSION["kanri_flg"] != 1) : ?>
      <a href="smcb_list.php" class="mennubutton">SMCB一覧</a>
    <?php else : ?>
      <a href="kanrisha_smcb_list.php"  class="mennubutton">SMCB一覧</a>
    <?php endif; ?>
    の該当SMCB列の[SMCBの結果を入力]から評価を入力します。素晴らしい回答の場合は特別に[神回答]としてください。</div>
  </div>
  <div>
    <h2>SMCBの評価について</h2>
    <h3>SMCBスコア：</h3><p>回答数、質問数、評価、神回答の数からシステム内でスコアを計算します。 どんどんSMCBを活用しスコアを上げましょう！</p>
    <h3>神回答数：</h3><p>神回答数に応じて以下のランクを取得できます。どなたからも閲覧されるランクとなります。素晴らしいShare Your Brainを積極的に実施しましょう！</p>
    <table>
      <tr><td>神回答数</td><td>ランク</td></tr>
      <tr><td>over 20</td><td>スーパーエリート</td></tr>
      <tr><td>10~19</td><td>エリート</td></tr>
      <tr><td>5~9</td><td>アチーバー</td></tr>
      <tr><td>3~4</td><td>リーディングメンター</td></tr>
      <tr><td>0~2</td><td>チャレンジャー</td></tr>
      



    </table>
  </div>
  

  <script>
// SMBスコアでランクを変える
let smb_score = <?= $smb_score ?>;
let star_rank ="";

if (smb_score>=200){star_rank ="SMCBマスター";}
else if(200>smb_score && smb_score>=100){star_rank ="SMCBの玄人";}
else if(100>smb_score && smb_score>=50){star_rank ="かなりのSMCBユーザー！";}
else if(50>smb_score && smb_score>=20){star_rank ="どんどん使いこなそう♪";}
else{ star_rank ="あなたの隠れた知見をlet's SMCB!";};

$("#starRank").text(star_rank);

// 神回答のランクを変える
let spstar_count = <?= $spstar_count ?>;
let spStar ="";

if (spstar_count>=20){spStar ="スーパーエリート";}
else if(20>spstar_count && spstar_count>=10){spStar ="エリート";}
else if(10>spstar_count && spstar_count>=5){spStar ="アチーバー";}
else if(5>spstar_count && spstar_count>=3){spStar ="リーディングメンター";}
else{ star_rank ="チャレンジャー";};

$("#spStar").text(star_rank);

// 神回答を★で見せる

let spstarToShow1 = <?= $spstarToShow1 ?>;
let spstar1 ="";

if (spstarToShow1 == 1){spstar1 ="★";}
else{ spstar1 ="ー";};

$("#spstar1").text(spstar1);

// 神回答を★で見せる

let spstarToShow2 = <?= $spstarToShow2 ?>;
let spstar2 ="";

if (spstarToShow2 == 1){spstar2 ="★";}
else{ spstar2 ="ー";};

$("#spstar2").text(spstar2);

  </script>
</body>

</html>