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



 


session_start();

//ambil nilai
require("../../../inc/config.php");
require("../../../inc/fungsi.php");
require("../../../inc/koneksi.php");
require("../../../inc/cek/janissari.php");
require("../../../inc/class/paging.php");
require("../../../inc/class/pagingx.php");
$tpl = LoadTpl("../../../template/janissari.html");

nocache;

//nilai
$filenya = "temanku.php";
$judul = "Daftar Temanku";
$judulku = "[$tipe_session : $no1_session.$nm1_session] ==> $judul";
$juduli = $judul;
$stkd = nosql($_REQUEST['stkd']);
$s = nosql($_REQUEST['s']);
$bk = nosql($_REQUEST['bk']);
$a = nosql($_REQUEST['a']);
$msgkd = nosql($_REQUEST['msgkd']);
$page = nosql($_REQUEST['page']);
if ((empty($page)) OR ($page == "0"))
	{
	$page = "1";
	}

$ke = "$filenya?page=$page";



//focus...
if ($bk == "teman")
	{
	$diload = "document.formx.bk_teman.focus();";
	}






//PROSES ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//reset
if ($_POST['btnRST'])
	{
	//re-direct
	$ke = "teman_cari.php";
	xloc($ke);
	exit();
	}





//cari
if ($_POST['btnCRI'])
	{
	//nilai
	$katcari = nosql($_POST['katcari']);
	$kunci = cegah2($_POST['kunci']);

	//cek empty
	if ((empty($katcari)) OR (empty($kunci)))
		{
		//re-direct
		$pesan = "Input Tidak Lengkap. Harap Diperhatikan...!!";
		pekem($pesan,$filenya);
		exit();
		}
	else
		{
		//re-direct
		$ke = "teman_cari.php?katcari=$katcari&kunci=$kunci";
		xloc($ke);
		exit();
		}
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////





//isi *START
ob_start();




require("../../../inc/js/swap.js");
require("../../../inc/menu/janissari.php");


//view //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
echo '<table width="100%" height="300" border="0" cellspacing="0" cellpadding="3">
<tr bgcolor="#FDF0DE" valign="top">
<td>';
//judul
xheadline($judul);

echo '<table width="100%" border="0" cellspacing="0" cellpadding="3">
<tr valign="top">
<td>
<select name="katcari">
<option value="" selected></option>
<option value="cr01">NIP/NIS</option>
<option value="cr02">Nama</option>
</select>
<input name="kunci" type="text" size="20">
<input name="btnCRI" type="submit" value="CARI">
<input name="btnRST" type="submit" value="Daftar Semua">
</td>
</tr>
</table>
<br>';


//query
$p = new Pager();
$start = $p->findStart($limit);

$sqlcount = "SELECT m_user.*, m_user.kd AS uskd, user_blog_teman.* ".
				"FROM m_user, user_blog_teman ".
				"WHERE user_blog_teman.kd_user_teman = m_user.kd ".
				"AND user_blog_teman.kd_user = '$kd1_session' ".
				"ORDER BY m_user.nama ASC";
$sqlresult = $sqlcount;

$count = mysql_num_rows(mysql_query($sqlcount));
$pages = $p->findPages($count, $limit);
$result = mysql_query("$sqlresult LIMIT ".$start.", ".$limit);
$target = $ke;
$pagelist = $p->pageList($_GET['page'], $pages, $target);
$data = mysql_fetch_array($result);


//nek ada
if ($count != 0)
	{
	echo '<table width="100%" border="0" cellpadding="3" cellspacing="0">';

	do
  		{
		$nomer = $nomer + 1;

		$kd = nosql($data['uskd']);
		$nisp = nosql($data['nomor']);
		$nama = balikin($data['nama']);
		$tipe = balikin($data['tipe']);


		//status-nya
		$qstu = mysql_query("SELECT * FROM user_blog_status ".
								"WHERE kd_user = '$kd' ".
								"ORDER BY postdate DESC");
		$rstu = mysql_fetch_assoc($qstu);
		$tstu = mysql_num_rows($qstu);
		$stu_kd = nosql($rstu['kd']);

		//nek null
		if ($tstu != 0)
			{
			$stu_status = balikin($rstu['status']);
			}
		else
			{
			$stu_status = "-";
			}

		//jumlah komentar
		$qitusx = mysql_query("SELECT * FROM user_blog_status_msg ".
									"WHERE kd_user_blog_status = '$stu_kd' ".
									"ORDER BY postdate ASC");
		$ritusx = mysql_fetch_assoc($qitusx);
		$titusx = mysql_num_rows($qitusx);


		//user_blog
		$qtuse = mysql_query("SELECT * FROM user_blog ".
								"WHERE kd_user = '$kd'");
		$rtuse = mysql_fetch_assoc($qtuse);
		$tuse_kd = nosql($rtuse['kd']);
		$tuse_foto_path = $rtuse['foto_path'];


		//nek null foto
		if (empty($tuse_foto_path))
			{
			$nilx_foto = "$sumber/img/foto_blank.jpg";
			}
		else
			{
			//gawe mini thumnail
			$nilx_foto = "$sumber/filebox/profil/$kd/thumb_$tuse_foto_path";
			}

		echo "<tr valign=\"top\" bgcolor=\"$warna\" onmouseover=\"this.bgColor='$warnaover';\" onmouseout=\"this.bgColor='$warna';\">";
		echo '<td valign="top">

		<table width="100%" border="0" cellspacing="3" cellpadding="0">
		<tr valign="top">
		<td width="50" valign="top">
		<a href="'.$sumber.'/janissari/p/index.php?uskd='.$kd.'" title="'.$nisp.'. '.$nama.' ['.$tipe.']">
		<img src="'.$nilx_foto.'" align="left" alt="'.$tuse_nm.'" width="50" height="75" border="1">
		</a>
		</td>
		<td valign="top">

		<a name="'.$stu_kd.'"></a>
		<big><em>'.$stu_status.'</em></big>
		<br>
		<big>
		<strong>
		<a href="'.$sumber.'/janissari/p/index.php?uskd='.$kd.'" title="'.$nisp.'. '.$nama.' ['.$tipe.']">'.$nama.' ['.$nisp.'. '.$tipe.']</a>
		</strong>
		</big>

		<br>';

		//jika belum ada status
		if ($stu_status == "-")
			{
			echo '<font color="red"><strong>BELUM ADA STATUS.</strong></font>';
			}
		else
			{
			echo '[<a href="'.$filenya.'?page='.$page.'&s=view&stkd='.$stu_kd.'#'.$stu_kd.'" title="('.$titusx.') Komentar">(<strong>'.$titusx.'</strong>) Komentar</a>].
			[<a href="'.$filenya.'?page='.$page.'&bk=teman&stkd='.$stu_kd.'#'.$stu_kd.'" title="Beri Komentar">Beri Komentar</a>]. ';

			//jika view
			if (($s == "view") AND ($stkd == $stu_kd))
				{
				//jika ada
				if ($titusx != 0)
					{
					echo '<table width="100%" border="0" cellspacing="3" cellpadding="0">
					<tr valign="top">
					<td width="50">&nbsp;</td>
					<td>';

					do
						{
						$itusx_kd = nosql($ritusx['kd']);
						$itusx_msg = balikin2($ritusx['msg']);
						$itusx_dari = nosql($ritusx['dari']);
						$itusx_postdate = $ritusx['postdate'];

						//user-nya
						$qtuse = mysql_query("SELECT m_user.*, m_user.kd AS uskd, ".
												"user_blog.* ".
												"FROM m_user, user_blog ".
												"WHERE user_blog.kd_user = m_user.kd ".
												"AND m_user.kd = '$itusx_dari'");
						$rtuse = mysql_fetch_assoc($qtuse);
						$tuse_kd = nosql($rtuse['uskd']);
						$tuse_no = nosql($rtuse['nomor']);
						$tuse_nm = balikin($rtuse['nama']);
						$tuse_tipe = nosql($rtuse['tipe']);
						$tuse_foto_path = $rtuse['foto_path'];

						//nek null foto
						if (empty($tuse_foto_path))
							{
							$nilz_foto = "$sumber/img/foto_blank.jpg";
							}
						else
							{
							//gawe mini thumnail
							$nilz_foto = "$sumber/filebox/profil/$tuse_kd/thumb_$tuse_foto_path";
							}


						echo '<table bgcolor="#FDF0DE" width="100%" border="0" cellspacing="3" cellpadding="0">
						<tr valign="top">
						<td width="50" valign="top">
						<a href="'.$sumber.'/janissari/p/index.php?uskd='.$tuse_kd.'" title="('.$tuse_tipe.'). '.$tuse_no.'. '.$tuse_nm.'">
						<img src="'.$nilz_foto.'" align="left" alt="'.$tuse_nm.'" width="50" height="75" border="1">
						</a>
						</td>
						<td valign="top">
						<em>'.$itusx_msg.'. <br>
						[Oleh : <strong><a href="'.$sumber.'/janissari/p/index.php?uskd='.$tuse_kd.'" title="('.$tuse_tipe.'). '.$tuse_no.'. '.$tuse_nm.'">('.$tuse_tipe.'). '.$tuse_no.'. '.$tuse_nm.'</a></strong>].
						['.$itusx_postdate.'].</em>
						<br><br>
						</td>
						</tr>
						</table>
						<br>';
						}
					while ($ritusx = mysql_fetch_assoc($qitusx));

					echo '</td>
					</tr>
					</table>';
					}

				//jika tidak ada msg
				else
					{
					echo '<br>
					<font color="red"><strong>TIDAK ADA KOMENTAR</strong></font>';
					}
				}


			//jika beri komentar
			else if (($bk == "teman") AND ($stkd == $stu_kd))
				{
				//jika batal
				if ($_POST['btnBTL'])
					{
					//nilai
					$stkd = nosql($_POST['stkd']);
					$page = nosql($_POST['page']);

					//re-direct
					$ke = "$filenya?page=$page&s=view&stkd=$stkd#$stkd";
					xloc($ke);
					exit();
					}


				//jika simpan
				if ($_POST['btnSMP1'])
					{
					//nilai
					$bk_teman = cegah($_POST['bk_teman']);
					$stkd = nosql($_POST['stkd']);
					$page = nosql($_POST['page']);

					//query
					mysql_query("INSERT INTO user_blog_status_msg(kd, kd_user_blog_status, dari, msg, postdate) VALUES ".
									"('$x', '$stkd', '$kd1_session', '$bk_teman', '$today')");

					//re-direct
					$ke = "$filenya?page=$page&s=view&stkd=$stkd#$stkd";
					xloc($ke);
					exit();
					}


				//view
				echo '<br>
				<textarea name="bk_teman" cols="50" rows="5" wrap="virtual"></textarea>
				<br>
				<input name="stkd" type="hidden" value="'.$stkd.'">
				<input name="bk" type="hidden" value="'.$bk.'">
				<input name="page" type="hidden" value="'.$page.'">
				<input name="btnSMP1" type="submit" value="SIMPAN">
				<input name="btnBTL" type="submit" value="BATAL">';
				}
			}

		echo '<br>
		<br>
		</td>
		</tr>
		</table>

		</td>
		</tr>';
  		}
	while ($data = mysql_fetch_assoc($result));

	echo '</table>
	<table width="100%" border="0" cellspacing="0" cellpadding="3">
    	<tr>
	<td align="right">
	<hr>
	<font color="#FF0000"><strong>'.$count.'</strong></font> Data '.$pagelist.'
	<hr>
	</td>
    	</tr>
	</table>';
	}
else
	{
	echo '<font color="red"><strong>BELUM PUNYA TEMAN. Silahkan Cari Dahulu...!!</strong></font>';
	}



echo '<br>
<br>
<br>
</td>
<td width="1%">
</td>

<td width="1%">';

//ambil sisi
require("../../../inc/menu/k_sisi.php");

echo '<br>
<br>
<br>

</td>
</tr>
</table>';
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//isi
$isi = ob_get_contents();
ob_end_clean();

require("../../../inc/niltpl.php");



//diskonek
xfree($qbw);
xclose($koneksi);
exit();
?>