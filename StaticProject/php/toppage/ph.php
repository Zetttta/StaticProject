<?php
function h($str)
  {
      return htmlspecialchars($str, ENT_QUOTES, "UTF-8");
  }

  function ph($str)
  {
      print h($str);
  }
