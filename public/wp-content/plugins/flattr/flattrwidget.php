<?php

if (!function_exists('flattrwidget_control')) {

function new_flattrwidget_control() {

    $options = get_option("flattrwidget");

    if (!is_array( $options )) {
        $options = array(
            'title' => 'Flattr',
            'text' => '',
            'above' => true,
            'compact' => false,
            'html' => false
        );
    }

    if ($_POST['flattrwidget-submit']) {
        $options['title'] = htmlspecialchars($_POST['flattrwidget-title']);
        if($options['html']) {
            $options['text'] = $_POST['flattrwidget-text'];
        } else {
            $options['text'] = htmlspecialchars($_POST['flattrwidget-text']);
        };
        $options['above'] = $_POST['flattrwidget-above'];
        $options['compact'] = $_POST['flattrwidget-compact'];
        $options['html'] = $_POST['flattrwidget-html'];

        update_option("flattrwidget", $options);
    }
?>
<p>
<label for="flattrwidget-title">Title: </label><br />
<input class="widefat" type="text" id="flattrwidget-title" name="flattrwidget-title" value="<?php echo $options['title'];?>" />
<label for="flattrwidget-text">Text: </label><br />
<textarea class="widefat" rows="16" cols="10" type="text" id="flattrwidget-text" name="flattrwidget-text"><?php echo stripslashes($options['text']);?></textarea>
<input type="checkbox" id="flattrwidget-above" name="flattrwidget-above"<?php if ($options['above']) { echo " checked"; } ?> />
<label for="flattrwidget-above">Check to display the text above the Flattr button. (leave unchecked to display below)</label><br />
<input type="checkbox" id="flattrwidget-html" name="flattrwidget-html"<?php if ($options['html']) { echo " checked"; } ?> />
<label for="flattrwidget-html">Check to allow HTML in text.</label><br />
<input type="checkbox" id="flattrwidget-compact" name="flattrwidget-compact"<?php if ($options['compact']) { echo " checked"; } ?> />
<label for="flattrwidget-compact">Check to use compact style Flattr button.</label><br />

<input type="hidden" id="flattrwidget-submit" name="flattrwidget-submit" value="1" />
<?php

    if (!get_option('flattr_uid')) {

        $url = get_bloginfo('wpurl') .'/wp-admin/plugin-install.php?tab=plugin-information&plugin=flattr&TB_iframe=true&width=640&height=840';

        echo "<p>You need the <a href=\"$url\">official Flattr plugin</a> installed, activated and configured for the widget to work!</p>";
        echo "<p>Nothing will be displayed in your sidebar right now.</p>";
    }

}

function new_flattrwidget_widget($args) {

    if (!get_option('flattr_uid')) { return; }

    extract($args);

    $options = get_option("flattrwidget");

    echo $before_widget;
    echo $before_title;
    echo $options['title'];
    echo $after_title;
    if ($options['above']) { echo "<p>". stripslashes($options['text']) ."</p>"; }
    echo "<p align=\"center\">";

    $uid = get_option('flattr_uid');
    $cat = get_option('flattr_cat');
    $lang = get_option('flattr_lng');

    $category = $cat;
    $title = get_bloginfo('name');
    $description = get_bloginfo('description');
    $tags = 'blog,wordpress,widget';
    $url = get_bloginfo('url');
    $language = $lang;
    $userID = $uid;

    $compact = compact;

    if (!$options['compact']) { $compact = 'large'; }

    $cleaner = create_function('$expression', "return trim(preg_replace('~\r\n|\r|\n~', ' ', addslashes(\$expression)));");

    # button: 	compact | default
    /*
     * <a class="FlattrButton" style="display:none;"
    title="Detta är min post titel"
    data-flattr-uid="kjell"
    data-flattr-tags="tag1, tag2"
    data-flattr-category="text"
    href="http://wp.local/?p=444">

    Lorem ipsum dolor sit amet, consectetur adipiscing elit.
	Lorem ipsum dolor sit amet, consectetur adipiscing
    Maecenas aliquet aliquam leo quis fringilla.
</a>
     */

    $output = "<a class='FlattrButton' style='display:none;'".
                ' href="'.$cleaner($url).'"'.
                ' title="'.$cleaner($title).'"'.
                ' data-flattr-uid="'.$cleaner($userID).'"'.
                ' data-flattr-tags="'.$tags.'"'.
                ' data-flattr-button="'.$compact.'"'.
                ' data-flattr-category="'.$cleaner($category).'">'.
                $cleaner($description).
                '</a>';
    /*
    $output = "<script type=\"text/javascript\">\n";
    if ( defined('Flattr::VERSION')) {
        $output .= "var flattr_wp_ver = '" . Flattr::VERSION  . "';\n";
    }
    $output .= "var flattr_uid = '" . $cleaner($userID)      . "';\n";
    $output .= "var flattr_url = '" . $cleaner($url)         . "';\n";
    $output .= "var flattr_lng = '" . $cleaner($language)    . "';\n";
    $output .= "var flattr_cat = '" . $cleaner($category)    . "';\n";
    if($tags) { $output .= "var flattr_tag = '". $cleaner($tags) ."';\n"; }
    if ($options['compact']) { $output .= "var flattr_btn = 'compact';\n"; } else {
        $output .= "var flattr_btn = 'large';\n";
    }
    $output .= "var flattr_tle = '". $cleaner($title) ."';\n";
    $output .= "var flattr_dsc = '". $cleaner($description) ."';\n";
    $output .= "</script>\n";
    if ( defined('Flattr::API_SCRIPT')) {
        $output .= '<script src="' . Flattr::API_SCRIPT . '" type="text/javascript"></script>';
    }
     *
     */
    echo $output;

    echo "</p>";
    if (!$options['above']) { echo "<p>". stripslashes($options['text']) ."</p>"; }
    echo $after_widget;
}

register_sidebar_widget ( "Flattr Widget", new_flattrwidget_widget );
register_widget_control ( "Flattr Widget", new_flattrwidget_control );

} else {

}

?>
