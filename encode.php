<?php
	//TODO
	/*
		1. Form Validation(Vigenere Rules)
		2. Download Link
		3. Cover Text Validation
		4. User Interface(Sesuai Mockup)
		5. Algorithm Understanding
		6. Tutorial Video
	*/
	$text_cover;
	$fname;
	if(isset($_FILES['file'])){
		require_once 'convert.php';
		$kunci = $_POST['kunci'];
		$pesan_asli = $_POST['pesan'];
		$pesan_enkrip = encrypt($kunci, $pesan_asli);
		$binerpesan = strToBin($pesan_enkrip);
		$binerpesanarray = str_split($binerpesan);
		$fp = fopen($_FILES['file']['tmp_name'], 'rb');
	    $text_cover = fread($fp, filesize($_FILES['file']['tmp_name']));
	    $coverarray = str_split($text_cover);

	    $cek = false;
	    $i = 0;
	    $jumlahBit = 0;

	    while($i < count($coverarray)){
	    	if(ord($coverarray[$i]) == 32 ){
	    		if($cek == false){
	    			if(($i+1 < count($coverarray)) && ($jumlahBit < count($binerpesanarray))){
	    				if(ord($coverarray[$i+1]) < 255){
	    					if((ord($coverarray[$i+1]) % 2) == $binerpesanarray[$jumlahBit]){
	    						$coverarray[$i] = chr(160);
	    						$cek = true;
	    						$jumlahBit++;
	    					}
	    				}
	    			}
	    		}else{
	    			$cek = false;
	    		}
	    	}elseif(ord($coverarray[$i]) == 160){
	    		$coverarray[$i] = chr(32);
	    	}
	    	$i++;
	    }

	    if(count($binerpesanarray) != $jumlahBit){
	    	echo "pesan tidak tersisipkan seluruhnya. mohon ganti cover text dengan yang lebih banyak textnya!";
	    }else{
	    	echo "pesan tersisipkan seluruhnya";
	    	$stego_object = implode($coverarray);
		    $times = time();
		    $fname = "stego_object_$times.txt";
		    $myfile = fopen("$fname", "w") or die("Unable to open file!");
			fwrite($myfile, $stego_object);
	    }

	}
	

    function strToBin($input)
	{
	    if (!is_string($input))
	        return false;
	    $input = unpack('H*', $input);
	    $chunks = str_split($input[1], 2);
	    $ret = '';
	    foreach ($chunks as $chunk)
	    {
	        $temp = base_convert($chunk, 16, 2);
	        $ret .= str_repeat("0", 8 - strlen($temp)) . $temp;
	    }
	    return $ret;
	}

    
    
?>
<html>
	<head>
		<title>Test</title>
	</head>
	<body>
		<form action="" method="post" enctype="multipart/form-data">
			<label for="kunci">Kunci Enkripsi:</label> <input type="text" name="kunci" id="kunci"/>
			<label for="pesan">Pesan:</label> <input type="text" name="pesan" id="pesan"/>
			<label for="file">Filename:</label> <input type="file" name="file" id="file"/>
			<input type="submit" value="Submit">
		</form>
		<?php
            if(isset($fname))
                echo "<a href='".$fname."'>Download Stego Object</a><br>";
        ?>
		<a href="index.php">Kembali ke menu awal</a>
	</body>
</html>