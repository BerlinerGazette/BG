<?php
/*  Copyright 2007  FunnyDingo  (email : funnydingo@funnydingo.de)

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
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

/*
Plugin Name: New Tag Cloud
Plugin URI: http://www.funnydingo.de/projekte/new-tag-cloud/
Description: The plugin provides an widget wich shows a tag cloud with the tags used by the new WordPress own tagging feature
Author: Dennis Hueckelheim aka FunnyDingo
Version: 0.7
Author URI: http://www.funnydingo.de/
*/



$newtagcloud_defaultoptions = array(
	'dblayout'			=> '0.5',
	'widgetinstance'	=> 0,
	'headingsize'		=> 1,
	'filterinstance'	=> 0,
	'shortcodeinstance'	=> 0,
	'instances'			=> serialize(array(0 => 'Default')),
	'enablefilter'		=> false,
	'enablecache'		=> true
);



$newtagcloud_instancedefaults = array(
	'maxcount'			=> 10,
	'title'				=> 'New Tag Cloud',
	'bigsize'			=> 18,
	'smallsize'			=> 10,
	'step'				=> 2,
	'sizetype'			=> 'px',
	'html_before'		=> '<ul id="newtagcloud"><li>',
	'html_after'		=> '</li></ul>',
	'entry_layout'		=> '<a style="font-size:%FONTSIZE%%SIZETYPE%" href="%TAGURL%" target="_self">%TAGNAME%</a>',
	'glue'				=> ' ',
	'filter'			=> false,
	'order'				=> 'name'
);



$newtagcloud_orderoptions = array('name' => 'By name', 'count' => 'By count');



function newtagcloud($id = 0)
{
	generate_newtagcloud(false, true, intval($id));
}



function generate_newtagcloud($widget = true, $display = true, $instanceID = 0)
{
	global $newtagcloud_defaults, $wpdb;

	$globalOptions = get_newtagcloud_options();

	if ($globalOptions['enablecache'] && !empty($globalOptions['cache'][$instanceID]))
	{
		if ($display)
			echo $globalOptions['cache'][$instanceID];
		else
			return $globalOptions['cache'][$instanceID];
		return;
	}

	$instanceOptions = get_newtagcloud_instanceoptions($instanceID);
	$content = array();
	$size = $instanceOptions['bigsize'];

	if (is_array($instanceOptions['catfilter']))
		$sqlCatFilter = "`$wpdb->term_relationships`.`object_id` IN (SELECT `object_id` FROM `$wpdb->term_relationships` LEFT JOIN `$wpdb->term_taxonomy` ON `$wpdb->term_relationships`.`term_taxonomy_id` = `$wpdb->term_taxonomy`.`term_taxonomy_id` WHERE `term_id` IN (" . implode(",", $instanceOptions['catfilter']) . ")) AND";
	else
		$sqlCatFilter = "";

	if (is_array($instanceOptions['tagfilter']))
	{
		foreach($instanceOptions['tagfilter'] as $k => $v)
			$instanceOptions['tagfilter'][$k] = "'" . $wpdb->escape($v) . "'";
		$skipTags = implode(",", $instanceOptions['tagfilter']);
		$sqlTagFilter = "AND LOWER(`$wpdb->terms`.`name`) NOT IN ($skipTags)";
	}
	else
		$sqlTagFilter = "";
	$query = "SELECT `$wpdb->terms`.`term_id`, `$wpdb->terms`.`name`, LOWER(`$wpdb->terms`.`name`) AS lowername, `$wpdb->term_taxonomy`.`count` FROM `$wpdb->terms` LEFT JOIN `$wpdb->term_taxonomy` ON `$wpdb->terms`.`term_id` = `$wpdb->term_taxonomy`.`term_id` LEFT JOIN `$wpdb->term_relationships` ON `$wpdb->term_taxonomy`.`term_taxonomy_id` = `$wpdb->term_relationships`.`term_taxonomy_id` LEFT JOIN `$wpdb->posts` ON `$wpdb->term_relationships`.`object_id` = `$wpdb->posts`.`ID` WHERE " . $sqlCatFilter . " `$wpdb->term_taxonomy`.`taxonomy` = 'post_tag' AND `$wpdb->term_taxonomy`.`count` > 0 " . $sqlTagFilter . " GROUP BY `$wpdb->terms`.`name` ORDER BY `$wpdb->term_taxonomy`.`count` DESC LIMIT 0, " . $instanceOptions['maxcount'];
	$terms = $wpdb->get_results($query);

	$prevCount = $terms[0]->count;
	$skipTags = explode(",", $instanceOptions['tagfilter']);
	foreach ($terms as $term)
	{
		if ($prevCount > intval($term->count) && $size > $instanceOptions['smallsize'])
		{
			$size = $size - $instanceOptions['step'];
			$prevCount = intval($term->count);
		}
		$content[$term->lowername] = str_replace('%FONTSIZE%', $size, $instanceOptions['entry_layout']);
		$content[$term->lowername] = str_replace('%SIZETYPE%', $instanceOptions['sizetype'], $content[$term->lowername]);
		$content[$term->lowername] = str_replace('%TAGURL%', get_tag_link($term->term_id), $content[$term->lowername]);
		$content[$term->lowername] = str_replace('%TAGNAME%', $term->name, $content[$term->lowername]);
	}
	if ($instanceOptions['order'] == 'name')
		ksort($content);
	$content = implode($instanceOptions['glue'], $content);

	if ($widget)
		$result = '<h' . ($globalOptions['headingsize'] + 1) . '>' . $instanceOptions['title'] . '</h' . ($globalOptions['headingsize'] + 1) . '>' . $instanceOptions['html_before'] . $content . $instanceOptions['html_after'];
	else
		$result = $instanceOptions['html_before'] . $content . $instanceOptions['html_after'];

	newtagcloud_cache_create($instanceID, $result);

	if ($display)
		echo $result;
	else
		return $result;
}



