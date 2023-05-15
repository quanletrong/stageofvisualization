<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!doctype html>
<html>
<head>
	<?php $this->load->view($template_f . 'component/header/header_meta_view'); ?>
	<?php $this->load->view($template_f . 'component/header/header_common_view'); ?>

	<?php 
	// check load file css, js theo tung page
	if(isset($header_page_css_js) && $header_page_css_js != '')
	{
		$this->load->view($template_f . 'component/header/pages/header_' . $header_page_css_js . '_view');
	}
	?>
</head>
<?php flush();?>
<body>
    <?php $this->load->view($template_f . 'component/header/header_menu_view'); ?>

	