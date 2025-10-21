<?php
namespace Flamix\Base;

class DB {
    private static $debug = false;

    /**
    *   Строит Query, возвращает массив
    */
    function query($sql = false){
        global $DB;
        $array = Array();

        $result = $DB->query($sql, false);
        while ($record = $result->fetch()){
            $array[] = $record;
        }

        if( self::$debug ) echo $sql;

        if( !empty($array) ) return $array;

        return false;
    }

    /**
    *   Строит Query, возвращает массив
    */
    function b_query($sql = false){
        global $DB;
        $array = Array();

        $result = $DB->query($sql, false);
        while ($record = $result->fetch()){
            $array[] = $record;
        }

        if( self::$debug ) echo $sql;

        if( !empty($array) ) return $array;

        return false;
    }

    /**
    *   Строит Query, возвращает массив
    */
    function query_one($sql = false){
        global $DB;

        $result = $DB->query($sql, false);

        if( self::$debug ) echo $sql;

        return $result->fetch();
    }

    /**
    *   Строит Query, возвращает массив
    */
    function b_query_one($sql = false){
        global $DB;

        $result = $DB->query($sql, false);

        if( self::$debug ) echo $sql;

        return $result->fetch();
    }


    /**
    *   Берет одно значение по параметру
    */
    function get_var($var_name = false, $table = false, $where = false){
        $where_insert = "WHERE 1 = 1 ";

        if($where)
            $where_insert .= 'AND ' . $where;
        
        $sql = "SELECT {$var_name}
                    FROM {$table}
                        {$where_insert }
                            LIMIT 1";

        $return = self::b_query_one($sql);
        return $return[$var_name];
    }


    /**
    *   Вставляем в БД, вовращает ID вставки
    */
    function insert($table_name = false, $arFields = false){
        global $DB;

        $id = $DB->Insert( $table_name, $arFields );
        return intval( $id );
    }

    /**
    *   Обновляем табилцу
    */
    function update($table_name = false, $arFields = false, $where = false){
        global $DB;
        $where_insert = "WHERE 1 = 1 ";
        if(!$table_name || empty($arFields))
            return false;

        if($where)
            $where_insert .= 'AND ' . $where;

        return $DB->Update( $table_name, $arFields, $where_insert, $err_mess.__LINE__);
    }


    /**
    *   Собирает последнее ID
    */
    function get_last_param($table = false, $param = 'ID'){
        $sql = "SELECT {$param}
                    FROM {$table}
                        ORDER BY {$param} DESC
                            LIMIT 1";

        $return = self::b_query_one($sql);
        return $return[$param];
    }

    /**
    *   Подготавливаем STRING
    */
    function to_string($string = false){
        return "'".trim($string)."'";
    }

    /**
    *   Подготавливаем INT
    */
    function to_int($int = false){
        return intval( $int );
    }

    /**
    *   Подготавливаем FLOAT
    */
    function to_float($float = false){
        return floatval( $float );
    }




    /*BILL5*/
    /**
    *   Строит Query, возвращает массив
    */
    function bill_query_one( $sql = false ){
        global $BILL5;

        $result = $BILL5->query($sql, false);

        if( self::$debug ) echo $sql;

        return $result->fetch();
    }
}