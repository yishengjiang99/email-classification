<?php

$categories=explode(", ","CEO, Director, Vice President, Trader, Manager, Employee");
$category_employ_map=array(); //maps from category name to employee name
$emailFilesForCategory = array(); //maps from category name to a list of mail files.
$file=file_get_contents("enronkey.csv");
$lines=explode("\n",$file);

foreach($lines as $line){
 $csv=explode(",",$line);
 $category=$csv[2];
 $employeeid=explode(".",$csv[0]);
 $folderName=$employeeid[1]."-".substr($employeeid[0],0,1); //the directory name for this employee
 if(!file_exists("enron_mail_20110402/maildir/$folderName")) continue;
 if(!in_array($category,$categories)) $category = "Other";
 if(!$category_employ_map[$category]) $category_employ_map[$category]=array();
 $output="\n$category,$folderName";
 file_put_contents("cache/category_employee_folders.txt",$output,FILE_APPEND);
 $category_employ_map[$category][]=$folderName; 	
}

foreach($category_employ_map as $category=>$folderNames){
  if(!$emailFilesForCategory[$category]) $emailFilesForCategory[$category]=array(); 
  foreach($folderNames as $folderName){
	$directory = "enron_mail_20110402/maildir/$folderName";
	$files=array();
	echo "\nsearching $directory";
	exec("find $directory -name *.",$files);
	foreach($files as $file){
		$line="\n$category, $file";
	        file_put_contents("cache/category_employeeId.txt",$line,FILE_APPEND);

	}
  }
 }
