<?php

require_once('./ascii_table.php');
require_once('./Helper.php');

$ascii_table = new Ascii_Table();
$helper = new Helper();

$daftar = json_decode($_POST["daftar"]);
$resep = json_decode($_POST["resep"]);
$resepdetail = json_decode($_POST["resepdetail"]);
$resepracikdetail = json_decode($_POST["resepracikdetail"]);
$petugas = $_POST["petugas"];
$profilrs = json_decode($_POST["profilrs"]);

$tmpdir = sys_get_temp_dir();   # ambil direktori temporary untuk simpan file.
$file =  tempnam($tmpdir, 'ctk');  # nama file temporary yang akan dicetak
$handle = fopen($file, 'w');
$condensed = Chr(27) . Chr(33) . Chr(4);

$initialized = chr(27).chr(64);
$condensed1 = chr(15);
$condensed0 = chr(18);

$bold1 = Chr(27) . Chr(33).(1);
$bold2 = Chr(27) . Chr(33).(2);
$bold0 = $condensed;
$big1 = Chr(27) . Chr(33).chr(32);
$big0 = $condensed;
$tall16 = Chr(27) . Chr(33).chr(16);
$tall15 = Chr(27) . Chr(33).chr(15);
$tall14 = Chr(27) . Chr(33).chr(14);
$tall10 = Chr(27) . Chr(33).chr(10);
$tall0 = $condensed;
$small1 = Chr(27) . Chr(69);
$small0 = Chr(27) . Chr(69).chr(0);

$pasien_norm = str_split($daftar->pasien_norm, 2);
$pasien_norm = $pasien_norm[1] . "-" . $pasien_norm[2] . "-" . $pasien_norm[3];

$lebar = "";
$umur = $helper->getUmur($daftar->pasien_tgllahir);

$Data  = $initialized;
$Data .= $condensed1;

$height = 54;
$text = "\n";
$text .= "\n";
$text .= "\n";
$text .= "\n";
$text .= "\n";
$title = $tall14."*RESEP*".$tall0;

$text .= $ascii_table->aligncenter(80, $title) . "\n";
$text .= $ascii_table->aligncenter(80, $tall10.$resep->resep_no.$tall0) . "\n";

$widthCol1 = 3;
$widthCol2 = 4;
$widthCol3 = 45;
$widthCol4 = 11;
$widthCol5 = 6;
$widthCol6 = 3;

$text .= $ascii_table->aligncenter(80, "-------------------------------------------------------------------------") . "\n";
$text .= $ascii_table->alignleft(2, "|  Nama", 15) . ":";
$text .= $ascii_table->alignleft(2, $daftar->pasien_nama, 58) . "|\n";
$text .= $ascii_table->alignleft(2, "|  Alamat", 15) . ":";
$text .= $ascii_table->alignleft(2, $daftar->pasien_alamat, 58) . "|\n";
$text .= $ascii_table->alignleft(2, "|  Tgl. Lahir", 15) . ":";
$text .= $ascii_table->alignleft(2, $daftar->pasien_tgllahir, 58) . "|\n";
$text .= $ascii_table->alignleft(2, "|  Umur", 15) . ":";
$text .= $ascii_table->alignleft(2, $umur, 58) . "|\n";
$text .= $ascii_table->aligncenter(80, "-------------------------------------------------------------------------") . "\n";
$text .= $ascii_table->alignleft(2, "|", $widthCol1);
$text .= $ascii_table->aligncenter($widthCol2+2, "No.") . "|";
$text .= $ascii_table->alignleft(2, "Nama Obat", $widthCol3) . "|";
$text .= $ascii_table->aligncenter($widthCol4, "Satuan") . "|";
$text .= $ascii_table->aligncenter($widthCol5, "Qty"); 
$text .= $ascii_table->alignright($widthCol6, "|", 2) . "\n";
$text .= $ascii_table->aligncenter(80, "-------------------------------------------------------------------------") . "\n";
$no = 1;
$row = 0;

$minrowobat = 15;
foreach ($resepdetail as $keydet => $valdet) {
	$text .= $ascii_table->alignleft(2, " ", $widthCol1);
	$text .= $ascii_table->alignright($widthCol2+1, ".".$no, 0) . "";
	$barang_nama = $valdet->resepdet_racikan == "Y" ? $valdet->resepdet_racikan_nama : $valdet->barang_nama;
	$text .= $ascii_table->alignleft(2, $barang_nama, $widthCol3) . " ";
	$text .= $ascii_table->alignleft(3, $valdet->satuan_nama, $widthCol4) . " ";
	$text .= $ascii_table->alignright($widthCol5, $valdet->resepdet_jml, 1);
	$text .= $ascii_table->alignright($widthCol6, " ", 2) . "\n";
	$no++;
	$row++;
	$resepdet_id = $valdet->resepdet_id;
	
	foreach ($resepracikdetail as $keyracikdetail => $valracikdetail) {
		if ($resepdet_id==$valracikdetail->t_resepdet_racik_id) {
			$text .= $ascii_table->alignleft(2, " ", $widthCol1);
			$text .= $ascii_table->alignright($widthCol2+1, "  ", 0) . "";
			$text .= $ascii_table->alignleft(2, ">>> ".$valracikdetail->barang_nama . " " . $valracikdetail->resepdet_jml . " " . $valracikdetail->satuan_nama , $widthCol3) . "\n";
			$row++;
		}
	}
}
if ($minrowobat>$row) {
	$text .= str_repeat("\n", $minrowobat);
}
$text .= $ascii_table->aligncenter(80, "-------------------------------------------------------------------------") . "\n";
$text .= "  " . $petugas . " (". date("d-m-Y H:m:s") .")\n";
$text .= str_repeat(' ', 50);
$tempattgl = "JAKARTA, " . $helper->tgl_indo(date("d-m-Y"));
$text .= $ascii_table->aligncenter(30, $tempattgl);
$footer = "\n\n\n\n";

$result = $ascii_table->addPage($height, 0, $text, $footer);

fwrite($handle, $Data . $result);
fclose($handle);
$printer = $profilrs->sadministratorrs_printerfarmasi;
copy($file, $printer);  # Lakukan cetak
unlink($file);
?>
<script type="text/javascript">
	window.close();
</script>