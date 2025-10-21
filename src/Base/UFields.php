<?php
namespace Flamix\Base;

class UFields {

    private static $instances;
    private static $id;
    private static $name;
    private static $uf_data = array();

    private static function getInstance()
    {
        self::$instances = new static;
        return self::$instances;
    }

    /**
     * Вытягиваем реальное ID элемента по его Code и инициализируем его (основная инициализация проходит по ID)
     *
     * @param string $name
     * @return UFields
     * @throws \Exception
     */
    public static function initByCode( string $name )
    {
        self::$name = $name;

        $result = \CUserTypeEntity::GetList(array("ID" => "ASC"), array("FIELD_NAME" => $name))->Fetch();

        if($result['ID'] > 0)
            return self::initByID($result['ID']);
        else
            throw new \Exception('Bad ID in FIELD by NAME ' . $name );
    }

    /**
     * Вся инициализация проходит тут
     *
     * @param int $id
     * @return UFields
     * @throws \Exception
     */
    public static function initByID( int $id )
    {
        if(!$id )
            throw new \Exception('Bad ID!');
        self::$id = $id;

        $field = \CUserTypeEntity::GetByID( self::$id );
        if(empty($field))
            throw new \Exception( 'Bad DATA in Field!' );
        //Нужно сбросить перед работой
        self::$uf_data = array();
        self::$uf_data['FIELD'] = \CUserTypeEntity::GetByID( self::$id );

        $userTypeEnumsIterator = \CUserFieldEnum::GetList(array('SORT' => 'ASC'), array('USER_FIELD_ID' => self::$id));
        while ($uf = $userTypeEnumsIterator->Fetch()) {
            self::$uf_data['VALUE'][] = $uf;
        }

        /*
         * Если один элемент - значит возвращаем только его не как массив
         */
        if(count(self::$uf_data['VALUE']) === 1 )
            self::$uf_data['VALUE'] = end(self::$uf_data['VALUE']);

        return self::getInstance();
    }

    /**
     * Возвращаем ID
     *
     * @return mixed
     */
    public static function getID()
    {
        return self::$id;
    }

    /**
     * Возвращаем ВСЕ данные
     *
     * @return array
     */
    public static function get()
    {
        return self::$uf_data;
    }

    /**
     * Возвращаем имя на текущем языке
     *
     * @return array
     */
    public static function getName()
    {
        return self::$uf_data['FIELD']['EDIT_FORM_LABEL'][LANGUAGE_ID];
    }

    /**
     * Возвращаем имя на текущем языке
     *
     * @return array
     */
    public static function getCode()
    {
        return self::$uf_data['FIELD']['FIELD_NAME'];
    }

    /**
     * Возвращаем тип (например - enumeration )
     *
     * @return array
     */
    public static function getType()
    {
        return self::$uf_data['FIELD']['USER_TYPE_ID'];
    }


    /**
     * Мультиполе или нет?
     *
     * @return bool
     */
    public static function isMultiple()
    {
        if(self::$uf_data['FIELD']['MULTIPLE'] == 'Y')
            return true;

        return false;
    }

    /**
     * Возвращаем имя на текущем языке
     *
     * @return array
     */
    public static function getVal()
    {
        $value = self::$uf_data['VALUE'];
        if(is_array($value))
            foreach ($value as $key => $val) {
                if(!$val['XML_ID'])
                    continue;

                unset($value[$key]);
                $value[$val['XML_ID']] = $val;
            }

        return $value;
    }

    /**
     * Возвращает дефолтное значение
     *
     * @return bool
     */
    public static function getDefault()
    {
        if(!empty(self::$uf_data['FIELD']['DEFAULT_VALUE']))
            return self::$uf_data['FIELD']['DEFAULT_VALUE'];

        if(!empty(self::$uf_data['VALUE']))
            foreach (self::$uf_data['VALUE'] as $val)
                if($val['DEF'] == 'Y')
                    return $val;

        return false;
    }

    /**
     * Возвращает ID значения по XML_CODE
     *
     * @param $XML_CODE
     * @return int|string
     */
    public static function getValID( $XML_CODE )
    {
        //@TODO Доделать для других типов
        if(self::getType() == 'enumeration')
            return (isset(self::getVal()[$XML_CODE]))? (int) self::getVal()[$XML_CODE]['ID'] : '';


    }

}