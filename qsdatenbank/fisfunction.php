<? function dateDiff($dt1, $dt2, $split='yw') {
    $date1 = (strtotime($dt1) != -1) ? strtotime($dt1) : $dt1;
    $date2 = (strtotime($dt2) != -1) ? strtotime($dt2) : $dt2;
    $dtDiff = $date1 - $date2;
    $totalDays = intval($dtDiff/(24*60*60));
    $totalSecs = $dtDiff-($totalDays*24*60*60);
    $dif['h'] = $h = intval($totalSecs/(60*60));
    $dif['m'] = $m = intval(($totalSecs-($h*60*60))/60);
    $dif['s'] = $totalSecs-($h*60*60)-($m*60);
    // set up array as necessary
    switch($split) {
    case 'yw': # split years-weeks-days
      $dif['y'] = $y = intval($totalDays/365);
      $dif['w'] = $w = intval(($totalDays-($y*365))/7);
      $dif['d'] = $totalDays-($y*365)-($w*7);
      break;
    case 'y': # split years-days
      $dif['y'] = $y = intval($totalDays/365);
      $dif['d'] = $totalDays-($y*365);
      break;
    case 'w': # split weeks-days
      $dif['w'] = $w = intval($totalDays/7);
      $dif['d'] = $totalDays-($w*7);
      break;
    case 'd': # don't split -- total days
      $dif['d'] = $totalDays;
      break;
    default:
      die("Error in dateDiff(). Unrecognized \$split parameter. Valid values are 'yw', 'y', 'w', 'd'. Default is 'yw'.");
    }
    return $dif;
  }
?>