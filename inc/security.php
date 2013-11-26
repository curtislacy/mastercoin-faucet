<?php

// Generates a random 64 long char id
function generateUid() {
  $uid = uniqid(md5(mt_rand()), true);
  $formid = hash("sha256", $uid);
  return $formid;
}

// Returns true, if input is a valid uid
function isUid($input)
{
  $pattern = "/^[a-zA-Z0-9]{64}$/";
  return preg_match($pattern, $input);
}

// Returns true, if input is a valid address
function isAddress($input)
{
  $pattern = "/^[a-zA-Z0-9]{33,34}$/";
  return preg_match($pattern, $input);
}

// Sets referrer session
function registerReferrer($referrer)
{
  $_SESSION["referrer"] = $referrer;
}

// Sets uid session
function registerUid($uid)
{
  $_SESSION["state"] = $uid;
}

// Deletes referrer session
function unregisterReferrer()
{
  if(isset($_SESSION["referrer"]))
  {
    unset($_SESSION["referrer"]);
  }
}

// Deletes uid session
function unregisterUid()
{
  if(isset($_SESSION["state"]))
  {
    unset($_SESSION["state"]);
  }
}

// Returns true, if address is valid
function hasValidAddress()
{
  return isset($_POST["address"]) && isAddress($_POST["address"]);
}

// Returns true, if referrer is valid
function hasValidReferrer($referrer)
{
  $isValid =
    isset($_SESSION["referrer"]) && ($_SESSION["referrer"] == $referrer);
    
  return $isValid;
}

// Returns true, if session is valid
function hasValidUid()
{
  $validState =
    isset($_GET["state"]) && isUid($_GET["state"]);
    
  $validSession =
    isset($_SESSION["state"]) && isUid($_SESSION["state"]);
  
  if($validState && $validSession)
  {
    return $_SESSION["state"] == $_GET["state"];
  }
  else
  {
    return false;
  }
}

// Returns true, if session is valid for POST
function hasValidPostUid()
{
  $validState =
    isset($_POST["state"]) && isUid($_POST["state"]);
    
  $validSession =
    isset($_SESSION["state"]) && isUid($_SESSION["state"]);
    
  if($validState && $validSession)
  {
    return $_SESSION["state"] == $_POST["state"];
  }
  else
  {
    return false;
  }
}

// Returns true, if Bitcointalk profil link and signature were provided
function hasValidBitcointalkData()
{
  $isValid =
    isset($_POST["profil"]) && isset($_POST["signature"]);
    
  return $isValid;
}

// Returns true, if referrer and session id is valid for POST
function isValidPostSession($referrer)
{
  return hasValidPostUid() && hasValidReferrer($referrer);
}

// Returns true, if referrer and session id is valid for POST
function isValidPostRequest($referrer)
{
  switch($referrer)
  {
    case "bitcointalk":    
      return hasValidBitcointalkData();
  }
  
  return false;
}

?>