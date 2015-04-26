<?php
function getEmailContent($fileId){
   $cmd="cat enron_mail_20110402/maildir/$fileId/*/* |uniq "
      ."|grep -Ev '(Message-ID:|Date:|From:|To:|Subject:|Mime-Version:|Content-Type:|Content-Transfer-Encoding:|X-To:)'";
   exec($cmd,$output);
}

function getEmailContentWords($fileName, $training_set_size){
   $cmd="cat $filename ".
        "|grep -Ev '(Message-ID:|Date:|From:|To:|Subject:|Mime-Version:|Content-Type:|Content-Transfer-Encoding:|X-To:|Cc:|Bcc:)'|tr \" \" \"\n\" | sort | uniq -c |sort";
   exec($cmd,$output);
   return $output;
}





