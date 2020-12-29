<?php

use Config\App;

function print_link_resource($resource)
{
	$link = "<link rel='stylesheet' href='" . base_url($resource) . "'>";
	echo $link . "\n";
}

function print_script_resource($resource)
{
	$script = "<script src='" . base_url($resource) . "'></script>";
	echo $script . "\n";
}

function isParamId($idParam)
{
	$isParamId = $idParam > "0" && is_numeric($idParam);
	return $isParamId;
}

function print_base_url($url = "")
{
	echo base_url($url);
}

function print_site_url($url = "")
{
	echo site_url($url);
}

function cleanString($stringValue)
{
	return htmlspecialchars($stringValue, ENT_QUOTES, 'UTF-8');
}

function print_var($var)
{
	echo isset($var) ? $var : '';
}

function get_var($var)
{
	return isset($var) ? $var : '';
}

function getArrayString($array, $key, $default = '')
{
	$value = isset($array[$key]) ? $array[$key] : $default;
	$value = is_string($value) ? $value : var_dump($value);
	return get_var($value);
}

// if (!function_exists('is_value_null_for_keys')) {
function populateArrayWithKey($arrays = array(), $key = '')
{
	$newArray = [];
	foreach ($arrays as $array) {
		$newArray[] = $array[$key];
	}
	return $newArray;
}
// }

function csrf_cookie(): string
{
	$config = config(App::class);
	return $config->CSRFCookieName;
}

/**
 * get site settings
 * 
 * @param	array	settings array form get_all()
 * @param	string	Key
 * @return 	string
 * @author	Naufal Hakim
 */
function get_setting($array, $key){
	$id = array_search($key, array_column($array, 'name'));
	return get_var($array[$id]['value']);
}

/**
 * Clean String for Value Input
 * 
 * @param	string	Value String
 * @return 	string	Cleaned Value Safe for Input Form
 * @author	Naufal Hakim
 */
function cleanValue($string)
{
	return get_var(cleanString($string));
}

/**
 * get admin prefix from env
 * 
 * @return	string|null
 * @author	Naufal Hakim
 */
function get_admin_prefix(){
	if(!empty($_ENV['ADMIN_PREFIX'])){
		$adminPrefix = $_ENV['ADMIN_PREFIX'];
	}  else {
		$adminPrefix = '';
	}
	return $adminPrefix;
}

/**
 * Site Url Builder untuk admin
 * add support for admin prefix
 * 
 * @param	string	url to go 'admin/setting'
 * @return	string
 * @author 	Naufal Hakim
 */
function adminURL($string){	
	if (empty($string)) {
		return site_url(get_admin_prefix());
	}
	return site_url(get_admin_prefix() . '/' . $string);
}

/**
 * Redirect admin url with prefix
 * 
 * @param	string	url to go 'admin/setting'
 * @return	string|\CodeIgniter\HTTP\RedirectResponse
 * @author	Naufal Hakim
 */
function redirectAdmin($string){
	return redirect()->to(adminURL($string));
}