<?php
/*--------------------------
=wiki
--------------------------*/

//wikiから情報抽出
$searchTitle='愛禾みさ';
$url='https://ja.wikipedia.org/w/api.php?action=query&format=xml&redirects=&prop=categories&=cllimit=500&titles=';
$url.=urlencode($searchTitle);

$array_title = array();

$xml_string=file_get_contents($url);
$xmlObject=simplexml_load_string($xml_string);
foreach($xmlObject->query->pages->page->categories->cl as $p){
	array_push($array_title,$p['title']);
}

//Category:日本のタレントが情報に含まれているかどうか
if(in_array('Category:日本のタレント',$array_title) || in_array('Category:日本のアイドル',$array_title)){
	datOpen($searchTitle);
}

function datOpen($title){

	//該当ファイルの書き込み
	$fp = @fopen('wiki.dat', 'ab');
	if ($fp){
      if (flock($fp, LOCK_EX)){
          if (fputs($fp,$title.",") === FALSE){
              print('ファイル書き込みに失敗しました');
          }

          flock($fp, LOCK_UN);
      }else{
          print('ファイルロックに失敗しました');
      }
  }
  fclose($fp);

  // 重複排除
	$aryData = file("wiki.dat");
	$aryUnique = array_unique($aryData);
	$fp = @fopen('wiki.dat', 'w');
	flock($fp, LOCK_EX);
	foreach($aryUnique as $value){
		//ﾌｧｲﾙに保存
		fputs($fp, $value);
	}
  flock($fp, LOCK_UN);

  fclose($fp);

	echo '書き込み成功';
}

function uniq(){

}

/*--------------------------
END | =wiki
--------------------------*/
?>