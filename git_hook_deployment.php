<?php
// Payload comes from github
if(isset($_POST['payload'])){
  // Sleep for 15 sec just for safety
  sleep(5);
  /* I have given the apache user on the server an ssh key pair for git
   * The apache user owns the .git directory in /ivorys1/public_html/ and 
   * has read/write permission on the public_html folder
   * the apache user should be able to pull from git!
   */
  function sendMessage($output){
    $message = "WesCMS.com Deployment Report
            Date:".date("m/d/Y h:i:s A")."
            --------------------------------------------------
            ".$output."";
        $message = wordwrap($message, 100, "\r\n");
    @mail('karl@webksd.com', 'WesCMS.com Deployment Report '.date("m/d/Y h:i:s A").'', $message);
    @mail("6302175813@txt.att.net",$subject,$message,"From: deployment@wescms.com");
  }
  $return = shell_exec('git pull origin master 2>&1');
  echo "<pre>$return</pre>";
  sendMessage($return);
}
?>
