<?php  


/**
 * 
 */
class Helper
{

	function tgl_indo($tanggal){
		$bulan = array (
			1 =>   'Januari',
					'Februari',
					'Maret',
					'April',
					'Mei',
					'Juni',
					'Juli',
					'Agustus',
					'September',
					'Oktober',
					'November',
					'Desember'
		);
		$pecahkan = explode('-', $tanggal);

		// variabel pecahkan 0 = tanggal
		// variabel pecahkan 1 = bulan
		// variabel pecahkan 2 = tahun

		return $pecahkan[0] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[2];
	}

	function getUmur($tanggal)
	{
	  	//date in mm/dd/yyyy format; or it can be in other formats as well
	  	$age = "";
		if ($tanggal <> "") {
		  $birthDate = date("d-m-Y", strtotime($tanggal));
		  //explode the date to get month, day and year
		  $birthDate = explode("-", $birthDate);
		  //get age from date or birthdate
		  $age = (date("md", date("U", mktime(0, 0, 0, $birthDate[0], $birthDate[1], $birthDate[2]))) > date("md")
		    ? ((date("Y") - $birthDate[2]) - 1)
		    : (date("Y") - $birthDate[2]));
		}

	  	return $age;
	}

}