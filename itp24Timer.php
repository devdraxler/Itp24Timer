<?
	class itp24Timer{
	
		private static $table = Array();
		
		private static $config = Array(
			'unit'=>'seconds',
			'decimals'=>2,
			'separator'=>'.',
			'thousandsSeparator'=>' ',
			'language'=>'en'
		);

		private static $lang = Array(
			'all' => Array('nbsp'=>'&nbsp;'),
			'en' => Array(
				'title'=>'Process lead time&nbsp;',
				'existPoint'=>'Point %point% process %process% already exist',
				'noExistPoint'=>'Point% point% of process% process% does not exist',
				'fromPoint'=>'&nbsp;-&nbsp;from point&nbsp;',
				'toPoint'=>'&nbsp;to point&nbsp;',
				'seconds'=>'seconds',
				'minutes'=>'minutes',
				'hours'=>'hours',
				'noArray'=>'The passed parameter must be a filled array',
				'noString'=>'The parameter passed must be a filled string.',
				'languageErrorOrExist' => 'This language file was transferred in the wrong format or already exists.'
			)
		);

		private static function check($t,$v){
			if($t == 'array')
				if(!is_array($v)||!sizeOf($v))
						throw new Exception(self::$lang[self::$config['language']]['noArray']);
		}
		
		public static function config($a){
			self::check('array',$a);

			foreach($a as $k=>$i)
				if(isset(self::$config[$k])&&!empty($i))
					self::$config[$k] = $i;
		}
		
		public static function lang($a,$s = true){
			self::check('array',$a);

			foreach($a as $k=>$i):
				self::check('array',$i);

				if(isset(self::$config[$k])||empty($i))
					throw new Exception(self::$lang[self::$config['language']]['languageErrorOrExist']);

				self::$lang[$k] = $i;
			endforeach;
		}
		
		public static function point($pr,$p){
			if(isset(self::$table[$pr][$p]))
				throw new Exception(str_replace('%point%',$p,str_replace('%process%',$pr,self::$lang[self::$config['language']]['existPoint'])));

			self::$table[$pr][$p] = microtime(true);
		}

		public static function diff($pr,$fp,$tp,$s = 0){
			if(!isset(self::$table[$pr][$fp])&&empty(self::$table[$pr][$fp]))
				throw new Exception(str_replace('%point%',$fp,str_replace('%process%',$pr,self::$lang['noExistPoint'])));

			if(!isset(self::$table[$pr][$tp])&&empty(self::$table[$pr][$tp]))
				throw new Exception(str_replace('%point%',$tp,str_replace('%process%',$pr,self::$lang['noExistPoint'])));
			
			$diff = self::$table[$pr][$tp]-self::$table[$pr][$fp];
			
			if(self::$config['unit'] == 'seconds'):
				$diff /= 1;
			elseif(self::$config['unit'] == 'minutes'):
				$diff /= 60;
			elseif(self::$config['unit'] == 'hours'):
				$diff /= 60*60;
			
			endif;

			if($s):
				print strtolower(self::$lang[self::$config['language']]['title'].'<u>'.$pr.'</u>'.
						self::$lang[self::$config['language']]['fromPoint'].'<u>'.$fp.'</u>'.
								self::$lang[self::$config['language']]['toPoint'].'<u>'.$tp.'</u>'.
										self::$lang['all']['nbsp'].number_format($diff,self::$config['decimals'],self::$config['separator'],self::$config['thousandsSeparator']).
											self::$lang['all']['nbsp'].self::$lang[self::$config['language']][self::$config['unit']]);
			else:
				return $diff;
			endif;
		}
	}
?>
