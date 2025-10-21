<?php
namespace Flamix\Base;

/*
*	@example \Flamix\Base\Mail::to( 'r.shkabko@teil.com.ua' )->title( 'Тестируем отправку' )->message( 'Здоров Роман!' )->send();
*/

class Mail {
    use \Flamix\Base\BaseTrait;

    //Служебная переменная для ХАРДОВОГО вывода ошибок
    private static $debug = false;
    /* Переменные класса по умолчанию */
    private static $template 	= MAIN_DEFAULT_TEMPLATE_ID;
    private static $email_id 	= MAIN_DEFAULT_EMAILE_ID;
    private static $params 		= array();

    /**
     *	Переинициализация
     */
    public static function reinit(){
        self::$template 	= MAIN_DEFAULT_TEMPLATE_ID;
        self::$email_id 	= MAIN_DEFAULT_EMAILE_ID;
        self::$params 		= array();
    }

    /**
     *	Отправка почты средствами PHP
     */
    public static function php_mail( $to = false, $subject = false, $message = false ){
        $message_html = "<html><head><title>{$subject}</title></head><body>{$message}</body></html>";

        $headers= "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=utf-8\r\n";
        $headers .= "From: FLAMIX LOG <alert@{$_SERVER['SERVER_NAME']}}>\r\n";

        return mail($to, $subject, $message_html, $headers);
    }

    /**
     *	Проверяем формат, почта или не почта
     */
    public static function isEmail( $email = false ){
        if( filter_var($email, FILTER_VALIDATE_EMAIL) )
            return true;
        else
            return false;
    }

    /**
     *	Включения режима отладки
     */
    public static function debug(){
        self::$debug = true;
        $template_name = self::$template;

        //Обращаемся в БД
        $sql = "SELECT *
					FROM b_event 
						WHERE event_name LIKE '%{$template_name}%' order by date_insert desc";

        var_dump( FXDB::query($sql) );
        return self::$init;
    }

    /**
     *	Ставим параметры
     */
    public static function setParams( $param = false ){
        if( !is_array($param) )
            throw new \Exception('setParams. В параметрах должен передаваться массив');

        if( !MAIN_DEFAULT_TEMPLATE_ID )
            throw new \Exception('Bad MAIN_DEFAULT_TEMPLATE_ID');

        if( !MAIN_DEFAULT_EMAILE_ID )
            throw new \Exception('Bad MAIN_DEFAULT_EMAILE_ID');

        self::$params = array_merge($param, self::$params );
        return self::$init;
    }

    /**
     *	Кому прислать
     */
    public static function to( $email = false ){
        if( !self::isEmail( $email ) )
            throw new \Exception('to. Неверный формат почты.');

        self::setParams( array( 'EMAIL_TO' => $email ) );
        return self::$init;
    }

    /**
     *	Отправить пользователя
     */
    public static function toUser( $id = false ){
        $user = \USER::get_info( $id );

        if( !self::isEmail($user["EMAIL"]) )
            throw new \Exception('toUser. Укажите почту в профиле!');

        self::to( $user["EMAIL"] );
        return self::$init;
    }

    /**
     *	От кого
     */
    public static function from( $email = false ){
        if( !self::isEmail( $email ) )
            throw new \Exception('from. Неверный формат почты.');

        self::setParams( array( 'EMAIL_FROM' => $email ) );
        return self::$init;
    }

    /**
     *	Заголовок письма
     */
    public static function title( $title = false ){
        self::setParams( array( 'TITLE' => $title ) );
        return self::$init;
    }

    /**
     *	Сообщение
     */
    public static function message( $message = false ){
        self::setParams( array( 'MESSAGE' => $message ) );
        return self::$init;
    }

    /**
     *	Изменяет название шаблона
     */
    public static function setTemplate($name = false){
        self::$template = $name;
        return self::$init;
    }

    /**
     *	Изменяет название шаблона
     */
    public static function setMailId( $id = false ){
        self::$email_id = $id;
        return self::$init;
    }

    /**
     *	Отправка сообщения средставими Битрикс
     */
    public static function send( $param = array() ){
        $param = array_merge($param, self::$params );
        \Log::add("Отправка сообщения. Шаблон " . self::$template . ", ID сообщения " . self::$email_id, $param, 'mail');

        //Добавляем картинки
        if( !empty( $param['DATA_IMG_IDS'] ) ){
            $image_id = explode(';', $param['DATA_IMG_IDS']);
            \Log::add("Была закреплена картинка ", $image_id, 'mail');
        } else
            $image_id = false;

        //Непосердственно отправка
        $id = \CEvent::Send( self::$template, SITE_ID, $param, "Y", self::$email_id, $image_id );
        self::reinit();
        return $id;
    }

    /**
     *	Обычная отправка данных
     */
    public static function just_send( $id = false ){
        if(!$id)
            self::setMailId( $id );
        $id = self::$init->send( $_GET );

        self::reinit();
        return $id;
    }
}

Mail::init();