function newtagcloud_control()
{
	global $newtagcloud_defaults;

	$globalOptions = get_newtagcloud_options();
	$instanceOptions = get_newtagcloud_instanceoptions($globalOptions['widgetinstance']);
	if (isset($_POST['newtagcloud-title']))
	{
		$instanceOptions['title'] = strip_tags(stripslashes($_POST['newtagcloud-title']));
		update_option('newtagcloud_instance' . $globalOptions['widgetinstance'], $instanceOptions);
	}
	echo '<p style="text-align:right;"><label for="newtagcloud-title">Title: <input style="width: 250px;" id="newtagcloud-title" name="newtagcloud-title" type="text" value="'.$instanceOptions['title'].'" /></label></p>';
}



function newtagcloud_options()
{
	global $newtagcloud_orderoptions;

	if (isset($_POST['newtagcloud-instance']))
		$instanceToUse = intval($_POST['newtagcloud-instance']);
	else
		$instanceToUse = 0;

	if (isset($_POST['newtagcloud-saveglobal']))
		update_newtagcloud_options();

	if (isset($_POST['newtagcloud-clearcache']))
	{
		newtagcloud_cache_clear();
		echo '<div id="message" class="updated fade"><p><strong>Cache cleared</strong></p></div>';
	}

	if (isset($_POST['newtagcloud-saveinstance']))
		update_newtagcloud_instanceoptions(intval($_POST['newtagcloud-instance']));

	if (isset($_POST['newtagcloud-resetinstance']))
	{
		delete_option('newtagcloud_instance' . intval($_POST['newtagcloud-instance']));
		echo '<div id="message" class="updated fade"><p><strong>';
		_e('Instance reseted.');
		echo '</strong></p></div>';
	}

	if (isset($_POST['newtagcloud-deleteinstance']))
	{
		delete_newtagcloud_instance(intval($_POST['newtagcloud-instance']));
		$instanceToUse = 0;
	}

	$globalOptions = get_newtagcloud_options();
	$instanceOptions = get_newtagcloud_instanceoptions($instanceToUse);
	$instanceOptions['glue'] = str_replace(" ", "%BLANK%", $instanceOptions['glue']);
	$instanceName = unserialize($globalOptions['instances']);
	$instanceName = $instanceName[$instanceToUse];
	if (is_array($instanceOptions['tagfilter']))
		$tagFilter = implode(",", $instanceOptions['tagfilter']);
	else
		$tagFilter = "";

	?>
	<div class="wrap">
		<h2>Global Settings</h2>
		<form action="" method="post" name="newtagcloud-globalsettings">
			<table class="form-table">
				<tbody>
					<tr valign="top">
						<th scope="row">Instance for widget</th>
						<td><?php echo create_selectfield(get_newtagcloud_instances(), $globalOptions['widgetinstance'], 'newtagcloud-widgetinstance'); ?></td>
					</tr>
					<tr valign="top">
						<th scope="row">Heading size for widget title</th>
						<td><?php echo create_selectfield(array('h1', 'h2', 'h3', 'h4', 'h5', 'h6'), $globalOptions['headingsize'], 'newtagcloud-headingsize'); ?></td>
					</tr>
					<tr valign="top">
						<th scope="row">Do you want to enable filtering of <em>&lt;!--new-tag-cloud--&gt;</em>?</th>
						<td><input type="checkbox" name="newtagcloud-enablefilter" value="enablefilter" <?php echo (($globalOptions['enablefilter'])?"checked":""); ?>/>* This is the old way, you better should use shortcode ([newtagcloud], [newtagcloud int=&lt;ID&gt;) instead becaus it's faster!</td>
					</tr>
					<tr valign="top">
						<th scope="row">Default instance for filter</th>
						<td><?php echo create_selectfield(get_newtagcloud_instances(), $globalOptions['filterinstance'], 'newtagcloud-filterinstance'); ?></td>
					</tr>
					<tr valign="top">
						<th scope="row">Default instance for shortcode</th>
						<td><?php echo create_selectfield(get_newtagcloud_instances(), $globalOptions['shortcodeinstance'], 'newtagcloud-shortcodeinstance'); ?></td>
					</tr>
					<tr valign="top">
						<th scope="row">Enable caching?</th>
						<td><input type="checkbox" name="newtagcloud-enablecache" value="enablecache" <?php echo (($globalOptions['enablecache'])?"checked":""); ?>/></td>
					</tr>
					<tr valign="top">
						<th scope="row">Name of new instance</th>
						<td><input type="text" name="newtagcloud-instancename"/>* Enter a name to create a new instance. If empty, no new instance will be created if you click 'Save global settings'</td>
					</tr>
				</tbody>
			</table>
			<p class="submit">
				<input type="submit" name="newtagcloud-saveglobal" value="Save global settings"/>
				<input type="submit" name="newtagcloud-clearcache" value="Clear cache"/>
			</p>
		</form>
		<br/>
		<form action="" method="post" name="newtagcloud-instanceselector">
			<h2>Settings for instance: <?php echo create_selectfield(get_newtagcloud_instances(), $instanceToUse, 'newtagcloud-instance', ' onChange="submit();"'); ?></h2>
		</form>
		<form action="" method="post" name="newtagcloud-instance">
			<table class="form-table">
				<tbody>
					<tr valign="top">
						<th scope="row">Change instance name?</th>
						<td><input type="text" id="newtagcloud-instancename" name="newtagcloud-instancename" value="<?php echo($instanceName); ?>" size="<?php echo(strlen($instanceName)+5); ?>" /></td>
					</tr>
					<tr valign="top">
						<th scope="row">How many tags should be shown at most?</th>
						<td><input type="text" name="newtagcloud-maxcount" value="<?php echo($instanceOptions['maxcount']); ?>" size="<?php echo(strlen($instanceOptions['maxcount'])+5); ?>" /></td>
					</tr>
					<tr valign="top">
						<th scope="row">How big should be the biggest tag?</th>
						<td><input type="text" name="newtagcloud-bigsize" value="<?php echo($instanceOptions['bigsize']); ?>" size="<?php echo(strlen($instanceOptions['bigsize'])+5); ?>" /></td>
					</tr>
					<tr valign="top">
						<th scope="row">How small should be the smallest tag?</th>
						<td><input type="text" name="newtagcloud-smallsize" value="<?php echo($instanceOptions['smallsize']); ?>" size="<?php echo(strlen($instanceOptions['smallsize'])+5); ?>" /></td>
					</tr>
					<tr valign="top">
						<th scope="row">Font size difference between to sizes?</th>
						<td><input type="text" name="newtagcloud-step" value="<?php echo($instanceOptions['step']); ?>" size="<?php echo(strlen($instanceOptions['step'])+5); ?>" /></td>
					</tr>
					<tr valign="top">
						<th scope="row">Wich CSS size type you want use (e.g. px, pt, em, ...)?</th>
						<td><input type="text" name="newtagcloud-sizetype" value="<?php echo($instanceOptions['sizetype']); ?>" size="<?php echo(strlen($instanceOptions['sizetype'])+5); ?>" /></td>
					</tr>
					<tr valign="top">
						<th scope="row">Provide HTML code used before entries:</th>
						<td><input type="text" name="newtagcloud-htmlbefore" value="<?php echo(htmlentities($instanceOptions['html_before'])); ?>" size="<?php echo(strlen($instanceOptions['html_before'])+5); ?>" /></td>
					</tr>
					<tr valign="top">
						<th scope="row">Provide HTML coded used after entries:</th>
						<td><input type="text" name="newtagcloud-htmlafter" value="<?php echo(htmlentities($instanceOptions['html_after'])); ?>" size="<?php echo(strlen($instanceOptions['html_after'])+5); ?>" /></td>
					</tr>
					<tr valign="top">
						<th scope="row">Entry template</th>
						<td><input type="text" id="newtagcloud-entrylayout" name="newtagcloud-entrylayout" value="<?php echo(htmlentities($instanceOptions['entry_layout'])); ?>" size="<?php echo(strlen($instanceOptions['entry_layout'])+5); ?>" onkeyup="update_example();" onkeydown="update_example();" /><br/><div id="newtagcloud-entrylayout-example"></div></td>
					</tr>
					<tr valign="top">
						<th scope="row">Wich glue char you want use?</th>
						<td><input type="text" id="newtagcloud-glue" name="newtagcloud-glue" value="<?php echo(htmlentities($instanceOptions['glue'])); ?>" size="<?php echo(strlen($instanceOptions['glue'])+5); ?>" /><input type="button" value="I want use a blank" onClick="glueChar('%BLANK%')"/></td>
					<tr/>
					<tr valign="top">
						<th scope="row">Wich order type you want to use to sort the tags?</th>
						<td><?php echo create_selectfield($newtagcloud_orderoptions, $instanceOptions['order'], 'newtagcloud-order'); ?></td>
					</tr>
					<tr valign="top">
						<th scope="row">Enable category filter?<br/>Hint: To disable category filtering, deselect all categories!</th>
						<td><?php catfilter_list($instanceOptions['catfilter']); ?></td>
					</tr>
					<tr valign="top">
						<th scope="row">Filter tags?</th>
						<td><input type="text" id="newtagcloud-tagfilter" name="newtagcloud-tagfilter" value="<?php echo(htmlentities($tagFilter)); ?>" size="<?php echo(strlen($tagFilter)+5); ?>" />* Comma seperated list</td>
					</tr>
				</tbody>
			</table>
			<p class="submit">
				<input type="hidden" id="newtagcloud-instance" name="newtagcloud-instance" value="<?php echo $instanceToUse; ?>"/>
				<input type="hidden" name="newtagcloud-originalinstancename" value="<?php echo $instanceName; ?>"/>
				<input type="submit" name="newtagcloud-saveinstance" value="Save instance settings"/>
				<input type="submit" name="newtagcloud-resetinstance" value="Reset all data for this instance" onClick="return verifyReset()"/>
				<input type="submit" name="newtagcloud-deleteinstance" value="Delete this instance" onClick="return verifyDelete()"/>
			</p>
		</form>
	</div>
	<script type="text/javascript">
		function update_example()
		{
			var div = document.getElementById("newtagcloud-entrylayout-example");
			var template = document.getElementById("newtagcloud-entrylayout").value;
			template = template.replace(/</g, "&lt;");
			template = template.replace(/>/g, "&gt;");
			template = template.replace(/%FONTSIZE%/g, "10");
			template = template.replace(/%SIZETYPE%/g, "px");
			template = template.replace(/%TAGURL%/g, "http://www.yourblog.com/tags/test");
			template = template.replace(/%TAGNAME%/g, "test");
			div.innerHTML = template;
		}
		function verifyReset()
		{
			var instanceName = document.getElementById('newtagcloud-instancename').value;
			return confirm("Are you sure to reset all saved data for instance '" + instanceName + "'?");
		}
		function verifyDelete()
		{
			var instanceName = document.getElementById('newtagcloud-instancename').value;
			if (document.getElementById('newtagcloud-instance').value == 0)
			{
				alert("Instance '" + instanceName + "' is the base instance with ID 0 and can't be deleted!");
				return false;
			}
			return confirm("Are you sure to delete all saved data for instance '" + instanceName + "'?");
		}
		function glueChar(theChar)
		{
			document.getElementById('newtagcloud-glue').value = theChar;
		}
		update_example();
	</script>
	<?php
}



function catfilter_list($catfilter)
{
	global $wpdb;

	$query = "SELECT `$wpdb->terms`.`term_id`, `$wpdb->terms`.`name` FROM `$wpdb->terms` LEFT JOIN `$wpdb->term_taxonomy` ON `$wpdb->terms`.`term_id` = `$wpdb->term_taxonomy`.`term_id` WHERE `$wpdb->term_taxonomy`.`taxonomy` = 'category' ORDER BY `$wpdb->terms`.`name`";
	$terms = $wpdb->get_results($query);
	foreach($terms as $term)
	{
		if (is_array($catfilter))
		{
			if (in_array($term->term_id, $catfilter))
				echo '<input type="checkbox" name="newtagcloud-catfilter[' . $term->term_id . ']" value="dofilter" checked="checked" /> ' . $term->name . '<br/>';
			else
				echo '<input type="checkbox" name="newtagcloud-catfilter[' . $term->term_id . ']" value="dofilter" /> ' . $term->name . '<br/>';
		}
		else
			echo '<input type="checkbox" name="newtagcloud-catfilter[' . $term->term_id . ']" value="dofilter" /> ' . $term->name . '<br/>';
	}
}



function delete_newtagcloud_instance($instanceID)
{
	if ($instanceID == 0)
	{
		echo '<div id="message" class="updated fade"><p><strong>';
		_e('Instance 0 can\'t be deleted!');
		echo '</strong></p></div>';
		return;
	}
	delete_option('newtagcloud_instance' . $instanceID);
	$options = get_newtagcloud_options();
	$instances = unserialize($options['instances']);
	unset($instances[$instanceID]);
	$options['instances'] = serialize($instances);
	update_option('newtagcloud', $options);
	echo '<div id="message" class="updated fade"><p><strong>';
	_e('Instance deleted.');
	echo '</strong></p></div>';
}



function get_newtagcloud_options()
{
	global $newtagcloud_defaultoptions;

	$options = get_option('widget_newtagcloud');
	if (is_array($options))
	{
		delete_option('widget_newtagcloud');
		unset($options['filter']);
		update_option('newtagcloud_instance0', $options);

		$options = get_newtagcloud_options();
		$instances = unserialize($options['instances']);
		$instances[0] = 'Imported pre v0.5 configuration';
		$options['instances'] = serialize($instances);
		update_option('newtagcloud', $options);
	}

	$options = get_option('newtagcloud');
	$options['dblayout'] = $options['dblayout'] === NULL ? $newtagcloud_defaultoptions['dblayout'] : $options['dblayout'];
	$options['widgetinstance'] = $options['widgetinstance'] === NULL ? $newtagcloud_defaultoptions['widgetinstance'] : $options['widgetinstance'];
	$options['headingsize'] = $options['headingsize'] === NULL ? $newtagcloud_defaultoptions['headingsize'] : $options['headingsize'];
	$options['enablefilter'] = $options['enablefilter'] === NULL ? $newtagcloud_defaultoptions['enablefilter'] : $options['enablefilter'];
	$options['filterinstance'] = $options['filterinstance'] === NULL ? $newtagcloud_defaultoptions['filterinstance'] : $options['filterinstance'];
	$options['shortcodeinstance'] = $options['shortcodeinstance'] === NULL ? $newtagcloud_defaultoptions['shortcodeinstance'] : $options['shortcodeinstance'];
	$options['instances'] = $options['instances'] === NULL ? $newtagcloud_defaultoptions['instances'] : $options['instances'];
	$options['enablecache'] = $options['enablecache'] === NULL ? $newtagcloud_defaultoptions['enablecache'] : $options['enablecache'];

	return $options;
}



function update_newtagcloud_options()
{
	$options = get_newtagcloud_options();
	$options['widgetinstance'] = intval($_POST['newtagcloud-widgetinstance']);
	$options['headingsize'] = intval($_POST['newtagcloud-headingsize']);
	$options['enablefilter'] = isset($_POST['newtagcloud-enablefilter']) ? true : false;
	$options['filterinstance'] = intval($_POST['newtagcloud-filterinstance']);
	$options['shortcodeinstance'] = intval($_POST['newtagcloud-shortcodeinstance']);
	$options['enablecache'] = isset($_POST['newtagcloud-enablecache']) ? true : false;
	if ($options['enablecache'] === false)
		unset($options['cache']);

	if (strlen($_POST['newtagcloud-instancename']) > 0)
	{
		$instances = unserialize($options['instances']);
		$instances[] = strip_tags(stripslashes($_POST['newtagcloud-instancename']));
		$options['instances'] = serialize($instances);
	}

	update_option('newtagcloud', $options);
	echo '<div id="message" class="updated fade"><p><strong>';
	_e('Options saved.');
	echo '</strong></p></div>';
}



function get_newtagcloud_instances()
{
	$options = get_newtagcloud_options();
	$instances = unserialize($options['instances']);
	foreach($instances as $id => $name)
		$instances[$id] = $name . ' (ID: ' . $id . ')';
	return $instances;
}



function get_newtagcloud_instanceoptions($instanceID = 0)
{
	global $newtagcloud_instancedefaults;

	$options = get_option('newtagcloud_instance' . $instanceID);
	$options['title'] = $options['title'] === NULL ? $newtagcloud_instancedefaults['title'] : $options['title'];
	$options['maxcount'] = $options['maxcount'] === NULL ? $newtagcloud_instancedefaults['maxcount'] : $options['maxcount'];
	$options['bigsize'] = $options['bigsize'] === NULL ? $newtagcloud_instancedefaults['bigsize'] : $options['bigsize'];
	$options['smallsize'] = $options['smallsize'] === NULL ? $newtagcloud_instancedefaults['smallsize'] : $options['smallsize'];
	$options['step'] = $options['step'] === NULL ? $newtagcloud_instancedefaults['step'] : $options['step'];
	$options['sizetype'] = $options['sizetype'] === NULL ? $newtagcloud_instancedefaults['sizetype'] : $options['sizetype'];
	$options['html_before'] = $options['html_before'] === NULL ? $newtagcloud_instancedefaults['html_before'] : $options['html_before'];
	$options['html_after'] = $options['html_after'] === NULL ? $newtagcloud_instancedefaults['html_after'] : $options['html_after'];
	$options['entry_layout'] = $options['entry_layout'] === NULL ? $newtagcloud_instancedefaults['entry_layout'] : $options['entry_layout'];
	$options['glue'] = $options['glue'] === NULL ? $newtagcloud_instancedefaults['glue'] : $options['glue'];
	$options['order'] = $options['order'] === NULL ? $newtagcloud_instancedefaults['order'] : $options['order'];

	return $options;
}



function update_newtagcloud_instanceoptions($instanceID)
{
	$options = get_newtagcloud_instanceoptions($instanceID);

	$options['maxcount'] = intval($_POST['newtagcloud-maxcount']);
	$options['bigsize'] = intval($_POST['newtagcloud-bigsize']);
	$options['smallsize'] = intval($_POST['newtagcloud-smallsize']);
	$options['step'] = intval($_POST['newtagcloud-step']);
	$options['sizetype'] = strip_tags(stripslashes($_POST['newtagcloud-sizetype']));
	$options['html_before'] = stripslashes($_POST['newtagcloud-htmlbefore']);
	$options['html_after'] = stripslashes($_POST['newtagcloud-htmlafter']);
	$options['entry_layout'] = stripslashes($_POST['newtagcloud-entrylayout']);
	$options['glue'] = stripslashes(str_replace("%BLANK%", " ", $_POST['newtagcloud-glue']));
	$options['order'] = stripslashes(str_replace("%BLANK%", " ", $_POST['newtagcloud-order']));

	if (empty($_POST['newtagcloud-tagfilter']))
		unset($options['tagfilter']);
	else
		$options['tagfilter'] = explode(",", strtolower((stripslashes($_POST['newtagcloud-tagfilter']))));

	unset($options['catfilter']);
	if (is_array($_POST['newtagcloud-catfilter']))
	{
		foreach($_POST['newtagcloud-catfilter'] as $id => $value)
			$options['catfilter'][] = $id;
	}

	update_option('newtagcloud_instance' . $instanceID, $options);

	$options = get_newtagcloud_options();
	if ($_POST['newtagcloud-instancename'] != $_POST['newtagcloud-originalinstancename'])
	{
		$instances = unserialize($options['instances']);
		$instances[$instanceID] = strip_tags(stripslashes($_POST['newtagcloud-instancename']));
		$options['instances'] = serialize($instances);
	}
	unset($options['cache'][$instanceID]);
	update_option('newtagcloud', $options);
	echo '<div id="message" class="updated fade"><p><strong>';
	_e('Options saved.');
	echo '</strong></p></div>';
}



function create_selectfield($options, $preselect, $name, $extra = "")
{
	$html = '<select name="' . $name . '"' . $extra . '>';
	foreach($options as $k => $v)
	{
		if ($preselect == $k)
			$html .= '<option value="' . $k . '" selected="selected">'. $v . '</option>';
		else
			$html .= '<option value="' . $k . '">'. $v . '</option>';
	}
	$html .= '</select>';
	return $html;
}



function newtagcloud_menu()
{
	add_options_page('New Tag Cloud', 'New Tag Cloud', 8, 'newtagcloud.php','newtagcloud_options');
}



function newtagcloud_init()
{
	if ( !function_exists('register_sidebar_widget') || !function_exists('register_widget_control') )
		return;
	function print_newtagcloud($args) {
		extract($args);
		?>
		<?php echo $before_widget; ?>
		<?php $globalOptions = get_newtagcloud_options(); generate_newtagcloud(true, true, $globalOptions['widgetinstance']); ?>
		<?php echo $after_widget; ?>
<?php
	}
	register_sidebar_widget(array('New Tag Cloud', 'widgets'), 'print_newtagcloud');
	register_widget_control(array('New Tag Cloud', 'widgets'), 'newtagcloud_control', 50, 10);
}



function newtagcloud_filter($content)
{
	$globalOptions = get_newtagcloud_options();
	$instances = unserialize($globalOptions['instances']);
	$identifier = "<!--new-tag-cloud-->";

	if (strpos($content, $identifier) > 0) 
	{
		$tagcloud = generate_newtagcloud(false, false, $globalOptions['filterinstance']);
		$tagcloud = '</p>'.$tagcloud.'<p>';
		return str_replace($identifier, $tagcloud, $content);
	}

	foreach($instances as $id => $name)
	{
		$identifier = "<!--new-tag-cloud-" . $id . "-->";
		if (strpos($content, $identifier) > 0) 
		{
			$tagcloud = generate_newtagcloud(false, false, $id);
			$tagcloud = '</p>'.$tagcloud.'<p>';
			$content = str_replace($identifier, $tagcloud, $content);
		}
	}
	return $content;
}



function newtagcloud_shortcode($atts)
{
	$globalOptions = get_newtagcloud_options();

	extract(shortcode_atts(array('int' => NULL), $atts));
	if (!is_numeric($int)) $int = $globalOptions['shortcodeinstance'];
	return generate_newtagcloud(false, false, $int);
}



function newtagcloud_cache_create($instanceID, $data)
{
	$options = get_newtagcloud_options();
	if ($options['enablecache'])
	{
		$options['cache'][$instanceID] = $data;
		update_option('newtagcloud', $options);
	}
}



function newtagcloud_cache_clear()
{
	$options = get_newtagcloud_options();
	unset($options['cache']);
	update_option('newtagcloud', $options);
}



add_action('widgets_init', 'newtagcloud_init');
add_action('admin_menu', 'newtagcloud_menu');
add_action('save_post', 'newtagcloud_cache_clear');
$options = get_newtagcloud_options();
if ($options['enablefilter']) add_filter('the_content', 'newtagcloud_filter');
if (function_exists('add_shortcode')) add_shortcode('newtagcloud', 'newtagcloud_shortcode');

?>
