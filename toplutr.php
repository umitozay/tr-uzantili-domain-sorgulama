<!DOCTYPE html>
<html>
<head>
    <title>TR Uzantılı Domain Sorgulama</title>
</head>
<body>
    <h1>TR Uzantılı Domain Sorgulama</h1>
    <form method="post">
        <textarea name="domainList" rows="10" cols="30"></textarea>
        <br>
        <input type="submit" name="sorgula" value="Sorgula">
    </form>
    
    <div id="sonuc">
        <?php
        if (isset($_POST['sorgula'])) {
            $domainList = $_POST['domainList'];
            $domainArray = explode("\n", $domainList);
            
            foreach ($domainArray as $domain) {
                $domain = trim($domain);
                if (substr($domain, -3) === '.tr') {
                    $sonuc = sorgulaDomain($domain);
                    echo "<p><span style='color:red;font-weight:bold;'>$domain:</span> $sonuc</p>";
                }
            }
        }

        function sorgulaDomain($domain) {
            $whois_server = 'whois.nic.tr'; // .tr uzantılı domainler için NIC.TR WHOIS sunucusu
            $port = 43;
            $timeout = 10;

            // WHOIS sunucusuna bağlanma
            $fp = fsockopen($whois_server, $port, $errno, $errstr, $timeout);
            if (!$fp) {
                return "Bağlantı hatası: $errno - $errstr";
            }

            // WHOIS sorgusu gönderme
            fwrite($fp, $domain . "\r\n");

            // Yanıtı alın
            $response = '';
            while (!feof($fp)) {
                $response .= fgets($fp, 128);
            }

            // Bağlantıyı kapat
            fclose($fp);

            return $response;
        }
        ?>
    </div>
</body>
</html>
