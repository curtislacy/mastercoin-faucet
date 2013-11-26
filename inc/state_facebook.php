<?php

require_once("inc/config.php");
require_once("inc/security.php");  
require_once("inc/validator.php");
require_once("inc/FacebookConnector.php");
require_once("inc/SqlConnector.php");

$referrer = "facebook";
$connector = new FacebookConnector();
      
// Results: valid, alreadyclaimed, sessionerror, error
$result = "STATE_ERROR";      

// Session and state valid?
if($connector->validateSession())
{
  // Authentication successful?
  if($connector->authenticate())
  {
    $user = $connector->getUserDetails();
    
    // Request successful and user exists?
    if($user)
    {
      $name = $user["first_name"];
      $identifier = $user["id"];
      $fullname = $user["name"];
      
      $sql = new SqlConnector($sqlHost, $sqlUsername, $sqlPassword, $sqlDatabase);
      $reward = $sql->lookupReward($identifier, $referrer);
      
      // User already rewarded?
      if($reward == false)
      {
        // Last query successful?
        if($sql->wasSuccess())
        {
          $formid = generateUid();
          $registred = $sql->registerFormId($formid, $identifier, $referrer, $fullname);
          
          // Last query successful and claim registred?
          if($registred)
          {
            // Register new session id
            registerUid($formid);
            
            $result = "STATE_VALID";
          }
        }
      }
      else
      {
        $txtimestamp = date("F j, Y", strtotime($reward->timestamp));
        $txid = $reward->txid;
        
        $result = "STATE_ALREADY_CLAIMED";
      }
    }
  }
}
else
{
  $result = "STATE_SESSION_ERROR";
}

?>