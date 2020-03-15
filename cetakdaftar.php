<?php
require_once('./ascii_table.php');
$ascii_table = new Ascii_Table();

$daftar = json_decode($_POST["daftar"]);
$tindakan_masuk = json_decode($_POST["tindakan_masuk"]);
$administrasi_masuk = json_decode($_POST["administrasi_masuk"]);
$profilrs = json_decode($_POST["profilrs"]);
$umur = json_decode($_POST["umur"]);

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

$Data  = $initialized;
$Data .= $condensed1;
$Data .= "\n\n\n";
$Data .= "  --------------------------------------------------------------------------\n";
$Data .= "                       ".$tall14."*BUKTI REGISTRASI*".$tall0.            "\n";
$Data .= "                              ".$tall10.$daftar->daftar_no.$tall0."\n";
$Data .= "                               ".$tall10.$pasien_norm.$tall0."\n";
$Data .= "  --------------------------------------------------------------------------\n";
$Data .= "  Nama          : ".$daftar->pasien_nama."\n";
$Data .= "  Alamat        : ".$daftar->pasien_alamat."\n";
$Data .= "  Tgl. Lahir    : ".$daftar->pasien_tgllahir."\n";
$Data .= "  Umur          : ".$umur."\n";
$Data .= "  Jaminan       : ".$daftar->jaminan_nama."\n";
$Data .= "  No. Jmn       :\n";
$Data .= "  --------------------------------------------------------------------------\n";

$kolom1 = strlen("  --------------------------------------");
$alignleft = $ascii_table->alignleft(10, "Administrasi", $kolom1);
$Data .= $alignleft . ": Rp.";
$kolom2 = strlen("------------------------------------");
$curr_adm_masuk = number_format($administrasi_masuk->tagihan_nilai);
$alignright = $ascii_table->alignright($kolom2, $curr_adm_masuk, 10);
$Data .= $alignright."\n";

$alignleft = $ascii_table->alignleft(10, "Konsultasi Dokter 1", $kolom1);
$Data .= $alignleft . ": Rp.";
$curr_tdk_masuk = number_format($tindakan_masuk->tagihan_nilai);
$alignright = $ascii_table->alignright($kolom2, $curr_tdk_masuk, 10);
$Data .= $alignright."\n";

$Data .= "\n";
$Data .= "\n";
$Data .= "\n";
$Data .= "\n";
$Data .= "\n";
$Data .= "\n";
$Data .= "\n";
$Data .= "\n";
$Data .= "\n";
$Data .= "\n";
$Data .= "\n";
$Data .= "\n";
$Data .= "  --------------------------------------------------------------------------\n";
$alignright = $ascii_table->alignright($kolom1, "TOTAL", 2);
$Data .= $alignright.": Rp.";
$curr_total = number_format($tindakan_masuk->tagihan_nilai + $administrasi_masuk->tagihan_nilai);
$alignright = $ascii_table->alignright($kolom2, $curr_total, 10);
$Data .= $alignright."\n";
$Data .= "  --------------------------------------------------------------------------\n";
$Data .= "  Layanan       : " . $daftar->unit_nama . "\n";
$Data .= "  Dokter        : " . $daftar->pegawai_nama . "\n";
$Data .= "  --------------------------------------------------------------------------\n";
$petugas =  $daftar->daftar_updated_by <> null ? $daftar->daftar_updated_by : $daftar->daftar_created_by;
$tglcetak =  $daftar->daftar_updated_date <> null ? strtotime("d-m-Y H:i:s", strtotime($daftar->daftar_updated_date)) :  strtotime("d-m-Y H:i:s", strtotime($daftar->daftar_created_date));
$Data .= "  " . $petugas . " (". $tglcetak .")\n";

$kolom2 = strlen("  --------------------------------------------------------------------------");
$alignright = $ascii_table->alignright($kolom2, 'Para Dokter : ', 10);
$Data .= $alignright."\n";
$Data .= "\n";
$Data .= "\n";
$Data .= "\n";
$Data .= "\n";
$Data .= "\n";
$Data .= "\n";
$Data .= "\n";
$Data .= "\n";
$Data .= "\n";
$Data .= "\n";
$Data .= "\n";
$Data .= "\n";
$Data .= "\n";

fwrite($handle, $Data);
fclose($handle);
$printer = $profilrs->sadministratorrs_printdaftar;
copy($file, $printer);  # Lakukan cetak
unlink($file);
?>
<script type="text/javascript">
	window.close();
</script>