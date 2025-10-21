<?php
namespace Flamix\Base;

trait BaseTrait
{

	private static $init = false;
	private static $_error = array();


	/**
	*	Инициализация для статики
	*/
	public static function init(){
		// //Инициализация класса
		$ClassName = '\\'.get_called_class();

		if( !is_object(self::$init) )
			self::$init = new $ClassName;

		return self::$init;		
	}

	/**
	*	Инициализация для статики
	*/
	private static function setError( $msg = 'Error' ){
		self::$_error[] = $msg;
	}

	/**
	*	Инициализация для статики
	*/
	public static function hasError(){
		return !empty( self::$_error );
	}

	/**
	*	Инициализация для статики
	*/
	public static function getError(){
		return self::$_error;
	}

	/**
	*	Инициализация для статики
	*/
	public static function throwException( $separator = ', ', $error_code = false ){
		if( !$error_code )
			$error_code = count(self::$_error);

		if( self::hasError() )
			throw new \Exception( implode( $separator, self::$_error ), $error_code );
	}
}