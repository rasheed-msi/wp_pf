<?php
/**
 * class-widgets-control-shortcodes.php
 *
 * Copyright (c) www.itthinx.com
 *
 * This code is provided subject to the license granted.
 * Unauthorized use and distribution is prohibited.
 * See COPYRIGHT.txt and LICENSE.txt
 *
 * This code is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * This header and all notices must be kept intact.
 *
 * @author itthinx
 * @package widgets-control-pro
 * @since 1.5.0
 */

if ( !defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Implements shortcodes based on our conditional rendering facilities.
 */
class Widgets_Control_Shortcodes {

	/**
	 * Inside a condition tag.
	 * @var int
	 */
	const INSIDE = 0;

	/**
	 * Outside of a condition tag.
	 * @var int
	 */
	const OUTSIDE = 1;

	/**
	 * Adds our shortcode handlers.
	 */
	public static function init() {
		add_shortcode( 'widgets_control', array( __CLASS__, 'widgets_control' ) );
	}

	/**
	 * Shortcode handler for the [widgets_contro] shortcode.
	 * 
	 * @param array $atts parameters passed to the shortcode
	 * @param string $content content embedded within the shortcode's tags
	 * @return string HTML
	 */
	public static function widgets_control( $atts = array(), $content = '' ) {
		$output = '';
		$atts = shortcode_atts(
			array(
				'visibility' => 'show',
				'conditions' => ''
			),
			$atts
		);
		$atts['visibility'] = strtolower( trim( $atts['visibility'] ) );
		switch( $atts['visibility'] ) {
			case 'show' :
			case 'show not' :
				break;
			case 'show_not' :
			case 'show-not' :
				$atts['visibility'] = 'show not';
				break;
			default :
				$atts['visibility'] = 'show';
		}
		switch( $atts['visibility'] ) {
			case 'show not' :
				$visibility = WIDGETS_PLUGIN_SHOW_NOT_ON_THESE_PAGES;
				break;
			default :
				$visibility = WIDGETS_PLUGIN_SHOW_ON_THESE_PAGES;
		}
		$atts['conditions'] = trim( $atts['conditions'] );
		if ( empty( $atts['conditions'] ) ) {
			$output = $content;
		} else {
			$conditions = self::parse_conditions( $atts['conditions'] );
			$conditions = implode( "\n", $conditions );
			$show = Widgets_Control_Conditions::evaluate_display_condition( $visibility, $conditions );
			if ( $show ) {
				remove_shortcode( 'widgets_control' );
				$content = do_shortcode( $content );
				add_shortcode( 'widgets_control', array( __CLASS__, 'widgets_control' ) );
				$output = $content;
			}
		}
		return $output;
	}

	/**
	 * Parses the conditions string and returns an array of identified conditions and tokens.
	 *
	 * @param string $conditions
	 * @return array of conditions and tokens
	 */
	private static function parse_conditions( $conditions ) {
		$tag = '';
		$tags = array();
		$state = self::OUTSIDE;
		$negated = array();
		$n = 0;
		for ( $i = 0; $i < strlen( $conditions ); $i++ ) {
			switch( $conditions[$i] ) {
				case '!' :
					switch( $state ) {
						case self::OUTSIDE :
							$negated[$n] = true;
							break;
						default :
							$tag .= '!';
					}
					break;
				case '{' :
					switch ( $state ) {
						case self::OUTSIDE :
							$state = self::INSIDE;
							$tag = '';
							break;
						default :
							$tag .= '{';
					}
					break;
				case '}' :
					switch ( $state ) {
						case self::INSIDE;
							$tags[] = $tag;
							$state = self::OUTSIDE;
							$n++; // next tag
							break;
						default :
							$tag .= '}';
					}
					break;
				case ' ' :
				case "\t" :
				case "\n" :
				case "\r" :
					switch( $state ) {
						case self::INSIDE :
							$tag .= ' ';
							break;
					}
					break;
				default :
					switch( $state ) {
						case self::INSIDE :
							$tag .= $conditions[$i];
							break;
					}
			}
		}
		$n = 0;
		$_tags = array();
		foreach( $tags as $tag ) {
			$tag = trim( $tag );
			if (
				stripos( $tag, 'id:' ) === 0 ||
				stripos( $tag, 'title:' ) === 0 ||
				stripos( $tag, 'slug:' ) === 0
			) {
				$tag = substr( $tag, strpos( $tag, ':' ) + 1 );
			} else {
				$tag = ( isset( $negated[$n] ) && $negated[$n] ? '!' : '' ) . '[' . $tag . ']';
			}
			$_tags[] = $tag;
			$n++;
		}
		return $_tags;
	}
}
Widgets_Control_Shortcodes::init();
