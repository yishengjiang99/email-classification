<?php
$TRAINING_SET_PERCENT=4;
$categories=explode(",","CEO, Director, Vice President, Trader, Manager, Employee, Other");
$fr=fopen('cache/category_employeeId.txt', 'r');
$trainingSet = array();
$count=0;

while($l=fgets($fr)){
  $lt=explode(",",$l);
  if(!$lt[1]) continue;
  $filename=trim($lt[1]);
  $category=$lt[0]; 
  if(strpos($filename,"sent")===false) continue;
  if(rand(0,1000)<=$TRAINING_SET_PERCENT*10) $trainingSet[$filename]=$category;
}

$featureVector = array();
foreach($categories as $category){
  $category=urlencode(trim($category));
  $wordfile=explode("\n",file_get_contents("tmp_output/${category}_word_count_aggregate_filtered.txt"));
  foreach($wordfile as $line){
    $lt= explode(" ",$line);
    if(!$lt[1]) continue;
    $theWord = $lt[1];
    $theWord=preg_replace("/^\PL+|\PL\z/", "", $theWord);
    if(strlen($theWord)<3) continue;
    $featureVector[$theWord]=1;
  }
}

$word_count=array();
$file_count=array();
foreach($trainingSet as $filename=>$category){
    if(!$file_count[$category]) $file_count[$category]=0;
    $file_count[$category]++;
    echo "\nscanning $filename";
    $content=file_get_contents($filename);
    $files_in_category_containing_this_word=0;
    foreach($featureVector as $word=>$k){
	if(!$word_count[$word]) $word_count[$word]=array();
	if(!$word_count[$word][$category]) $word_count[$word][$category]=0;
	if(stripos($content,$word)!==false) $word_count[$word][$category]++;
    }   
}
$n_word_count=array();
foreach($word_count as $word=>$countArray){
  $n_word_count[$word]=array();
  foreach($countArray as $category=>$count){
    $n_word_count[$word][$category]=(float)$count/$file_count[$category];
  }   
}
echo json_encode($n_word_count);
file_put_contents("cache/feature_vectors.json",json_encode($n_word_count));
//var_dump($n_word_count);
exit;
/*
foreach($featureVector as $wordCategory =>$featureWords){
  $files_in_category_containing_this_word=0;
  $files_in_other_category_containing_this_word=0;
  $files_in_category_scaned=0; 
  $files_in_other_category_scaned=0;
  foreach($trainingSet as $filename=>$category){
	$content=file_get_contents($filename);
	foreach($featureWords as $word){
	  $files_in_category_containing_this_word=0;
	  $files_in_other_category_containing_this_word=0;
  	  $files_in_category_scaned=0;
          $files_in_other_category_scaned=0;
		
		$matched=stripos(content,$word)!==FALSE;
		
		if($matched && $category==$wordCategory) 
	}
	
	if($category==$wordCategory) $files_in_category_scaned++;
	else 			     $files_in_other_category_scaned++;	   
	
  }	 
}

foreach($trainingSet as $filename=>$category){
  if(!$categoriesScaned[$category]) $categoriesScaned[$category]=0;
  $categoriesScaned[$category]++;
  $content=file_get_contents($filename);
  foreach($featureVector as $wordCategory =>$featureWords){
        foreach($featureWords as $word){
		if(!$word) continue;
		if(!$wordCountInCategory[$word]) $wordCountInCategory[$word]=array();
		if(!$wordCountInCategory[$word][$wordCategory]) $wordCountInCategory[$word][$wordCategory]=0;
        	if(stripos($content,$word)!==false){
			$wordCountInCategory[$word][$wordCategory]++;
		}
        }
  }   
}

file_put_contents("cache/featureVectorInCategory.json",json_encode($wordCountInCategory));
file_put_contents("cache/categoriesScaned.json",json_encode($categoriesScaned));
*/
