<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2008 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: search_pdp_include_button.php
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

$form_elements['pdp']['enabled'] = array("datelimit", "fields1", "fields2", "fields3", "sort", "order1", "order2", "chars");
$form_elements['pdp']['disabled'] = array();
$form_elements['pdp']['display'] = array();
$form_elements['pdp']['nodisplay'] = array();

$radio_button['pdp'] = "<label><input type='radio' name='stype' value='pdp'".($_GET['stype'] == "pdp" ? " checked='checked'" : "")." onclick=\"display(this.value)\" /> ".$locale['pdp400']."</label>";
?>