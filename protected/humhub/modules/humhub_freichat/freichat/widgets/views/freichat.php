<?php

$name = base64_encode($name);
$avatar = base64_encode("https://api.adorable.io/avatars/60/{$name}.png");
$pubKey = explode("$$", $token)[0];
$change = date('Ymd');

echo <<<EOL
<script type="text/javascript">
    import('{$baseUrl}/v1/freichat-float.js?pubKey=$pubKey&userId=$id&displayName=$name&displayImage=$avatar&change=$change');
</script>
EOL;
