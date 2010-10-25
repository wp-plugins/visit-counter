<?php
/*
Plugin Name: Visit counter
Plugin URI: http://beastbed.wordpress.com/desarrollo/contador-de-visitas-plugin-para-wordpress/
Description: Widget that displays a visitor count for your blog. Based on <a href="http://jungwirths.com">Paul A. Jungwirth</a>'s <a href="http://jungwirths.com/2009/03/simple-wordpress-hit-counter-plugin/">Simple Hit Counter</a>
Version: 1.0
Author: Federico Mendez
Author URI: http://beastbed.wordpress.com
*/
/*
Contador de visitas
Copyright 2010 Federico Mendez (email: fede.bd@gmail.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
/*
Simple Hit Counter
Copyright 2009  Paul A Jungwirth  (email: once@9stmaryrd.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
/*
COLOR PICKER SELECTOR SCRIPT IS COPYRIGHTED BY:

FREE-COLOR-PICKER.COM
HTTP://WWW.FREE-COLOR-PICKER.COM
            
PERMISSION GIVEN TO USE THIS SCRIPT IN ANY KIND
OF APPLICATIONS IF SCRIPT CODE REMAINS
UNCHANGED AND THE ANCHOR TAG "POWERED BY FCP"
REMAINS VALID AND VISIBLE TO THE USER.
*/

global $wpdb, $simplehitcounter_table_name;
if (!function_exists("get_option")) {simplehitcounter_readme();die;}

// Edit this line if you want to use a different MySQL table name:
$simplehitcounter_table_name = $wpdb->prefix . "simplehitcounter_hits";

// Increments the database by one and returns the total number of hits to date.
function simplehitcounter_hit() {
	global $wpdb, $simplehitcounter_table_name;
	$thesite = get_option('siteurl');
	$wpdb->query("UPDATE $simplehitcounter_table_name SET hit_count = hit_count + 1 WHERE site = '$thesite'");
	return $wpdb->get_var("SELECT hit_count FROM $simplehitcounter_table_name WHERE site = '$thesite'");
}

// Prints an error message.
function simplehitcounter_readme() {
	echo '<br><strong>Something is wrong with Simple Hit Counter!</strong><br>';
}

// Installs the plugin.
function simplehitcounter_install() {
	global $wpdb, $simplehitcounter_table_name;
	$thesite = get_option('siteurl');
	if ($wpdb->get_var("SHOW TABLES LIKE '$simplehitcounter_table_name'") != $simplehitcounter_table_name) {
		$wpdb->query("CREATE TABLE $simplehitcounter_table_name (
			site TEXT NOT NULL,
			hit_count INT NOT NULL
		)");
		$wpdb->query("INSERT INTO $simplehitcounter_table_name (site, hit_count) VALUES ('$thesite', 0)");
		add_option("simplehitcounter_db_version", "1.0");
	}
}

// Prints the counter
function simplehitcounter_output()
{
  $options = get_option('simplehitcounter_widget_options');
  $hits = simplehitcounter_hit();
  $visitas = '<font size="'.$options['font_size'].'" color="'.$options['font_color'].'"><b>'.$hits.'</b></font>';
  echo '<h4>'.$options['title'].'</h4>';
  echo str_replace('VISITAS', $visitas, $options['text']);
}

// Widget declaration
function simplehitcounter_widget(){
//  if(function_exists('register_sidebar_widget')){
    register_sidebar_widget('Contador de visitas','simplehitcounter_output');
	register_widget_control('Contador de visitas','simplehitcounter_widget_control');
//  }
}

