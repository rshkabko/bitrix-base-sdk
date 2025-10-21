<?php
namespace Flamix\Base;

class Helpers
{
    /**
     * Понятные события
     *
     * @return bool
     */
    public static function registerEvents()
    {
        $events_dir = $_SERVER['DOCUMENT_ROOT'] . '/local/classes/Events';
        if(!is_dir($events_dir))
            return false;

        $flamix_events = array();
        $class = scandir( $events_dir );

        foreach ($class as $value)
            if(  $value != '.' && $value != '..' && $value != 'index.php')
            {
                require( $events_dir . '/' . $value );
                $flamix_events[] = explode('.', $value);
            }

        if( !empty($flamix_events) )
            foreach ($flamix_events as $key => $value)
                if( function_exists($value['1']) )
                    \AddEventHandler($value['0'], $value['1'], $value['1']);
    }
}

