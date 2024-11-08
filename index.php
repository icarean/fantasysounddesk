<!DOCTYPE html>
<html lang="en-UK">
<meta charset="UTF-8">
<head>

<title>Fantasy sound desk</title>
<!-- Serah Allison 2024 v20241104.1120 -->
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
/* Keep a section on screen when everything else scrolls on */
.sticky {
  position: sticky;
  top: 0;
  background-color: gray;
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
  height: 3em;
  vertical-align: top;
  font-size: 1.5em;
  overflow: hidden;
  font-weight: normal;
  padding: 0;
  margin-top: 3px;
  margin-bottom: 3px;
  border: 1px solid lightgray;
  border-radius: 20px;
}
button.sound {
  width: 44%;
  margin-left: 4%;
  margin-right: 1%;
  background-color: WhiteSmoke;
}
button.stop {
  width: 4%;
  margin-left: 0;
  margin-right: 4%;
  background-color: WhiteSmoke;
  font-size: 3vw;
}
button.playing {
  background-color: DarkSeaGreen;
}
button.stopall {
  height: 1.5em;
  width: fit-content;
  background-color: IndianRed;
}
img.alertIcon {
  width: auto;
  height: 0.8em;
  padding-right: 0.5em;
}
input[type="range"] {
  width:90%;
  pointer-events: none;   // Click through
}
</style>



<html>
<body>

<!-- Page header -->
<div class="sticky">
<h1><span>Fantasy sound desk</span></h1>
<button class="stopall" onclick='stopAllAudio();' type='button'>STOP ALL</button>
</div>


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
      //echo "<audio id='audioContainer_".$thisFilename."' ontimeupdate='updateProgressBar($thisFilename);' onended='stopMp3(&#34;".$thisFilename."&#34;);'><source type='audio/mpeg' src='sounds/".$dir.'/'.$thisFilename."'></audio>\n";
      echo "<audio id='audioContainer_".$thisFilename."' ontimeupdate='updateProgressBar(&#34;".$thisFilename."&#34;);' onended='stopMp3(&#34;".$thisFilename."&#34;);'><source type='audio/mpeg' src='sounds/".$dir.'/'.$thisFilename."'></audio>\n";
      echo "<button id='soundbutton_".$thisFilename."' class='sound' onclick='togglePlaying(&#34;".$thisFilename."&#34;)' type='button'>".substr($thisFilename, 0, -4)."<br /><input id='progressBar_".$thisFilename."' type='range' value='0' min='0' max='100' step='1' disabled=True></input></button>\n";
    }
  }
}

// Buttons to links to external audio
// NEWNEWNEW - for some reason this doesn't work if there isn't a soundlinks/ folder or something
if (is_dir('soundlinks/')) {
  $linkfiles = array();
  $listOfFiles = new FilesystemIterator('soundlinks/');
  foreach ($listOfFiles as $fileInfo) {
    $thisFilename = $fileInfo->getFilename();
    $linkfiles[] = $fileInfo;
  }
  if (sizeof($linkfiles)) {
    echo "<h1 id='linksToWebAudio'><span> Links to web audio </span></h1>\n";
    // Put into ascending alphabetical order
    sort($linkfiles, SORT_STRING);
    // Create the audio containers and buttons
    foreach ($linkfiles as $linkfile) {
      $thisFilename = $linkfile->getFilename();
      echo "<button class='sound'><a href='".file_get_contents('soundlinks/'.$thisFilename)."' target='_blank' rel='noopener noreferrer'>".$thisFilename."</a></button>\n";
    }
  }
}

?>

<!-- Loop toggle -->
<h1><span> Settings </span></h1>
<table id="toggleSwitches" style="border-spacing: 0 5px;">
  <tr onClick="toggleLooping();">
    <td style="width:33%;text-align:right;">Play once </td>
    <td style="width:33%;border:1px solid black;"><div id="loopingSlider" style="width:1em;height:1em;-webkit-border-radius:0.5em;-moz-border-radius:0.5em;border-radius:0.5em;background:black;margin-left:0;margin-right:auto;"></div></td>
    <td style="width:33%;"> Loop all sounds</td>
  </tr>
</table>

<!-- Footer -->
<div id="footer"><small>
  <br /><br />
  <p><span>Sound desk browser tool. Last updated 26/09/2024 SA<span></p>
  <p><span><img src="images/256px-Alert_icon-72a7cf.png" class="alertIcon" />Webapps are constrained by browser and OS limitations. If the app doesn't seem to work well, try a different browser or device.</span></p>
  <?php
    if (file_exists('soundsources.php')) {
      echo "<p><span><a href='soundsources.php'>Audio file sources/credits</a></span></p>";
    }
  ?>
</small></div>

</body>
</html>



<script type="text/javascript">

var looping = 0;

// Toggle the playing / stopped state of the audioContainer
function togglePlaying(audioFileName) {
  if (document.getElementById("audioContainer_" + audioFileName).paused) {
    playMp3(audioFileName);
  } else {
    stopMp3(audioFileName);
  }
}

// Play a .mp3, given the name of the audio container
function playMp3(audioFileName) {
  document.getElementById("soundbutton_" + audioFileName).classList.add("playing");
  document.getElementById("audioContainer_" + audioFileName).currentTime = 0;
  document.getElementById("audioContainer_" + audioFileName).play();
}

function stopMp3(audioFileName) {
  document.getElementById("soundbutton_" + audioFileName).classList.remove("playing");
  document.getElementById("audioContainer_" + audioFileName).currentTime = 0;
  document.getElementById("audioContainer_" + audioFileName).pause();
}

// 
function stopAllAudio() {
  document.querySelectorAll('audio').forEach(el => {el.pause(); el.currentTime = 0;});
  document.querySelectorAll('button').forEach(el => {el.classList.remove("playing");});
}

// 
function toggleLooping() {
  if (looping==1) {
    looping=0;
    document.getElementById("loopingSlider").style.marginLeft="0";
    document.getElementById("loopingSlider").style.marginRight="auto";
  } else {
    looping=1;
    document.getElementById("loopingSlider").style.marginLeft="auto";
    document.getElementById("loopingSlider").style.marginRight="0";
  }
  document.querySelectorAll('audio').forEach(el => {el.loop = looping;});
}

//
function updateProgressBar(audioFileName) {
  audioContainer = document.getElementById("audioContainer_" + audioFileName);
  document.getElementById("progressBar_" + audioFileName).value = audioContainer.currentTime / audioContainer.duration * 100
}


</script>
