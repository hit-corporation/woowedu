<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// function check_loggin() {
// 	$CI =& get_instance();
// 	$user = $CI->session->userdata('username');
// 	if (!isset($user)) { return false; } else { return true; }
// }

function check_Loggin()
{
    $CI= & get_instance();
    $session=$CI->session->userdata('status_login');
    $level = $CI->session->userdata('user_level');
    $bisaliat = [1, 10];
    if($session!='y' && !in_array(intval($level), $bisaliat))
    {
        redirect('auth/login');
    }
}

function chek_session_login()
{
    $CI= & get_instance();
    $session=$CI->session->userdata('status_login');
    if($session=='y')
    {
        redirect('dashboard');
    }
}

function check_auth($token) {

    $ci = &get_instance();

    if(empty($token))
        return false;

    $token = ltrim($token, 'Basic ');

    $auth = explode(':', base64_decode($token));

    if(empty($auth[0]))
        return false;

    $user = $ci->db->get_where('users', ['username' => $auth[0]])->row_array();

    if(!password_verify($auth[1], $user['password']))
        return false;

    return true;
}