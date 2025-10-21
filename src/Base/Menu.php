<?php
namespace Flamix\Base;
class Menu {

	private static $text_menu_href = array('/services/', '/shop/umi/', '/shop/bitrix/', '/shop/', '/shop/bitrix24/',);
	
	/**
	*	Формируем меню с правильным уровнем вложености
	*/
	public static function get_depth_menu($arResult = false){
		if(!$arResult)
			return false;
		$return = array();
		$parent_id = 0;

		foreach ($arResult as $key => $value) {
			$is_parent 		= $value["IS_PARENT"];
			$depth_level 	= $value["DEPTH_LEVEL"];

			if( $depth_level == 2 && self::is_parent($arResult[$key-1]) )
				$parent_id = $key - 1;

			if( $depth_level == 3 && self::is_parent($arResult[$key-1]) )
				$parent_parent_id = $key - 1;

			$value['parent_id'] = $parent_id;

			if($parent_id >= 0 && !$is_parent && $depth_level == 2)
				$return[$parent_id]['inner_menu'][$key] = $value;
			elseif($parent_id >= 0 && !$is_parent && $depth_level == 3)
				$return[$parent_id]['inner_menu'][$parent_parent_id]['inner_menu'][$key] = $value;
			elseif($parent_id >= 0 && $is_parent && $depth_level == 2)
				$return[$parent_id]['inner_menu'][$key] = $value;
			else
				$return[$key] = $value;
		}

 		ksort($return);
		return $return;
	}


	/**
	*	Имеет ли элемент подменю?
	*/
    public static function is_parent($arResult = false){
		return $arResult["IS_PARENT"];
	}


	/**
	*	Уровень вложености
	*/
    public static function depth_level($arResult = false){
		return $arResult["DEPTH_LEVEL"];
	}


	/**
	*	Имеется ли следующий эллемент в массиве?
	*/
    public static function is_have_next($arResult = false){
		return is_array( $arResult['inner_menu'] );
	}


	/**
	*	Реальный селект?
	*/
    public static function is_select( $url ){
		if($_SERVER["REQUEST_URI"] == $url)
			return true;
		else
			return false;
	}


	/**
	*	Рендер пункта меню
	*/
    public static function show_url($arResult = false, $href_class = false, $inner_tag = ''){
		if(!$arResult)
			return false;

		$href = $arResult["LINK"];

		//Ставим дополнительные классы
		if($href_class)
			$href_class = ' class="'. $href_class . '"';

		//Проверяем наличие иконки
		if($arResult["PARAMS"]['ICON'])
			$inner_tag = '<i class="material-icons">' . $arResult["PARAMS"]['ICON'] . '</i>';

		if(self::is_have_next($arResult)){
			$caretca 	= ' <b class="caret"></b>';
			$href 		= '#' . $arResult["PARAMS"]['ICON'];
			$href_class = ' data-toggle="collapse"';
		}

		//<a href="/extranet/dashboard/"><p>Главная</p></a>
		return '<a href="' . $href . '" title="' . $arResult["TEXT"] . '"' . $href_class .'>' . $inner_tag . '<p>' . $arResult["TEXT"]  . ' ' . $caretca . '</p></a>';
	}
}