<!DOCTYPE html>
<html lang="en-UK">
<meta charset="UTF-8">
<head>

<title>Sound desk</title>
<!-- Serah Allison 2023 -->
<!-- This code fights fascists -->


<!-- Prevent zoom in/out on Android -->
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />


<!-- Icon by Wikimedia Commons user Overtheborderline -->
<!-- https://commons.wikimedia.org/wiki/File:3D_heart.png -->
<link rel="icon" href="./favicon.ico" type="image/x-icon" />

<!-- Icon sizes and code from Greg Gant's "The 2020 Guide to FavIcons for Nearly Everyone and Every Browser":
     https://www.emergeinteractive.com/insights/detail/The-Essentials-of-FavIcons/ -->
<!-- generics -->
<link rel="icon" href="icons/hearticon-32.png" sizes="32x32">
<link rel="icon" href="icons/hearticon-57.png" sizes="57x57">
<link rel="icon" href="icons/hearticon-76.png" sizes="76x76">
<link rel="icon" href="icons/hearticon-96.png" sizes="96x96">
<link rel="icon" href="icons/hearticon-128.png" sizes="128x128">
<link rel="icon" href="icons/hearticon-192.png" sizes="192x192">
<link rel="icon" href="icons/hearticon-228.png" sizes="228x228">

<!-- Android -->
<link rel="shortcut icon" sizes="196x196" href=“icons/hearticon-196.png">

<!-- iOS -->
<link rel="apple-touch-icon" href="icons/hearticon-120.png" sizes="120x120">
<link rel="apple-touch-icon" href="icons/hearticon-152.png" sizes="152x152">
<link rel="apple-touch-icon" href="icons/hearticon-180.png" sizes="180x180">

<!-- Windows 8 IE 10-->
<meta name="msapplication-TileColor" content="#FFFFFF">
<meta name="msapplication-TileImage" content="icons/hearticon-144.png">

<!— Windows 8.1 + IE11 and above —>
<meta name="msapplication-config" content="icons/browserconfig.xml" />

<!-- Used for the accordion -->
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

</head>
</html>


<style>
:root {
  /* Prevent most zoom in/out on iOS */
  touch-action: pan-x pan-y;
  height: 100%

  /* Prevent the user from selecting text e.g. if they press-hold over text on a button */
  -webkit-touch-callout: none;  /* iOS Safari */
  -webkit-user-select: none;    /* Safari */
  user-select: none;
}
body {
  background-color: grey;
}
p span {
  background-color: whitesmoke;
}
h1 {
  padding-top: 1em;
  text-align: center;
  font-size: 3em;
  overflow: scroll;
  font-family: Arial, Helvetica, sans-serif;
  line-height: 1.2em;
  white-space: nowrap;
}	
/* Hide scrollbar for Chrome, Safari and Opera */
h1::-webkit-scrollbar {
  display: none;
}
/* Hide scrollbar for IE, Edge and Firefox */
h1 {
  -ms-overflow-style: none;  /* IE and Edge */
  scrollbar-width: none;  /* Firefox */
}
h1 span {
  background-color: whitesmoke;
}
small {
  font-size: 0.6em;
}
.alertText {
  color: red;
}
button {
  width: 45%;
  height: 3em;
  vertical-align: top;
  font-size: 1.5em;
  overflow: hidden;
  font-weight: normal;
  padding: 0;
  margin-left: 2%;
  margin-right: 2%;
  margin-top: 3px;
  margin-bottom: 3px;
  border: 1px solid lightgray;
  border-radius: 20px;
  background-color: offwhite;
}
img.alertIcon {
  width: auto;
  height: 0.8em;
  padding-right: 0.5em;
}
</style>



<html>
<body>

<h1><span>Sound desk</span></h1>
<br/>

<!-- Audio containers and play-audio buttons -->
<?php

// endswith function thanks to user mcrumley from https://stackoverflow.com/questions/619610/whats-the-most-efficient-test-of-whether-a-php-string-ends-with-another-string
function endswith($string, $test) {
    $strlen = strlen($string);
    $testlen = strlen($test);
    if ($testlen > $strlen) return false;
    return substr_compare($string, $test, $strlen - $testlen, $testlen) === 0;
}

// Get an array of all the subdirectories
$subDirs = array();
$listOfFilesAndDirs = new DirectoryIterator('sounds');
foreach ($listOfFilesAndDirs as $fileOrDirInfo) {
  if ($fileOrDirInfo->isDir() && !$fileOrDirInfo->isDot()) {
    $subDirs[] = $fileOrDirInfo->getFilename();
  }
}
$subDirs[] = '.';  // Include the sounds base directory at the end of the list
// Fill arrays of all the audio files in each subdirectory
foreach ($subDirs as $dir) {
  $listOfFiles = new FilesystemIterator('sounds/' . $dir);
  $audioFiles = array();
  foreach ($listOfFiles as $fileInfo) {
    $thisFilename = $fileInfo->getFilename();
    if (endswith($thisFilename, ".mp3") || endswith($thisFilename, ".wav") || endswith($thisFilename, ".ogg")) {
      $audioFiles[] = $fileInfo;
    }
  }
  if (sizeof($audioFiles)) {
    if (strcmp($dir, '.'))
      echo "<h1><span>" . $dir . "</span></h1>\n";
    else
      echo "<h1><span> Unsorted sounds </span></h1>\n";
    // Put into ascending alphabetical order
    sort($audioFiles, SORT_STRING);
    // Create the audio containers and buttons
    foreach ($audioFiles as $audioFile) {
      $thisFilename = $audioFile->getFilename();
      echo "<audio id='audioContainer_".$thisFilename."'><source src='sounds/".$dir.'/'.$thisFilename."' type='audio/mpeg'></audio>\n";
      echo "<button onclick='playMp3(&quot;audioContainer_".$thisFilename."&quot;)' type='button'>".substr($thisFilename, 0, -4)."</button>\n";
    }
  }
}

// Buttons to links to external audio
$linkfiles = array();
$listOfFiles = new FilesystemIterator('soundlinks/' . $dir);
foreach ($listOfFiles as $fileInfo) {
  $thisFilename = $fileInfo->getFilename();
  $linkfiles[] = $fileInfo;
}
if (sizeof($linkfiles)) {
  echo "<h1><span> Links to web audio </span></h1>\n";
  // Put into ascending alphabetical order
  sort($linkfiles, SORT_STRING);
  // Create the audio containers and buttons
  foreach ($linkfiles as $linkfile) {
    $thisFilename = $linkfile->getFilename();
    echo "<button><a href='".substr(file_get_contents('soundlinks/'.$thisFilename), 0, -1)."' target='_blank' rel='noopener noreferrer'>".$thisFilename."</a></button>\n";
  }
}


?>

<!-- Footer -->
<div id="footer"><small>
  <br /><br />
  <p><span>Sound desk browser fun. Last updated 30/10/2023 SA<span></p>
  <p><span><img src="images/256px-Alert_icon-72a7cf.png" class="alertIcon" />Webapps are constrained by browser and OS limitations. If the app doesn't seem to work well, try a different browser or device.</span></p>
  <p><span><a href="soundsources.php">Audio file sources/credits</a></span></p>
</small></div>

</body>
</html>



<script type="text/javascript">

// Playing a .mp3, given the name of the audio container
function playMp3(audioContainerID) {
  audioContainer = document.getElementById(audioContainerID);
  audioContainer.play(); 
} 


</script>

