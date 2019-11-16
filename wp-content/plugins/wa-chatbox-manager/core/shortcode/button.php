<?php

if (!function_exists('chatbox_manager_button_function')) {

	function chatbox_manager_button_function($atts,  $content = null) {
		
		extract(shortcode_atts(array(
			'chatboxtype' => '',
			'position' => '',
			'layout' => '',
			'size' => '',
			'number' => '',
			'text' => '',
			'icon' => '',
			'position1' => '',
			'position2' => '',
			'prefilledmessage' => '',
			'chatboxid' => 'chatbox',
		), $atts));
		
		$html = '';
		
		$chatboxWrapper = '';
		$chatboxWrapperSpan = '';
		$chatboxWrapperTooltip = '';
		$chatboxWrapperIcon = '';
		$chatboxWrapperNoIcon = '';

		$cssClass = '.' . $chatboxid;
						
		$fixedPosition = str_replace('chatbox-wrapper', '', esc_attr($position));
		$fixedPosition = explode('-', $fixedPosition);

		if ( $chatboxtype == 'floating' && ( $position1 || $position2 ) ) :
	
			$chatboxWrapper .= ($position1) ? $fixedPosition[0] . ':' . esc_attr($position1) . 'px;' : '';
			$chatboxWrapper .= ($position2) ? $fixedPosition[1] . ':' . esc_attr($position2) . 'px;' : '';
		
		else :
		
			$chatboxWrapper .= '';
							
		endif;

		$chatboxWrapper .= 'line-height:' . esc_attr($size). 'px;';
		$chatboxWrapperSpan .= 'line-height:' . esc_attr($size). 'px;';

		if ( $layout == 'layout-1' || $layout == 'layout-2' ) :

			$chatboxWrapperIcon .= 'line-height:' . esc_attr($size). 'px;';
			$chatboxWrapperTooltip .= 'line-height:' . esc_attr($size). 'px;';

		else :
		
			$chatboxWrapperIcon .= '';
			$chatboxWrapperTooltip .= '';
							
		endif;
		
		$chatboxWrapperIcon .= 'font-size:' . esc_attr($size). 'px !important;';
		
		if ( $icon == 'none' ) :

			$chatboxWrapperNoIcon .= 'font-size:' . esc_attr($size). 'px !important;';
			$chatboxWrapperNoIcon .= 'line-height:' . esc_attr($size). 'px !important;';

		else :
		
			$chatboxWrapperNoIcon .= '';
							
		endif;
						
		if ( $layout == 'layout-3' || $layout == 'layout-4' || $layout == 'layout-5' ) :

			$chatboxWrapper .= 'width:' . (esc_attr($size)+30) . 'px;';
			$chatboxWrapper .= 'height:' . (esc_attr($size)+30) . 'px;';
			$chatboxWrapper .= 'line-height:' . (esc_attr($size)+30) . 'px;';

		else :
		
			$chatboxWrapper .= '';
							
		endif;
		
		$link = ( wp_is_mobile() ) ? 'api' : 'web';

		$cssClasses = array(
			esc_attr($chatboxid),
			($icon=='none') ? 'no-icon' : '',
			'chatbox-wrapper',
			'chatbox-' . esc_attr($layout),
			'chatbox-wrapper-' . esc_attr($position),
		);

		$html .= '<a';
			
			$html .= ' target="_blank" class="' . implode(' ', $cssClasses).'"';
			$html .= ' style="' . $chatboxWrapper . '"';
			$html .= ' href="https://'.$link.'.whatsapp.com/send?';
			$html .= 'phone=' . esc_attr($number) . '&text=' . esc_attr(rawurlencode($prefilledmessage));
			
		$html .= '">';

		switch ($icon) {

			default:
			case 'icon-1':

				$html .= '<i class="wa-icon wa-icon-1" style="' . $chatboxWrapperIcon . '"></i>';
			
			break;
			
			case 'icon-2':
	
				$html .= '<i class="wa-icon wa-icon-2" style="' . $chatboxWrapperIcon . '">';
					$html .= '<span style="' . $chatboxWrapperSpan . '" class="path1"></span>';
					$html .= '<span style="' . $chatboxWrapperSpan . '" class="path2"></span>';
					$html .= '<span style="' . $chatboxWrapperSpan . '" class="path3"></span></span>';
				$html .= '</i>';
			
			break;

			case 'none':
	
				$html .= '';
			
			break;

		}

			$html .= ($text) ? '<span style="' . $chatboxWrapperTooltip . ' ' . $chatboxWrapperNoIcon . '" class="chatbox-tooltip">' . $text . '</span>' : '' ;
			$html .= '<div class="clearfix"></div>';
		
		$html .= '</a>';
		
		$html .= '<div class="clearfix"></div>';
		
		return $html;
		
	}
	
	add_shortcode('chatbox_manager_button','chatbox_manager_button_function');

}

?>
