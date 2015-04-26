<?php
$categories=explode(", ","CEO, Director, Vice President, Trader, Manager, Employee");
$stopwords = file_get_contents("stopwordslsit.txt");
$stopwords = implode("|",explode("\n",trim($stopwords)));
foreach($categories as $category){
  $category=urlencode($category);
  $cmd = "cat tmp_output/${category}_*word_count.txt |grep -iEv '(${stopwords})' | awk '{ count[$2] += $1 } END { for(elem in count) print count[elem], elem }' | sort -r -n -k1,1 |head -n 500 >> tmp_output/${category}_word_count_aggregate_filtered.txt";
  shell_exec($cmd);
}
