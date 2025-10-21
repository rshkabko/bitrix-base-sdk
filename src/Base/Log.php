<?php
namespace Flamix\Base;

//Пример вызова Log::add('Текст для логирования', $_POST, 'pay');
class Log {

	public static $debug = true;
	public static $dev_email = 'r.shkabko@teil.com.ua';
	
	/**
	*	Название файла для логов
	*/
	function define_path( $module = false ){
		if(!$module)
			$module = '';
		else
			$module = $module . '/';

		$dir = $_SERVER["DOCUMENT_ROOT"] . '/local/logs/'. $module;

		$file_url = $dir . date('Y_m_d') . '-' . md5(date('Y_m_d')) . '.log';

		//Если не существет директории, значит нужно создать
		if( !is_dir($dir) )
			mkdir( $dir, 0751);

		define( "LOG_FILENAME", $file_url );
		return $file_url;
	}


	/**
	*	Добавляем лог
	*	Log::add('Запрос поступил.', $_POST);
	*/
	function add( $text = false, $param = false, $module = false ){
		if( !self::$debug )
			return false;

		$file_url = self::define_path( $module );

		if(!$module)
			$module = "log";

		//Если есть дополнительные параметры, значит тоже пишем их в лог
		if( !empty($param) ) {
			ob_start();
				var_dump($param);
			$param_to_log = ob_get_contents();
			ob_end_clean();
		} else 
			$param_to_log = '';

		self::AddMessage2Log( $text . ' ' . $param_to_log, $module, 6, false, $file_url );
		return true;
	}


	/**
	*	Критическая ошибка, нужно оповещать админа о таких ошибках
	*/
	function alert( $text = false, $param = false, $module = false ){
		self::$debug = true;
		Mail::php_mail( self::$dev_email, 'Критическая ошибка', '<p>' . $text . '</p>' . '<p>Более подробно смотрите в логах!</p>' );
		return self::add( $text, $param, $module );
	}


	//Взяли из битрикса
	function AddMessage2Log( $sText, $sModule = "", $traceDepth = 6, $bShowArgs = false, $file_name = false ) {
	    if ($file_name){
	        if(!is_string($sText))
	        {
	            $sText = var_export($sText, true);
	        }
	        if (strlen($sText)>0)
	        {
	            ignore_user_abort(true);
	            if ($fp = @fopen( $file_name, "ab"))
	            {
	                if (flock($fp, LOCK_EX))
	                {
	                    @fwrite($fp, "Host: ".$_SERVER["HTTP_HOST"]."\nDate: ".date("Y-m-d H:i:s")."\nModule: ".$sModule."\n".$sText."\n");
	                    $arBacktrace = \Bitrix\Main\Diag\Helper::getBackTrace($traceDepth, ($bShowArgs? null : DEBUG_BACKTRACE_IGNORE_ARGS));
	                    $strFunctionStack = "";
	                    $strFilesStack = "";
	                    $firstFrame = (count($arBacktrace) == 1? 0: 1);
	                    $iterationsCount = min(count($arBacktrace), $traceDepth);
	                    for ($i = $firstFrame; $i < $iterationsCount; $i++)
	                    {
	                        if (strlen($strFunctionStack)>0)
	                            $strFunctionStack .= " < ";

	                        if (isset($arBacktrace[$i]["class"]))
	                            $strFunctionStack .= $arBacktrace[$i]["class"]."::";

	                        $strFunctionStack .= $arBacktrace[$i]["function"];

	                        if(isset($arBacktrace[$i]["file"]))
	                            $strFilesStack .= "\t".$arBacktrace[$i]["file"].":".$arBacktrace[$i]["line"]."\n";
	                        if($bShowArgs && isset($arBacktrace[$i]["args"]))
	                        {
	                            $strFilesStack .= "\t\t";
	                            if (isset($arBacktrace[$i]["class"]))
	                                $strFilesStack .= $arBacktrace[$i]["class"]."::";
	                            $strFilesStack .= $arBacktrace[$i]["function"];
	                            $strFilesStack .= "(\n";
	                            foreach($arBacktrace[$i]["args"] as $value)
	                                $strFilesStack .= "\t\t\t".$value."\n";
	                            $strFilesStack .= "\t\t)\n";

	                        }
	                    }

	                    if (strlen($strFunctionStack)>0)
	                    {
	                        @fwrite($fp, "    ".$strFunctionStack."\n".$strFilesStack);
	                    }

	                    @fwrite($fp, "----------\n");
	                    @fflush($fp);
	                    @flock($fp, LOCK_UN);
	                    @fclose($fp);
	                }
	            }
	            ignore_user_abort(false);
	        }
	    }
	}


}