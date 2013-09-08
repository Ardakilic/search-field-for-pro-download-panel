<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2008 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: search_pdp_include.php
| Author: Arda Kılıçdağı (SoulSmasher)
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/
if (!defined("IN_FUSION")) { die("Access Denied"); }

include LOCALE.LOCALESET."search/pdp.php";
include_once INFUSIONS."pro_download_panel/infusion_db.php";

if ($_GET['stype'] == "pdp" || $_GET['stype'] == "all") {
	if ($_GET['sort'] == "datestamp") {
		$sortby = "dl_ctime";
	} else if ($_GET['sort'] == "subject") {
		$sortby = "dl_name";
	} else if ($_GET['sort'] == "author") {
		$sortby = "user_id";
	}
	$ssubject = search_querylike("dl_name");
	$smessage = search_querylike("dl_desc");
	$sabstract = search_querylike("dl_abstract");
	if ($_GET['fields'] == 0) {
		$fieldsvar = search_fieldsvar($ssubject);
	} else if ($_GET['fields'] == 1) {
		$fieldsvar = search_fieldsvar($smessage, $smessage);
	} else if ($_GET['fields'] == 2) {
		$fieldsvar = search_fieldsvar($ssubject, $smessage, $sabstract);
	} else {
		$fieldsvar = "";
	}
	if ($fieldsvar) {
		//$rows = dbcount("(download_id)", DB_PDP_DOWNLOADS, groupaccess('news_visibility')." AND ".$fieldsvar.($_GET['datelimit'] != 0 ? " AND dl_ctime>=".(time() - $_GET['datelimit']) : ""));
		$result=dbquery("
			SELECT pdp.*,pdpc.* FROM ".DB_PDP_DOWNLOADS." pdp 
			INNER JOIN ".DB_PDP_CATS." pdpc ON pdp.cat_id=pdpc.cat_id
			WHERE ".groupaccess("cat_download_access")." AND ".$fieldsvar."
			".($_GET['datelimit'] != 0 ? " AND dl_mtime>=".(time() - $_GET['datelimit']):"")
			);
		$rows=dbrows($result);
	} else {
		$rows = 0;
	}
	if ($rows != 0) {
		$items_count .= THEME_BULLET."&nbsp;<a href='".INFUSIONS."pro_download_panel/search.php?stext=".$_GET['stext']."&amp;".$composevars."'>".$rows." ".($rows == 1 ? $locale['pdp401'] : $locale['pdp402'])." ".$locale['522']."</a><br />\n";
		$result = dbquery("
			SELECT pdp.*,pdpc.*,u.user_id,u.user_name FROM ".DB_PDP_DOWNLOADS." pdp 
			INNER JOIN ".DB_PDP_CATS." pdpc ON pdp.cat_id=pdpc.cat_id
			LEFT JOIN ".DB_USERS." u ON u.user_id=pdp.user_id
			WHERE ".groupaccess("cat_download_access")." AND ".$fieldsvar."
			".($_GET['datelimit'] != 0 ? " AND dl_mtime>=".(time() - $_GET['datelimit']):"")."
			ORDER BY ".$sortby." ".($_GET['order'] != 1 ? "ASC":"DESC").($_GET['stype'] != "all" ? " LIMIT ".$_GET['rowstart'].",10" : "")
		);
		while ($data = dbarray($result)) {
			$search_result = "";
			$text_all = $data['dl_name']." ".$data['dl_desc'];
			$text_all = search_striphtmlbbcodes($text_all);
			$text_frag = search_textfrag($text_all);
			$text_frag = highlight_words($swords, $text_frag);
			$subj_c = search_stringscount($data['dl_name']);
			$text_c = search_stringscount($data['dl_desc']);
			$text_c2 = search_stringscount($data['dl_abstract']);

			$search_result .= "<a href='".INFUSIONS."pro_download_panel/download.php?did=".$data['download_id']."'>".highlight_words($swords, $data['dl_name'])."</a>"."<br /><br />\n";
			$search_result .= "<div class='quote' style='width:auto;height:auto;overflow:auto'>".$text_frag."</div><br />";
			$search_result .= "<span class='small2'>".$locale['global_070']."<a href='profile.php?lookup=".$data['user_id']."'>".$data['user_name']."</a>\n";
			$search_result .= $locale['global_071'].showdate("longdate", $data['dl_ctime'])."</span><br />\n";
			$search_result .= "<span class='small'>".$subj_c." ".($subj_c == 1 ? $locale['520'] : $locale['521'])." ".$locale['pdp403']." ".$locale['pdp404'].", ";
			$search_result .= $text_c." ".($text_c == 1 ? $locale['520'] : $locale['521'])." ".$locale['pdp403']." ".$locale['pdp405'].", ";
			$search_result .= $text_c2." ".($text_c2 == 1 ? $locale['520'] : $locale['521'])." ".$locale['pdp403']." ".$locale['pdp406']."</span><br /><br />\n";
			search_globalarray($search_result);
		}
	} else {
		$items_count .= THEME_BULLET."&nbsp;0 ".$locale['pdp402']." ".$locale['522']."<br />\n";
	}
	$navigation_result = search_navigation($rows);
}
?>