<?php

namespace WP_Template_Performance;

class Profile {

	const INDEX = 1;

	protected static $index = self::INDEX;
	protected static $indexes = array();
	protected static $templates = array();

	public static function start($template){
		$current_index = self::index($template,true);
		self::$templates[$current_index] = array(
			'template' => $template,
			'start' => microtime(true),
			'position' => $current_index
		);

		$previous_index = $current_index - 1;
		if (!isset(self::$templates[$previous_index]['end']) && $previous_index >= self::INDEX){

			// If the previous element hasn't been closed yet, this is the
			// first child
			self::$templates[$current_index]['parent'] = $previous_index;

		} else if (
			isset(self::$templates[$previous_index]['parent'])
			&& isset(self::$templates[$previous_index]['end'])
		){
			// If the previous element has a parent, AND it's been closed,
			// traverse up the array for the last unclosed element
			$i = $previous_index;
			$walk_start = microtime(true);
			while(isset(self::$templates[$i]['end'])){
				$i--;
			}
			if($i >= self::INDEX){
				self::$templates[$current_index]['parent'] = $i;
			}
			// Let's track how long it took to walk up the array so we can
			// remove it for more accurate render time
			self::$templates[$current_index]['walk_time'] = microtime(true) - $walk_start;
		}

	}

	public static function end($template){
		$index = self::index($template);
		self::$templates[$index]['end'] = microtime(true);
		self::$templates[$index]['render_time'] = self::$templates[$index]['end'] - self::$templates[$index]['start'] - self::$templates[$index]['walk_time'];
	}

	protected static function index($template, $increment = false){
		if( $increment ) {
			self::$indexes[$template] = self::$index++;
		}
		$index = self::$indexes[$template];
		return $index;
	}

	public static function get_statistics($return_sorted = false){
		$statistics = self::$templates;
		if($return_sorted){
			usort($statistics, function($a, $b) {
				$x = $a['render_time'];
				$y = $b['render_time'];
				if ($x == $y){
					return 0;
				}
				return ($x > $y) ? -1 : 1;
			});
		}
		return $statistics;
	}
}
