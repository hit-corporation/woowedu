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
    if($session!='y')
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