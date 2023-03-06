<?php
header('Content-Type: text/html; charset=UTF-8');
header('Content_Language: ja');
//トレンドも通知する
//require_once "arro_insert.php";
require "vendor/autoload.php";
//require "TwistOAuth.phar/TwistOAuth.php"

$consumer_key = 'a';
$consumer_secret = 'b';
$access_token = 'c';
$access_token_secret = 'd';

try {
    $connection = new TwistOAuth($consumer_key, $consumer_secret, $access_token, $access_token_secret);

} catch(TwistException $e) {
    $error = $e->getMessage();
}


$statuses = []; //すべてのツイート用の配列

$screen_name = "mezauranai";
$param = array('screen_name' => $screen_name, 'count' => '12'); //200件取得
if(isset($max_id)) {
    $param['max_id'] = $max_id;
}
$tmp_statuses = [];
try {
    $tmp_statuses = $connection->get('statuses/user_timeline', $param);
} catch (TwistException $e) {
    $error = $e->getMessage();
}
//取得したツイートがあった時
        $statuses =  array_merge($statuses, $tmp_statuses); //配列の最後に追加
    echo $tmp_statuses;
        $max_id = $tmp_statuses[count($tmp_statuses)-1]->id - 1; //取得した最古のツイートのidを保存

try {
    $text = null;
    $message = null;
    foreach ($statuses as $status) {
        $text = htmlspecialchars($status->text);
        $to = "enotiru0000@gmail.com";
        $subject = "おはようございます。Buediaです。";
        $myseiza = "うお座";
        $rank = "1位";
        if (mb_strpos($text, $myseiza) !== FALSE) {
            //もし、$myseizaが$textにあったら
            //echo "第一チェックポイント";
            if (mb_strpos($text, "11位") !== FALSE) {
                //11位だったら
                $lp = "ラッキーポイント";
                if (mb_strpos($text, $lp) !== FALSE) {
                    //$posi=mb_strpos($text,$lp);
                    $gettext = mb_stristr($text, $lp);
                    $message = "今日の{$myseiza}の" . $gettext . "です！" . "\n\n今日も一日頑張りましょう！";
                    //htmlspecialchars($statuses[0]->user->name);
                    mb_language('uni');
                    mb_internal_encoding('UTF-8');
                    mb_send_mail($to, $subject, $message);
                } else {
                    $text = "星座に関するワードは見当たりますが、エラーです1";
                    mb_send_mail($to, $subject, $message);
                }
            } else {

                if (mb_strpos($text, $rank) !== FALSE) {
                    //もし、一位だったらかつ、１１位がテキストに見つからなかったとき
                    //echo "一位だったら、チェックポイント";

                    $subject = "おめでとうございます！Buediaです！";
                    $message = "おめでとうございます！\n" . $text . "です！" . "\n\n今日も一日頑張りましょう！";
                    mb_language('uni');
                    mb_internal_encoding('UTF-8');
                    mb_send_mail($to, $subject, $message);
                } else {
                    //一位以外の場合
                    $lp = "ラッキーポイント";
                    if (mb_strpos($text, $lp) !== FALSE) {
                        //$posi=mb_strpos($text,$lp);
                        $gettext = mb_stristr($text, $lp);
                        $message = "今日の{$myseiza}の" . $gettext . "です！" . "\n\n今日も一日頑張りましょう！";
                        //htmlspecialchars($statuses[0]->user->name);
                        mb_language('uni');
                        mb_internal_encoding('UTF-8');
                        mb_send_mail($to, $subject, $message);
                    } else {
                        $text = "星座に関するワードは見当たりますが、エラーです";
                        mb_send_mail($to, $subject, $text);
                    }
                }
            }
        } else {
            //$myseizaが$textにないとき
            //$text = "別のことをつぶやいています";
            //$message = $text;
           // mb_send_mail($to, $subject, $message);
        }
    }
}catch(Exception $e){
    echo '<span class="error">insertエラーがありました</span><br>';
    echo $e->getMessage();
    exit();
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf8mb4">
    <title><?php echo htmlspecialchars($statuses[0]->user->name); ?>'s timeline</title>
</head>
<body>
<?php if(isset($error)): echo $error; ?>
<?php else: ?>
    <ul>
        <?php foreach ($statuses as $status): ?>
            <li><?php echo htmlspecialchars($status->text); ?> </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
</body>
</html>
