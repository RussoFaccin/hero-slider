<?php
function returnMediaType($args){
    if (strchr($args,"image")) {
      return "IMAGE";
    } elseif (strchr($args,"video")) {
      return "VIDEO";
    }
}
?>
