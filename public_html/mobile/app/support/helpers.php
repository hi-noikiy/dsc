<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
function abort($msg, $code = 0)
{
	E($msg, $code);
}

function debug($start, $end = '', $dec = 6)
{
	return G($start, $end, $dec);
}

function lang($name = NULL, $value = NULL)
{
	return L($name, $value);
}

function config($name = NULL, $value = NULL, $default = NULL)
{
	return C($name, $value, $default);
}

function input($name, $default = '', $filter = NULL, $datas = NULL)
{
	return I($name, $default, $filter, $datas);
}

function widget($name, $data = array())
{
	return W($name, $data);
}

function model($name = '')
{
	$class = '\\app\\models\\' . $name;

	if (class_exists($class)) {
		return new $class();
	}

	return false;
}

function dao($name = '', $tablePrefix = '', $connection = '')
{
	return M($name, $tablePrefix, $connection);
}

function action($url, $vars = array(), $layer = '')
{
	return R($url, $vars, $layer);
}

function url($url = '', $vars = '', $suffix = true, $domain = false)
{
	$routes = config('URL_ROUTE_RULES');
	$rule = array_search($url, $routes);
	if (($rule !== false) && ($domain === false) && (config('url_model') == '2')) {
		$rule = str_replace('\\/', '/', $rule);
		trims($rule, array('/^', '$/', '$'));
		$rule = explode('/', $rule);
		$string = '';

		foreach ($rule as $item) {
			if (0 === strpos($item, '[:')) {
				$item = substr($item, 1, -1);
			}

			if (0 === strpos($item, ':')) {
				if ($pos = strpos($item, '^')) {
					$var = substr($item, 1, $pos - 1);
				}
				else if (strpos($item, '\\')) {
					$var = substr($item, 1, -2);
				}
				else {
					$var = substr($item, 1);
				}
			}

			$string .= '/' . ($var === NULL ? $item : $vars[$var]);

			if (isset($vars[$var])) {
				unset($vars[$var]);
			}
		}

		return U($string) . (empty($vars) ? '' : '?' . http_build_query($vars, '', '&'));
	}

	return U($url, $vars, $suffix, $domain);
}

function cache($name, $value = '', $options = NULL)
{
	return S($name, $value, $options);
}

function view($template = '', $layer = '')
{
	return T($template, $layer);
}

function json($data = array())
{
	return json_encode($data, '5.4.0' <= PHP_VERSION ? JSON_UNESCAPED_UNICODE : 0);
}

function trims(&$value, $charlist = '')
{
	if (is_string($charlist)) {
		$charlist = array($charlist);
	}

	foreach ($charlist as $char) {
		$value = trim($value, $char);
	}
}

function is_wechat_browser()
{
	$user_agent = strtolower($_SERVER['HTTP_USER_AGENT']);

	if (strpos($user_agent, 'micromessenger') === false) {
		return false;
	}
	else {
		return true;
	}
}

function array_define($array, $check = true)
{
	$content = "\n";

	foreach ($array as $key => $val) {
		$key = strtoupper($key);

		if ($check) {
			$content .= 'defined(\'' . $key . '\') or ';
		}

		if (is_int($val) || is_float($val)) {
			$content .= 'define(\'' . $key . '\',' . $val . ');';
		}
		else if (is_bool($val)) {
			$val = ($val ? 'true' : 'false');
			$content .= 'define(\'' . $key . '\',' . $val . ');';
		}
		else if (is_string($val)) {
			$content .= 'define(\'' . $key . '\',\'' . addslashes($val) . '\');';
		}

		$content .= "\n";
	}

	return $content;
}

function array_order($array, $key, $type = 'asc', $reset = false)
{
	if (empty($array) || !is_array($array)) {
		return $array;
	}

	foreach ($array as $k => $v) {
		$keysvalue[$k] = $v[$key];
	}

	if ($type == 'asc') {
		asort($keysvalue);
	}
	else {
		arsort($keysvalue);
	}

	$i = 0;

	foreach ($keysvalue as $k => $v) {
		$i++;

		if ($reset) {
			$new_array[$k] = $array[$k];
		}
		else {
			$new_array[$i] = $array[$k];
		}
	}

	return $new_array;
}

function dir_size($directoty)
{
	$dir_size = 0;

	if ($dir_handle = @opendir($directoty)) {
		while ($filename = readdir($dir_handle)) {
			$subFile = $directoty . DIRECTORY_SEPARATOR . $filename;
			if (($filename == '.') || ($filename == '..')) {
				continue;
			}
			else if (is_dir($subFile)) {
				$dir_size += dir_size($subFile);
			}
			else if (is_file($subFile)) {
				$dir_size += filesize($subFile);
			}
		}

		closedir($dir_handle);
	}

	return $dir_size;
}

