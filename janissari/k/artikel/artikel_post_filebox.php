<?php
///////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////
/////// SISFOKOL_SMK_v4.0_(NyurungBAN)                          ///////
/////// (Sistem Informasi Sekolah untuk SMK)                    ///////
///////////////////////////////////////////////////////////////////////
/////// Dibuat oleh :                                           ///////
/////// Agus Muhajir, S.Kom                                     ///////
/////// URL 	:                                               ///////
///////     * http://sisfokol.wordpress.com/                    ///////
///////     * http://hajirodeon.wordpress.com/                  ///////
///////     * http://yahoogroup.com/groups/sisfokol/            ///////
///////     * http://yahoogroup.com/groups/linuxbiasawae/       ///////
/////// E-Mail	:                                               ///////
///////     * hajirodeon@yahoo.com                              ///////
///////     * hajirodeon@gmail.com                              ///////
/////// HP/SMS	: 081-829-88-54                                 ///////
///////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////



 



//fungsi - fungsi
require("../../../inc/config.php");
require("../../../inc/fungsi.php");
require("../../../inc/koneksi.php");
$tpl = LoadTpl("../../../template/window.html");


nocache;

//nilai
$filenya = "artikel_post_filebox.php";
$judul = "FileBox Image (.jpg) :";
$judulku = $judul;
$juduly = $judul;
$s = nosql($_REQUEST['s']);
$artkd = nosql($_REQUEST['artkd']);
$filekd = nosql($_REQUEST['filekd']);
$ke = "$filenya?artkd=$artkd";


//focus....focus...
$diload = "document.formx.filex.focus();";







//PROSES /////////////////////////////////////////////////////////////////////////////////////////////////
//hapus
if ($s == "hapus")
	{
	//nilai
	$artkd = nosql($_REQUEST['artkd']);
	$filekd = nosql($_REQUEST['filekd']);

	//query
	$qcc = mysql_query("SELECT * FROM user_blog_artikel_filebox ".
							"WHERE kd_artikel = '$artkd' ".
							"AND kd = '$filekd'");
	$rcc = mysql_fetch_assoc($qcc);

	//hapus file
	$cc_filex = $rcc['filex'];
	$path1 = "../../../filebox/artikel/$artkd/$cc_filex";
	unlink ($path1);

	//hapus query
	mysql_query("DELETE FROM user_blog_artikel_filebox ".
					"WHERE kd_artikel = '$artkd' ".
					"AND kd = '$filekd'");

	//null-kan
	xclose($koneksi);

	//re-direct
	xloc($ke);
	exit();
	}





//upload image
if ($_POST['btnUPL'])
	{
	//ambil nilai
	$artkd = nosql($_POST['artkd']);
	$filex_namex = strip(strtolower($_FILES['filex']['name']));

	//nek null
	if (empty($filex_namex))
		{
		//null-kan
		xclose($koneksi);

		//re-direct
		$pesan = "Input Tidak Lengkap. Harap Diulangi...!!";
		pekem($pesan,$ke);
		exit();
		}
	else
		{
		//deteksi .jpg
		$ext_filex = substr($filex_namex, -4);

		if ($ext_filex == ".jpg")
			{
			//nilai
			$filex1 = "../../../filebox/artikel/$artkd/$filex_namex";

			//cek, sudah ada belum
			if (!file_exists($filex1))
				{
				//mengkopi file
				move_uploaded_file($_FILES['filex']['tmp_name'],"../../../filebox/artikel/$artkd/$filex_namex");

				//query
				mysql_query("INSERT INTO user_blog_artikel_filebox(kd, kd_artikel, filex) VALUES ".
								"('$x', '$artkd', '$filex_namex')");

				//null-kan
				xclose($koneksi);

				//re-direct
				xloc($ke);
				exit();
				}
			else
				{
				//null-kan
				xclose($koneksi);

				//re-direct
				$pesan = "File : $filex_namex, Sudah Ada. Ganti Yang Lain...!!";
				pekem($pesan,$ke);
				exit();
				}
			}
		else
			{
			//null-kan
			xclose($koneksi);

			//salah
			$pesan = "Bukan FIle Image .jpg . Harap Diperhatikan...!!";
			pekem($pesan,$ke);
			exit();
			}
		}
	}
//////////////////////////////////////////////////////////////////////////////////////////////////////////




//isi *START
ob_start();

//js
require("../../../inc/js/jumpmenu.js");
require("../../../inc/js/swap.js");



//view //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
echo '<form name="formx" method="post" enctype="multipart/form-data" action="'.$filenya.'">
<table bgcolor="'.$warnaover.'" width="100%" border="0" cellpadding="3" cellspacing="0">
<tr>
<td>';

xheadline($judul);
echo '<br>
<input name="filex" type="file" size="30">
<input name="artkd" type="hidden" value="'.$artkd.'">
<input name="btnUPL" type="submit" value="UPLOAD">
</td>
</tr>
</table>

<table bgcolor="'.$warna02.'" width="100%" height="150" border="0" cellpadding="3" cellspacing="0">
<tr>
<td>';

//koleksi file
$qfle = mysql_query("SELECT * FROM user_blog_artikel_filebox ".
						"WHERE kd_artikel = '$artkd' ".
						"ORDER BY filex ASC");
$rfle = mysql_fetch_assoc($qfle);
$tfle = mysql_num_rows($qfle);

//nek gak null
if ($tfle != 0)
	{
	do
		{
		//nilai
		$nomer = $nomer + 1;
		$fle_kd = nosql($rfle['kd']);
		$fle_filex = $rfle['filex'];

		echo '* <input name="filex'.$nomer.'" type="text" value="'.$sumber.'/filebox/artikel/'.$artkd.'/'.$fle_filex.'" size="75" readonly="true">';
		echo '  [<a href="'.$ke.'&s=hapus&filekd='.$fle_kd.'">HAPUS</a>]';
		echo '<br><br>';
		}
	while ($rfle = mysql_fetch_assoc($qfle));
	}
while ($rfle = mysql_fetch_assoc($qfle));


echo '</td>
</tr>
</table>

<table bgcolor="'.$warnaheader.'" width="100%" border="0" cellpadding="3" cellspacing="0">
<tr>
<td>
<input name="btnKLR" type="button" value="KELUAR" onClick="window.close();">
</td>
</tr>
</table>
<br><br><br>';

//isi
$isi = ob_get_contents();
ob_end_clean();


require("../../../inc/niltpl.php");


//null-kan
xclose($koneksi);
exit();
?>