<?php
$fr=fopen('cache/category_employeeId.txt', 'r');
$testingSet=array();
while($l=fgets($fr)){
  $lt=explode(",",$l);
  if(!$lt[1]) continue;
  $filename=trim($lt[1]);
  $category=$lt[0];
  if(strpos($filename,"sent")===false) continue;
  if(rand(0,1000)<=60) $testingSet[$filename]=$category;
}

$categories=explode(", ","CEO, Director, Vice President, Trader, Manager, Employee, Other");

$featureVector = json_decode(file_get_contents('cache/feature_vectors.json'),1);

foreach($testingSet as $filename =>$actual_category){
 $scores = array();
 $highscore=0;  $prediction="";
 $fileContent=file_get_contents($filename);
 echo "\n";
 foreach($categories as $testingForThisCategory){
   $n=0;
   foreach($featureVector as $word=>$infoArray)
   {
	$p_hit=$infoArray[$testingForThisCategory];	
	if(!$p_hit) continue;
	$p_miss=average_percent_in_other($infoArray,$testingForThisCategory);
	$word_category_significance=(float)($p_hit+0.00001)/($p_hit+$p_miss+0.00001); //ghetto smoothing
	if(stripos($fileContent,$word)!==false){
		$log_score=log(1-$word_category_significance)-log($word_category_significance);	
		$n+=$log_score;
	}
    }
    $scores[$testingForThisCategory]=1/(1+exp($n));
    echo round($scores[$testingForThisCategory],5).",";   
    if($scores[$testingForThisCategory]>$highscore){
	$highscore=$scores[$testingForThisCategory];
	$prediction=$testingForThisCategory;
    }
 }
 $correct = $prediction==$actual_category ? "correct" : "incorrect";
 echo "$prediction, $actual_category, $correct";
}

function average_percent_in_other($categoryInfo,$theCategory){
  $total=0;$count=0;
  foreach($categoryInfo as $category=>$percent){
    if($theCategory!=$category){
	$total+=$percent;
	$count++;
    }
  }
  return (float)$total/$count;
}
exit;
