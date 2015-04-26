<?php
$fr=fopen('cache/category_employeeId.txt', 'r');
$testingSet=array();
while($l=fgets($fr)){
  $lt=explode(",",$l);
  if(!$lt[1]) continue;
  $filename=trim($lt[1]);
  $category=$lt[0];
  if(rand(0,1000)<=60) $testingSet[$filename]=$category;
}

$featureVector = json_decode(file_get_contents('cache/featureVectorInCategory.json'),1);
$countVector = json_decode(file_get_contents('cache/categoriesScaned.json'),1);
$n_fectureVector=array();
$categories=explode(", ","CEO, Director, Vice President, Trader, Manager, Employee");

foreach($categories as $category){
 $category = trim($category);
 foreach($featureVector as $word=>$wordInfo){
   $n_fectureVector[$word][$category]=array(0,0,0,0); //
   $file_scaned_incategory=0;
   $file_scaned_non_cata=0; 
   foreach($wordInfo as $wordCategory =>$count){
	if($wordCategory==$category) {
		$n_fectureVector[$word][$category][0]+=$count;
		$file_scaned_incategory+=$countVector[$category];
	}
	else {
		$n_fectureVector[$word][$category][1]+=$count;
		$file_scaned_non_cata+=$countVector[$wordCategory];
	}
  }
  $n_fectureVector[$word][$category][2] = (float)$n_fectureVector[$word][$category][0]/$file_scaned_incategory;
  $n_fectureVector[$word][$category][3] = (float)$n_fectureVector[$word][$category][1]/$file_scaned_non_cata;
 }
}
file_put_contents("cache/normalized_vector.json",json_encode($n_fectureVector));
var_dump($n_fectureVector);




