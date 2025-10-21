<?php
namespace Flamix\Base;

class User {

    public static function get_ID()
    {
        global $USER;
        return $USER->GetID();
    }

    public static function isAdmin()
    {
        global $USER;
        if($USER->IsAdmin())
            return true;

        return false;
    }

    public static function get( array $filter, array $select = array('*'), int $limit = 0, array $dop = array() )
    {
        $return = array();
        $params = array(
            "select"=>$select,
            "filter"=>$filter,
        );

        if($limit)
            $params['limit'] = $limit;

        if(!empty($dop))
            array_merge( $params, $dop );

        $result = \Bitrix\Main\UserTable::getList($params);

        while ($arRes = $result->fetch())
            $return[] = $arRes;

        if( $limit == 1 && !empty($return))
            return end($return);
        else
            return $return;
    }

    public static function getUserFields( $select = array('*'), $userID = false )
    {
        if(!$userID)
            $userID = self::get_ID();

        $params = array(
            "select"    => $select,
            "filter"    => array( 'ID' => $userID ),
            'limit'     => 1,
        );

        $result = \Bitrix\Main\UserTable::getList($params)->fetchAll();

        //Это не баг))
        if(count($result) === 1)
            $result = end($result);

        if(count($result) === 1)
            $result = end($result);

        //Поидее, мы сюда никогда не зайдем
        return $result;
    }

    public static function update( int $id, array $update )
    {
        $user = new \CUser;
        $user->Update( $id, $update );

        if(!empty($user->LAST_ERROR))
            throw new \Exception($user->LAST_ERROR);

        return true;
    }

    public static function update_current( array $update )
    {
        $user_id = self::get_ID();
        if(!$user_id)
            throw new \Exception('Bad USER ID!');
        return self::update( $user_id, $update );
    }

    public static function add_user_to_group( int $user_id, int $group_id )
    {
        $groups = \CUser::GetUserGroup($user_id);
        $groups[] = $group_id;

        \CUser::SetUserGroup( $user_id, $groups );
    }

    public static function is_user_in_group( int $user_id, int $group_id )
    {
        $groups = \CUser::GetUserGroup($user_id);
        if(empty($groups))
            return false;

        if( in_array($group_id, $groups) )
            return true;

        return false;
    }

    public static function get_users_in_department( int $department_id )
    {
        \CModule::IncludeModule("im");
        $result = \Bitrix\Im\Department::getEmployeesList();

        if(!empty($result[$department_id]))
            return $result[$department_id];

        return false;
    }
}