function copy_dir($sourceDir, $aimDir)
{
	$succeed = true;

	if (!file_exists($aimDir)) {
		if (!mkdir($aimDir, 511)) {
			return false;
		}
	}

	$objDir = opendir($sourceDir);

	while (false !== ($fileName = readdir($objDir))) {
		if (($fileName != '.') && ($fileName != '..')) {
			if (!is_dir($sourceDir . '/' . $fileName)) {
				if (!copy($sourceDir . '/' . $fileName, $aimDir . '/' . $fileName)) {
					$succeed = false;
					break;
				}
			}
			else {
				copy_dir($sourceDir . '/' . $fileName, $aimDir . '/' . $fileName);
			}
		}
	}

	closedir($objDir);
	return $succeed;
}

function del_dir($dir)
{
	\Touch\Util::delDir($dir);
}

function html_in($str)
{
	$str = htmlspecialchars($str);

	if (!get_magic_quotes_gpc()) {
		$str = addslashes($str);
	}

	return $str;
}

function html_out($str)
{
	if (function_exists('htmlspecialchars_decode')) {
		$str = htmlspecialchars_decode($str);
	}
	else {
		$str = html_entity_decode($str);
	}

	$str = stripslashes($str);
	return $str;
}

function unique_number()
{
	return date('Ymd') . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
}

function random_str()
{
	$year_code = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
	$order_sn = $year_code[intval(date('Y')) - 2010] . strtoupper(dechex(date('m'))) . date('d') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('d', rand(0, 99));
	return $order_sn;
}

function data_auth_sign($data)
{
	if (!is_array($data)) {
		$data = (array) $data;
	}

	ksort($data);
	$code = http_build_query($data);
	$sign = sha1($code);
	return $sign;
}

function logResult($word = '')
{
	$word = (is_array($word) ? var_export($word, true) : $word);
	$fp = fopen(ROOT_PATH . 'storage/logs/log.txt', 'a');
	flock($fp, LOCK_EX);
	fwrite($fp, '执行日期：' . date('Y-m-d H:i:s', time()) . "\n" . $word . "\n");
	flock($fp, LOCK_UN);
	fclose($fp);
}

function elixir($file, $absolute_path = false)
{
	return ($absolute_path == true ? __HOST__ : '') . __TPL__ . '/' . ltrim($file, '/');
}

function global_assets($type = 'css', $module = 'app', $mode = 0)
{
	$assets = C('ASSETS');
	$gulps = array('dist' => 'public/');
	if (APP_DEBUG || $mode) {
		$resources = './public/';
		$paths = array();

		foreach ($assets as $key => $item) {
			foreach ($item as $vo) {
				if (substr($vo, -3) == '.js') {
					$paths[$key]['js'][] = '<script src="' . __PUBLIC__ . '/' . $vo . '?v=' . time() . '"></script>';
					$gulps[$key]['js'][] = $resources . $vo;
				}
				else if (substr($vo, -4) == '.css') {
					$paths[$key]['css'][] = '<link href="' . __PUBLIC__ . '/' . $vo . '?v=' . time() . '" rel="stylesheet" type="text/css" />';
					$gulps[$key]['css'][] = $resources . $vo;
				}
			}
		}

		file_put_contents(ROOT_PATH . 'storage/webpack.config.js', 'module.exports = ' . json_encode($gulps));
	}
	else {
		$paths[$module] = array(
	'css' => array('<link href="' . elixir('css/' . $module . '.min.css') . '?v=' . RELEASE . '" rel="stylesheet" type="text/css" />'),
	'js'  => array('<script src="' . elixir('js/' . $module . '.min.js') . '?v=' . RELEASE . '"></script>')
	);
	}

	return isset($paths[$module][$type]) ? implode("\n", $paths[$module][$type]) . "\n" : '';
}

function create_editor($input_name, $input_value = '', $width = 600, $height = 260)
{
	static $ueditor_created = false;
	$editor = '';

	if (!$ueditor_created) {
		$ueditor_created = true;
		$editor .= '<script type="text/javascript" src="' . __PUBLIC__ . '/vendor/editor/ueditor.config.js"></script>';
		$editor .= '<script type="text/javascript" src="' . __PUBLIC__ . '/vendor/editor/ueditor.all.min.js"></script>';
	}

	$editor .= '<script id="ue_' . $input_name . '" name="' . $input_name . '" type="text/plain" style="width:' . $width . 'px;height:' . $height . 'px;">' . htmlspecialchars_decode($input_value) . '</script>';
	$editor .= '<script type="text/javascript">var ue_' . $input_name . ' = UE.getEditor("ue_' . $input_name . '");</script>';
	return $editor;
}

function dd($var, $echo = true, $label = NULL, $strict = true)
{
	dump($var, $echo, $label, $strict);
	exit();
}


?>