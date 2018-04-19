<?php
/*--------------------------
=tfIDF
--------------------------*/

class tfIDF{
	public static $word;
	public static $document;

  // hookを登録します
	public function __construct() {

	}
	public function check($value){
		// Swap newlines for spaces, you'll see why
	  $doc = str_replace("\n",' ',self::$document);
	  $doc = strtolower($doc);
	  // Remove special characters except '-' from the string
	  for($i = 0; $i <= 127; $i++)
	  {
	      // Space is allowed, Hyphen is a legitimate part of some words. Also allow range for 0-9, A-Z, and a-z
	      // Extended ASCII (128 - 255) is purposfully excluded from this since it isn't often used
	      if($i != 32 && $i != 45 && !($i >= 48 && $i <=57) && !($i >= 65 && $i <= 90) && !($i >= 97 && $i <= 122))
	          $doc = str_replace(chr($i),'',$doc);
	  }
	  // Split the document on spaces. This gives us individual words
	  $tmpDoc = explode(' ',trim($doc));

	  // Get the number of elements with $word in them
	  $occur = count(array_keys($tmpDoc,$value));
	  // Get the total number of elements
	  $numWords = count($tmpDoc);

	  $returnText = $occur / $numWords;

	  return $returnText;
	}

	public function action(){
		$arr_tfIDF = array();
		foreach(self::$word as $key => $value ):
			$res_tfIDF = self::check($value);
			$arr_keys = array($value=>$res_tfIDF);
			$arr_tfIDF = array_merge($arr_tfIDF,$arr_keys);
		endforeach;

		return $arr_tfIDF;
	}

	//連想配列 最大値表示
	public function max(array $arr) {
		$max = max($arr);
		$arrFind = array_keys($arr, $max);
		$key = array_rand($arrFind, 1);

		return $arrFind[$key];
	}
}

function s_get_posts(){
	$arr = array();
	$args = array(
    'post_type' => 'post',
    'posts_per_page' => 10
  );
  $st_query = new WP_Query( $args );

  if ( $st_query->have_posts() ):
  	while ( $st_query->have_posts() ) : $st_query->the_post();
  		return array_push($arr,the_title());
  	endwhile;
  endif;
}

//管理画面に表示
if(is_admin()):

	$tfIDF = new tfIDF();
	tfIDF::$word = s_get_posts();
	tfIDF::$document = "Welcome hello world";
	$featureNum = $tfIDF->action();
	$featureNumMax = $tfIDF->max($featureNum);

	_var_dump(tfIDF::$word);

endif;

/*--------------------------
END | =tfIDF
--------------------------*/
?>