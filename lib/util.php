<?
// font-awesome
$IMG_ICON_FIRST = "<i class='icon-fast-backward'></i>";
$IMG_ICON_PREV = "<i class='icon-backward'></i>";
$IMG_ICON_NEXT = "<i class='icon-forward'></i>";
$IMG_ICON_LAST = "<i class='icon-fast-forward'></i>";

$IMG_ICON_NEW = "<i class='icon-new '></i>&nbsp;";
$IMG_ICON_SAVE = "<i class='icon-save '></i>&nbsp;";
$IMG_ICON_TIME = "<i class='icon-time'></i>&nbsp;";
$IMG_ICON_MSG = "<i class='icon-comments-alt'></i>&nbsp;";
$IMG_ICON_TIME = "<i class='icon-time'></i>&nbsp;";
$IMG_ICON_HEART = "<i class='icon-heart'></i>&nbsp;";
$IMG_ICON_EXCLAM = "<i class='icon-warning-sign'></i>&nbsp;";
$IMG_ICON_SYS = "<i class='icon-desktop'></i>&nbsp;";
$IMG_ICON_REFRESH = "<i class='icon-refresh'></i>&nbsp;";
$IMG_ICON_BACK = "<i class='icon-reply'></i>&nbsp;";
$IMG_ICON_WRENCH = "<i class='icon-wrench'></i>&nbsp;";
$IMG_ICON_CHART = "<i class='icon-bar-chart'></i>&nbsp;";
$IMG_ICON_DATA = "<i class='icon-table'></i>&nbsp;";
$IMG_ICON_HOME = "<i class='icon-home'></i>&nbsp;";
$IMG_ICON_EDIT = "<i class='icon-edit'></i>&nbsp;";
$IMG_ICON_DELETE = "<i class='icon-delete'></i>&nbsp;";


function redirect($url) {
	header("Location: $url");
}
function dbgout($txt) {
	echo "<font face='courier' color='#006600'>" . $txt . "</font><br />\n";
}

function errout($txt) {
	echo "<font face='courier' color='#660000'><b>" . $txt . "</b></font><br />\n";
}

function cmtout($txt) {
	echo "<!-- " . $txt . " -->\n";
}

function objout($obj) {
	echo "<pre>";
	print_r($obj);
	echo "</pre>\n";
}


function readPOST($key, $default = "") {
	if(array_key_exists($key, $_POST)) {
		return $_POST[$key];
	} else {
		return $default;
	}
}

function readGET($key, $default = "") {
	if(array_key_exists($key, $_GET)) {
		return $_GET[$key];
	} else {
		return $default;
	}
}

function right($value, $count){
    return substr($value, ($count*-1));
}
 
function left($string, $count){
    return substr($string, 0, $count);
}

function getTitleOfCourtesyOptions($default) {
	$titles = array("Dr.", "Mr.", "Mrs.", "Ms.");
	$opts = "<option />";
	foreach($titles as $T) {
		if($T == $default) {
			$opts .= "<option selected>$T</option>";
		} else {
			$opts .= "<option>$T</option>";
		}
	}
	return $opts;
}
?>