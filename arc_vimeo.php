<?php
$plugin['name'] = 'arc_vimeo';

$plugin['version'] = '1.0';
$plugin['author'] = 'Andy Carter';
$plugin['author_uri'] = 'http://andy-carter.com/';
$plugin['description'] = 'Embed Vimeo videos with customised player';
$plugin['type'] = 0;

@include_once('zem_tpl.php');

if (0) {
# --- BEGIN PLUGIN HELP ---


# --- END PLUGIN HELP ---
}

# --- BEGIN PLUGIN CODE ---

function arc_vimeo($atts,$thing)
{
	global $thisarticle;

	$defaults = array(
		'video'     => '',
		'custom'    => 'Vimeo ID',
		'width'     => '0',
		'height'    => '0',
		'ratio'		=> '4:3',
		'color'		=> null,
		'label'     => '',
		'labeltag'  => '',
		'wraptag'   => '',
		'class'     => __FUNCTION__
	);

	extract(lAtts($defaults, $atts));

    $custom = strtolower($custom);
    if ($video && isset($thisarticle[$custom])) {
        $video = $thisarticle[$custom];
    }

    // If the width and/or height has not been set we want to calculate new
    // ones using the aspect ratio.
    if (!$width || !$height) {
    	
    	// Work out the aspect ratio.
    	preg_match("/([0-9]+):([0-9]+)/", $ratio, $matches);
    	if ($matches[0] && $matches[1]!=0 && $matches[2]!=0) {
			$aspect = $matches[1]/$matches[2];
		} else {
			$aspect = 1.333;
		}

		// Calcuate the new width/height.
		if ($width) {
			$height = $width/$aspect;
		} elseif ($height) {
			$width = $height*$aspect;
		} else {
			$width = 425;
			$height = 344;
		}

    }

	$src = '//player.vimeo.com/video/' . $video;

	$qString = array();

	// Check if the player's UI colour is being customised.
	if ($color && preg_match('|^#?([a-z0-9]{6})$|i', $color, $match)) {
		$qString[] = 'color=' . $match[1];
	}

	// Check if we need to append a query string to the video src.
	if (!empty($qString)) {
		$src .= '?' . implode('&amp;', $qString);
	}

	$out = "<iframe src='$src' width='$width' height='$height' frameborder='0' webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>";

	return doLabel($label, $labeltag) . (($wraptag) ? doTag($out, $wraptag, $class) : $out);

}

# --- END PLUGIN CODE ---

?>