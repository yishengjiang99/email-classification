<?php
$featureVector = json_decode(file_get_contents('cache/featureVectorInCategory.json'),1);
$countVector = json_decode(file_get_contents('cache/categoriesScaned.json'),1);

$n_fectureVector=array();
$categories=explode(", ","CEO, Director, Vice President, Trader, Manager, Employee, Other");
foreach($categories as $category){
 $category =trim($category);
 foreach($featureVector as $word=>$wordInfo){
   $n_fectureVector[$word][$category]=array(0,0,0,0); //
   $file_scaned_in_category=$countVector[$category];
   $file_scaned_in_otherCategory=0;
   foreach($wordInfo as $wordCategory =>$count){
	if($wordCategory==$category) {
		$n_fectureVector[$word][$category][0]=$count;
	}
	else {
		$n_fectureVector[$word][$category][1]+=$count;
		$file_scaned_in_otherCategory+=$countVector[$category];
	}
  } 
  $n_fectureVector[$word][$category][2] = (float)$n_fectureVector[$word][$category][0]/$file_scaned_in_category;
  $n_fectureVector[$word][$category][3] = (float)$n_fectureVector[$word][$category][1]/$file_scaned_in_otherCategory;
 }
}
file_put_contents("cache/normalized_vector.json",json_encode($n_fectureVector));
var_dump($n_fectureVector);



