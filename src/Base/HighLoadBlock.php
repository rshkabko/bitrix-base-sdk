<?php
namespace Flamix\Base;

//new \Bitrix\Main\Entity\ExpressionField('ALL_PRICE', 'SUM(UF_PRICE)')

Class HighLoadBlock {

    private static $init 		= false;
    private static $hlblock 	= false;
    private static $entity 		= false;

    private static $arSelect 	= array( '*' );
    private static $arFilter 	= array();
    private static $arOrder 	= array();
    private static $iLimit 		= false;
    private static $iD 			= false;

    /**
     *	Скидываем все функции
     */
    public static function resert()
    {
        self::$init 		= false;
        self::$hlblock 		= false;
        self::$entity 		= false;

        self::$arSelect 	= array( '*' );
        self::$arFilter 	= array();
        self::$arOrder 		= array();
        self::$iLimit 		= false;
        self::$iD 			= false;
    }

    /**
     *	Инициализация
     *
     *	@param integer	$iHl_ID	ID HighLoad блока
     *
     *	@example \Flamix\Base\HighLoadBlock::init( 1 );
     *
     *	@return object	Инициализированный объект битрикса HighloadBlockTable
     */
    public static function init( int $iHl_ID )
    {
        if( !$iHl_ID )
            throw new \Exception( 'Неверно передан ID Highload Blockа' );

        if(self::$init)
            self::resert();

        \Bitrix\Main\Loader::includeModule('highloadblock');

        self::$hlblock 	= \Bitrix\Highloadblock\HighloadBlockTable::getById( $iHl_ID )->fetch();
        self::$entity 	= \Bitrix\Highloadblock\HighloadBlockTable::compileEntity( self::$hlblock )->getDataClass();

        self::$init = new HighLoadBlock();
        return self::$init;
    }

    /**
     *	Переопределяем переменную $test
     */
    public static function setSelect( array $select )
    {
        self::$arSelect = $select;
        return self::$init;
    }

    /**
     *	Переопределяем переменную $test
     */
    public static function setFilter( array $filter )
    {
        self::$arFilter = $filter;
        return self::$init;
    }

    /**
     *	Переопределяем переменную $test
     */
    public static function setOrder( array $order )
    {
        self::$arOrder = $order;
        return self::$init;
    }

    /**
     *	Переопределяем переменную $test
     */
    public static function setLimit( int $limit )
    {
        self::$iLimit = $limit;
        return self::$init;
    }

    /**
     *	Переопределяем переменную $test
     */
    public static function setID( int $iD )
    {
        self::$iD = $iD;
        return self::$init;
    }

    /**
     * Получение списка записей из highload блока
     *
     */
    public static function getList()
    {
        $entity_data_class = self::$entity;

        $rsData = $entity_data_class::getList( array('select' => self::$arSelect, 'filter' => self::$arFilter, 'order' => self::$arOrder, 'limit' => self::$iLimit) );
        $rsData = new \CDBResult( $rsData, 'tbl_' . self::$hlblock['TABLE_NAME'] );

        while($arData = $rsData->Fetch())
            $arResult[] = $arData;

        return $arResult;
    }

    /**
     * Получение данных записи из highload блока
     *
     * $HL_ID - ID highload блока
     * $ID - ID  записи
     */
    public static function getByID( int $ID )
    {
        if( !$ID)
            throw new \Exception( 'Неверно передан ID элемента' );

        self::setFilter( array( "ID" => $ID ) );
        self::setLimit(1);
        $arItems = self::GetList();

        return $arItems[0];
    }


    /**
     * Добавление записи в highload блок
     *
     * $HL_ID - ID highload блока
     * $arFields - массив данных записи
     */
    public static function add( array $arFields )
    {
        if( !$arFields || empty($arFields) )
            throw new \Exception( 'Передан пустой массив параметров' );

        $entity_data_class = self::$entity;
        $ID = $entity_data_class::add( $arFields )->getId();

        if ( $ID >= 1 )
            return $ID;
        else
            return false;
    }

    /**
     * Обновлние записи в highload блоке
     *
     * $HL_ID - ID highload блока
     * $ID - ID записи
     * $arFields - массив данных записи
     */
    public static function update( array $arFields )
    {

        if( !$arFields || empty($arFields) )
            throw new \Exception( 'Передан пустой массив параметров' );

        if( !self::$iD )
            throw new \Exception( 'Ненайден ID элемента для обновления, воспользуйтесь классом setID( $ID ) ' );

        $entity_data_class = self::$entity;
        $ID = $entity_data_class::update( self::$iD, $arFields )->getId();

        if ( $ID >= 1 )
            return true;
        else
            return false;
    }

    /**
     * Удаление записи в highload блоке
     *
     */
    public static function delete()
    {
        if( !self::$iD )
            throw new \Exception( 'Ненайден ID элемента для обновления, воспользуйтесь классом setID( $ID ) ' );

        $entity_data_class = self::$entity;
        return $entity_data_class::Delete( self::$iD );
    }
}