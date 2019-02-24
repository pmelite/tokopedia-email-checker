<?php 

	error_reporting(0);

	require ('class.php');

	echo "\n";
	echo "[!] Tokopedia Email Checker ";
	echo "\n\n";

	$options = getopt("l:d:r:");

	if (file_exists($options['r'])) {
				
		echo "file sudah ada bos ganti yg lain";
		die();		

	}

	function check($email,$result) {

		$curl = new curl();
		$curl->cookies('cookies/'.md5($_SERVER['REMOTE_ADDR']).'.txt');
		$curl->ssl(0, 2);
		$curl->timeout(5);
		$home = $curl->post('https://www.tokopedia.com/api/v1/account/register/email/check','email='.$email.'');

		$valid = fetch_value($home,'{"isExist":','}');

		if ($valid === 'true') {

			echo "[$email][email valid]";

			fopen($result, "w");
			file_put_contents($result, $email.PHP_EOL, FILE_APPEND);
			
		} else {

			echo "[$email][tidak valid]";

		}

	}

	$zero = 1;
	$count = count(file($options['l']));

	if ($fh = fopen($options['l'], 'r+')) {

	    while (!feof($fh)) {


	        $line = fgets($fh);
	        $email=trim($line);

	        echo "[+][".$zero++."/".$count."]";
	        check($email,$options['r']);
	        echo "[delay ".$options['d']." detik]";
	        echo "\n";

	        sleep($options['d']);

	    }

	    fclose($fh);

	}

?>