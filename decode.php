<?php
	$dekrip;
	if(isset($_FILES['file'])){
		require_once 'convert.php';
		$kunci = $_POST['kunci'];
		$fp = fopen($_FILES['file']['tmp_name'], 'rb');
	    $stgobj = fread($fp, filesize($_FILES['file']['tmp_name']));
	    $stgobjarr = str_split($stgobj);
	    $i = 0;
	    $hasil = "";

	    while($i < count($stgobjarr)){
	    	if($stgobjarr[$i] == chr(160)){
	    		if($i+1 < count($stgobjarr)){
	    			$temp = ord($stgobjarr[$i+1]) % 2;
	    			$hasil .= $temp;
	    			$stgobjarr[$i] = chr(32);
	    		}
	    	}
	    	$i++;
	    }
	    $pesan = binToStr($hasil);
	    $dekrip = decrypt($kunci, $pesan);
	    //echo $dekrip;
	}

	function binToStr($input)
	{
	    if (!is_string($input))
	        return false;
	    $chunks = str_split($input,8);
	    $ret = '';
	    foreach ($chunks as $chunk)
	    {
	        $temp = base_convert($chunk, 2, 16);
	        $ret .= pack('H*',str_repeat("0", 2 - strlen($temp)) . $temp);
	    }
	    return $ret;
	}


?>

<html>
	<head>
		<title>
			Decode Pesan
		</title>
	</head>
	<body>
		<form action="" method="post" enctype="multipart/form-data">
			<label for="kunci">Kunci Enkripsi:</label> <input type="text" name="kunci" id="kunci"/>
			<label for="file">Filename:</label> <input type="file" name="file" id="file"/>
			<input type="submit" value="Submit">
		</form>
		<?php
            if(isset($dekrip))
                echo "Pesan Rahasia dalam Stego Object: $dekrip <br>";
        ?>
        <a href="index.php">Kembali ke menu awal</a>
	</body>
</html>