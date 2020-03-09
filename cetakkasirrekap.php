<?php

require_once('./ascii_table.php');
require_once('./Helper.php');

$ascii_table = new Ascii_Table();
$helper = new Helper();

$kasir = json_decode($_POST["kasir"]);
$kasirtagihan = json_decode($_POST["kasirtagihan"]);
$profilrs = json_decode($_POST["profilrs"]);
$petugas = $_POST["petugas"];

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

$pasien_norm = str_split($kasir->pasien_norm, 2);
$pasien_norm = $pasien_norm[1] . "-" . $pasien_norm[2] . "-" . $pasien_norm[3];

$lebar = "";
$umur = $helper->getUmur($kasir->pasien_tgllahir);

$Data  = $initialized;
$Data .= $condensed1;

$height = 50;

$text = "\n";
$text .= "\n";
$text .= "\n";
$text .= "\n";
$text .= "\n";
$title = $tall14."*BUKTI PEMBAYARAN*".$tall0;
$aligncenter = $ascii_table->aligncenter(65, $title);
$text .= $aligncenter . "\n";
$aligncenter = $ascii_table->aligncenter(65, "----------------------------------------");
$text .= $aligncenter . "\n";
$kasirno = $tall10.$kasir->kasir_no.$tall0;
$aligncenter = $ascii_table->aligncenter(65, $kasirno);
$text .= $aligncenter . "\n";
$text .= "  Nama          : ".$kasir->pasien_nama."\n";
$text .= "  Alamat        : ".$kasir->pasien_alamat."\n";
$text .= "  Tgl. Lahir    : ".date("d-m-Y", strtotime($kasir->pasien_tgllahir))."\n";
$text .= "  Umur          : ". $umur."\n";
$text .= "  Jaminan       : ".$kasir->jaminan_nama."\n";
$text .= "  No. Jmn       :\n";
$text .= "--------------------------------------------------------------------\n";
$text .= "  NO      PELAYANAN                                    HARGA  \n";
$text .= "--------------------------------------------------------------------\n";
$no = 1;
$harga = 0;

// $kasirtagihan = (array)$kasirtagihan;
$arr = array();
foreach ($kasirtagihan as $key => &$entry) {
    $arr[$entry->tagihan_jenis][$key] = $entry;
}
$arr = array();
foreach ($kasirtagihan as $key => $item) {
   $arr[$item->tagihan_jenis][$key] = $item;
}

foreach ($arr as $key => $value) {
	$alignright = $ascii_table->alignright(4, $no, 0);
	$text .= $alignright.".";
	$sub=0;
	if ($key == "ADM. REGISTRASI") {
		$text .= "[ADM]" . "\n";
	} elseif ($key == "TINDAKAN PASIEN") {
		$text .= "[TDK]" . "\n";
	} elseif ($key == "OBAT PASIEN" || $key == "FARMASI") {
		$text .= "[FAR]" . "\n";
	} elseif ($key == "TINDAKAN LAB") {
		$text .= "[LAB]" . "\n";
	}
	foreach ($value as $keytag => $valtag) {
		$alignright = $ascii_table->alignright(5, " ", 0);
		$text .= $alignright;
		$m_trans_nama = $valtag->m_trans_nama;
		$alignleft = $ascii_table->alignleft(2, $m_trans_nama, 50);
		$text .= $alignleft;
		$alignright = $ascii_table->alignright(15, number_format($valtag->tagihan_nilai), 2);
		$text .= $alignright."\n";
		$sub++;
	}
	$no++;
}
$text .= "--------------------------------------------------------------------\n";
$alignright = $ascii_table->alignright(50, "TOTAL : ", 2);
$text .= $alignright;
$alignright = $ascii_table->alignright(20, number_format($kasir->kasir_total_bayar), 2);
$text .= $alignright . "\n";
$text .= "--------------------------------------------------------------------\n";
$text .= "  " . $petugas . " (". date("d-m-Y H:m:s") .")\n";
$text .= str_repeat(' ', 35);
$tempattgl = "JAKARTA, " . $helper->tgl_indo(date("d-m-Y"));
$aligncenter = $ascii_table->aligncenter(30, $tempattgl);
$text .= $aligncenter . "\n";
$text .= "\n";
$text .= "\n";
$text .= "\n";
$text .= "\n";
$text .= str_repeat(' ', 35);
$aligncenter = $ascii_table->aligncenter(30, $petugas);
$text .= $aligncenter . "\n";

$footer = "\n";
$footer .= "\n";
$footer .= "\n";
$footer .= "\n";

$result = $ascii_table->addPage(54, 0, $text, $footer);


fwrite($handle, $Data . $result);
fclose($handle);
$printer = $profilrs->sadministratorrs_printdaftar;
copy($file, $printer);  # Lakukan cetak
unlink($file);
?>
<script type="text/javascript">
	window.close();
</script>