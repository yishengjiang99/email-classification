<?php
$trainingSetSize=100; //reading first 100 emails
$filterCmd="grep -Ev '(Message-ID:|Date:|From:|To:|Subject:|Mime-Version:|Content-Type:|Content-Transfer-Encoding:|X-To:)'"; //filters out common email features
$countingCmd="tr [:space:] '\n' |sort | uniq -c |sort -r";
$file_handle =fopen("category_employee_folders.txt",'r');
while($l=fgets($file_handle)){
 $lineInfo=explode(",",$l);
 $category =$lineInfo[0];
 if(!$lineInfo[1]) continue;
 $folderName=trim($lineInfo[1]);
 $outputFile=$category."_".$folderName."_word_count.txt";
 $cmd = "ls enron_mail_20110402/maildir/$folderName/* | xargs -n 1000 -p 2 cat |$filterCmd|$countingCmd >> $outputFile";
 $ret= shell_exec($cmd);
}
