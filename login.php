<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width">
<link rel="stylesheet" href="css/style_new.css">
<style>div{padding: 10px;font-size:16px;}</style>
<title>ログイン</title>
</head>
<body>

<header>
  <div class="space"></div>
<div class="obi"><href="mypage.php"><h3>Share MC Brain(SMCB)へログイン</h3></nav></div>
</header>
<div class="space"></div>
<!-- lLOGINogin_act.php は認証処理用のPHPです。 -->
<div class="login">
<form name="form1" action="login_act.php" method="post">
<table>
  <tr><td>Personal ID(MC○○○○○○):</td><td><input type="text" name="lid" /></td></tr>
<tr><td>PW:</td><td><input type="password" name="lpw" /></td></tr>
</table>
<div class="space"></div>
<input type="submit" value="LOGIN" >
</form>
</div>


</body>
</html>