<?php

class PLT_Helper {
		
	/**
	 * Возвращает ссылку на телефон.
	 *
	 * @param string $phone
	 *
	 * @return string
	 */
	static function phone_link($phone) {
		return '<a href="tel:'.PLT_Helper::clean_phone_number($phone).'">'.$phone.'</a>';
	}
	
	/**
	 * Очищает номер телефона.
	 *
	 * @param string $phone
	 *
	 * @return string
	 */
	static function clean_phone_number($phone) {
		return preg_replace('/[^0-9+]/', '', $phone);
	}
		
	/**
	 * Возвращает правильную форму.
	 *
	 * @param int $number
	 * @param string $after
	 *
	 * @return string
	 */
	static function plural_form($number, $after) {
	  $cases = array (2, 0, 1, 1, 1, 2);
	  return $number.' '.$after[ ($number%100>4 && $number%100<20)? 2: $cases[min($number%10, 5)] ];
	}
	
}