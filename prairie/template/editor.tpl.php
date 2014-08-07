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

<div id="col_left">
	<form method="post">
	<div class="box" id="box_html">
		<div class="box_header">
			<h1><?php echo _("Web profile");?></h1>
		</div>
	
		<div class="box_body">
			<p>
				<label for="id_webspace_title"><?php echo _("Title");?></label>
				<input type="text" name="webspace_title" id="id_webspace_title" value="<?php if (isset($webspace['webspace_title'])) { echo htmlspecialchars($webspace['webspace_title']); }?>" />
			</p>
	
	
			<?php
			if (isset($themes)) {
			?>
			<p>
				<label for="id_webspace_theme"><?php echo _("Choose theme");?></label>
				<select name="theme_name" id="id_webspace_theme">
					<?php
					foreach ($themes as $key => $i):
			
					$selected = "";
			
					if (isset($webspace['webspace_theme']) && $i == $webspace['webspace_theme']) {
						$selected = " selected=\"selected\"";
					}
					elseif (!isset($webspace['webspace_theme']) && $i == $default_theme) {
						$selected = " selected=\"selected\"";
					}
					?>
					<option id="theme_thumb<?php echo $key;?>" onclick="javascript:selectTheme('<?php echo $i;?>');"<?php echo $selected;?>><?php echo $i;?></option>
					<?php endforeach;?>
				</select>
			</p>
			
			<div id="id_theme_thumb"></div>
			
			<script type="text/javascript">
				function selectTheme(theme) {
					document.getElementById('id_theme_thumb').style.backgroundImage = "url('/theme/"+theme+"/thumb.png')";
				}
		
				selectTheme('<?php if (isset($webspace['webspace_theme'])) { echo $webspace['webspace_theme']; } else { echo $default_theme;} ?>');
			</script>
			<?php }?>
		
			<p class="buttons">
				<input type="submit" name="save_profile" value="<?php echo _("save");?>" />
			</p>
		</div>
	</div>
	</form>
</div>


<div id="col_right">
	<form method="post">
	<div class="box2" id="box_html">
		<div class="box_header">
			<h1><?php echo _("Content markup");?></h1>
		</div>
	
		<div class="box_body">
			<p>
				<label for="id_html"><?php echo _("HTML");?></label>
				<textarea id="id_html" name="html" class="mceEditor"><?php if (isset($webspace['webspace_html'])) echo htmlspecialchars($webspace['webspace_html']); ?></textarea>
				<script  type="text/javascript">
				tinyMCE.init({
			// General options
			mode : "specific_textareas",
			editor_selector : "mceEditor",
			theme : "advanced",
			plugins : "safari,spellchecker,pagebreak,style,table,Archiv,advlink,advimage,emotions,preview,searchreplace,contextmenu,paste,fullscreen",
				Archiv_settings_file : "/var/www/openid/web/tiny_mce/plugins/Archiv/config.php",
			    theme_advanced_buttons1_add : "Archiv_files,Archiv_images",

			// Theme options
			theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect",
			theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
			theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
			theme_advanced_buttons4 : "moveforward,movebackward,absolute,|,styleprops,spellchecker,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,blockquote,pagebreak,|,insertfile,insertimage",
			theme_advanced_toolbar_location : "top",
			theme_advanced_toolbar_align : "left",
			theme_advanced_statusbar_location : "bottom",
			theme_advanced_resizing : true
			
		});
				</script>
				
			</p>
<!-- 	Remove the css field due to end-user usability. The CSS are handled by the tiny_mce		
			<p>
				<label for="id_css"><?php echo _("CSS");?></label>
				<textarea id="id_css" name="css"><?php if (isset($webspace['webspace_css'])) echo $webspace['webspace_css']; ?></textarea>
			</p>
 -->			
			<p class="buttons">
				<input type="submit" name="save_markup" value="<?php echo _("save");?>" />
			</p>
		</div>
	</div>
	</form>
</div>
</form>