// Widget control
function simplehitcounter_widget_control()
{
  $options = $new_options = get_option('simplehitcounter_widget_options');
  if(!$options) 
  {
    $options = array(
      'font_size' => 3, 
      'font_color' => '#000000', 
      'title' => 'Visitas', 
      'text' => 'Ud. es el visitante n&deg; VISITAS'
    );
    update_option('simplehitcounter_widget_options', $options);
    $new_options = $options;
  }

  if($_POST['format_submit']) 
  {
    $new_options['font_size'] = $_POST['font_size'];
    $new_options['font_color'] = $_POST['font_color_hex'];
    $new_options['title'] = $_POST['shc_title'];
    $new_options['text'] = $_POST['shc_text'];
  }

  if($options != $new_options)
  {
    $options = $new_options;
	update_option('simplehitcounter_widget_options', $options);
  }
?>
  <p>
  <label for="shc_title">
    T&iacute;tulo&nbsp;
    <input type="text" name="shc_title"<?php if($options['title'] != '') {echo ' value="'.$options['title'].'"';}?>>
  </label><br />
  <label for="shc_text">
    Texto&nbsp;
    <input type="text" name="shc_text"<?php if($options['text'] != '') {echo ' value="'.$options['text'].'"';} ?>>
  </label><br />
  <font size="2">
    <b>Escriba libremente en el campo, ingresando el t&eacute;rmino VISITAS donde quiera que aparezca el contador</b><br />
    <i>Puede utilizar c&oacute;digo html en el cuadro, como ser <b>&lt;i&gt;</b>, <b>&lt;b&gt;</b> o <b>&lt;br /&gt;</b></i>
  </font><br />
  <label for="font_size">Tama&ntilde;o:
  <select name="font_size">
    <option value="1"<?php if($options['font_size'] == 1){echo ' selected="selected"';} ?>><font size="1">Diminuto</font></option>
	<option value="2"<?php if($options['font_size'] == 2){echo ' selected="selected"';} ?>><font size="2">Peque&ntilde;o</font></option>
	<option value="3"<?php if($options['font_size'] == 3){echo ' selected="selected"';} ?>><font size="3">Normal</font></option>
	<option value="4"<?php if($options['font_size'] == 4){echo ' selected="selected"';} ?>><font size="4">Grande</font></option>
	<option value="5"<?php if($options['font_size'] == 5){echo ' selected="selected"';} ?>><font size="5">Muy grande</font></option>
	<option value="6"<?php if($options['font_size'] == 6){echo ' selected="selected"';} ?>><font size="6">Enorme</font></option>
	<option value="7"<?php if($options['font_size'] == 7){echo ' selected="selected"';} ?>><font size="7">No entra en el browser</font></option>
  </select>
  </label>
  <script src="<?php echo get_bloginfo('home').'/wp-content/plugins/contador-de-visitas/js/202pop.js' ?>" type="text/javascript"></script>
  <script language="javascript">
var newwindow='';function pickerPopup202(ifn,sam){ var bl=screen.width/2-102; var bt=screen.height/2-104;page="wp-content/plugins/contador-de-visitas/js/fcp202.html"+"?ifn="+escape(ifn)+"&sam="+escape(sam); if(!newwindow.closed&&newwindow.location){ newwindow.location.href=page;}else{ newwindow=window.open(page,"CTRLWINDOW","help=no, status=no, toolbar=no, menubar=no, location=no,scrollbars=no, resizable=no, dependent=yes, width=250,height=250,left="+bl+",top="+bt+","); if(!newwindow.opener)newwindow.opener=self;}; if(window.focus){newwindow.focus()} return false;}
</script>
  <input type="button" onclick="pickerPopup202('font_color_hex','sample_1');" value="Elija un color" />&nbsp;
  <input type="text" name="font_color_hex" id="font_color_hex" size="7" value="<?php echo $options['font_color']; ?>" />&nbsp;
  <input type="text" id="sample_1" size="1" value="" style="background: <?php echo $options['font_color']; ?>;" />
  <input type="hidden" id="format_submit" name="format_submit" value="1" />
<?php  
}

register_activation_hook(__FILE__, "simplehitcounter_install");
add_action('init', 'simplehitcounter_widget');
?>
