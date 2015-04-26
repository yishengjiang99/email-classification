<?php
$TRAINING_SET_PERCENT=2;
$categories=explode(",","CEO, Director, Vice President, Trader, Manager, Employee");
$fr=fopen('cache/category_employeeId.txt', 'r');
$trainingSet = array();
$count=0;

while($l=fgets($fr)){
  $lt=explode(",",$l);
  if(!$lt[1]) continue;
  $filename=trim($lt[1]);
  $category=$lt[0];
  if(rand(0,1000)<=$TRAINING_SET_PERCENT*10) $trainingSet[$filename]=$category;
}

$featureVector = array();
foreach($categories as $category){
  $category=urlencode(trim($category));
  $featureVector[$category]=array();
  $wordfile=explode("\n",file_get_contents("tmp_output/${category}_word_count_aggregate_filtered.txt"));
  foreach($wordfile as $line){
    $lt= explode(" ",$line);
    if(!$lt[1]) continue;
    $theWord = $lt[1];
    $theword=preg_replace("/^\PL+|\PL\z/", "", $theWord);
    $featureVector[$category][]=$theWord;
  }
}
$wordCountInCategory=array();
$categoriesScaned=array();
$wordCountInNonCategory=array();
foreach($trainingSet as $filename=>$category){
  if(!$categoriesScaned[$category]) $categoriesScaned[$category]=0;
  $categoriesScaned[$category]++;
  $content=file_get_contents($filename);
  foreach($featureVector as $wordCategory =>$featureWords){
        foreach($featureWords as $word){
		if(!$wordCountInCategory[$word]) $wordCountInCategory[$word]=array();
		if(!$wordCountInCategory[$word][$category]) $wordCountInCategory[$word][$category]=0;
        	if(strpos($content,$word)!==false){
			$wordCountInCategory[$word][$category]++;
		}
        }
  }   
}

file_put_contents("cache/featureVectorInCategory.json",json_encode($wordCountInCategory));

file_put_contents("cache/categoriesScaned.json",json_encode($categoriesScaned));
exit;

