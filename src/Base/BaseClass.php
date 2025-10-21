<?php
namespace Flamix\Base;

/**
* Base class
*/
class BaseClass
{
	use \Flamix\Base\BaseTrait;
	private static $test = 1;

	/**
	*	Умный AJAX ответ, DIE(1) писать не надо
	*
	*	@param string	$sStatus	Название статуса
	*	@param string	$aData 		Дополнительные параметры для ответа
	*
	*	@example \Flamix\Base\BaseClass::ajaxResponse('SUCCES', array('id' => 1, 'name' => 'Roman'));
	*
	*	@return json	Статус и параметры ввиде JSON с правильным окончанием строки
	*/
	public static function ajaxResponse( $sStatus, array $aData = array() ) {
  		die( json_encode( array_merge( array( 'sStatus' => $sStatus ), $aData ) ) );
	}

	/**
	*	Генерация пароля
	*
	*	@param int	$count 		К-во символов в пароле (по умолчанию 8)
	*
	*	@example \Flamix\Base\BaseClass::generatePassword();
	*
	*	@return string	Сгенерированный пароль
	*/
	public static function generatePassword( int $count = 8 ){
		$alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
		$pass = array();
		$alphaLength = strlen($alphabet) - 1; //put the length -1 in cache

		for ($i = 0; $i < $count; $i++) {
			$n = rand(0, $alphaLength);
			$pass[] = $alphabet[$n];
		}
		
		return implode($pass);
	}

	/**
	*	Переопределяем переменную $test
	*/
	public static function setTest( $testVar = false )
	{
		// self::init();

		self::$test = $testVar;
		// self::setError('Ошибка номер 1');

		if ( $testVar == 2 )
			self::setError('Давай 2 не писать');
			
		return self::$init;
	}

	/**
	*	Выводим переменные $test
	*/
	public static function getTest()
	{
		self::throwException();

		return self::$test;
	}
}

BaseClass::init();