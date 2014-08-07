<?php

// -----------------------------------------------------------------------
// This file is part of Prairie
// 
// Copyright (C) 2003-2008 Barnraiser
// http://www.barnraiser.org/
// info@barnraiser.org
// 
// This program is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
// 
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
// 
// You should have received a copy of the GNU General Public License
// along with this program; see the file COPYING.txt.  If not, see
// <http://www.gnu.org/licenses/>
// -----------------------------------------------------------------------

?>

<?php
$fields=""; 
if ($_SERVER["REQUEST_METHOD"]=="POST"){
	reset ($_POST); 
	foreach ($_POST as $key=>$value) {
		$fields .= input_($key, $value, "hidden"); 
	}
} else {
	reset ($_GET); 
	foreach ($_GET as $key=>$value) {
		$fields .= input_($key, $value, "hidden"); 
	}
}

if (isset($trust)) {

$trust_string = _("You are about to login to <a href='{1}'>{1}</a>. You have done this {2} times before. Last login datetime: {3}");
$trust_string = str_replace('{1}', '<a href="'.htmlspecialchars($trust['trust_url']).'">'.htmlspecialchars($trust['trust_url']).'</a>', $trust_string);
$trust_string = str_replace('{2}', $trust['trust_total'], $trust_string);
$trust_string = str_replace('{3}', $trust['trust_last_visit'], $trust_string);
?>
	<p><?php echo $trust_string;?></p>
<?php 
}
elseif (isset($trust_url)) {

$trust_string = _("You are about to login to <a href='{1}'>{1}</a>. This is the first time you login to that site.");
$trust_string = str_replace('{1}', htmlspecialchars($trust_url), $trust_string);
?>
	<p><?php echo $trust_string;?></p>
<?php }?>
<form method="post">
<input type="submit" name="cancel" value="<?php echo _("cancel");?>" />
<input type="submit" name="trust" value="<?php echo _("proceed");?>" />
<?php echo $fields; ?>
</form>