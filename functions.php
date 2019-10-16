<?php
namespace FinAid\Theme;


// Theme foundation
include_once 'includes/config.php';
include_once 'includes/meta.php';
include_once 'includes/header-functions.php';
include_once 'includes/breadcrumb-functions.php';
include_once 'includes/chatbot-functions.php';

// Plugin extras/overrides

if ( class_exists( 'UCF_Section_Common' ) ) {
	include_once 'includes/section-functions.php';
}
