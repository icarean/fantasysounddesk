<!DOCTYPE html>
<html lang="en-UK">
<meta charset="UTF-8">
<head>

<title>Sound desk sound sources</title>

<!-- Serah Allison 2023 -->

<style>
body {
  background-color: grey;
  text-color: white;
}
p span {
  background-color: whitesmoke;
}
h1 {
  text-align: center;
  font-size: 3em;
  line-height: 0.4em;
  white-space: nowrap;
}
h1 span {
  background-color: whitesmoke;
}
small {
  font-size: 0.6em;
}
</style>



<html>
<body>

<h1><span>Sound file sources</span></h1>

<?php
$credits = nl2br(file_get_contents('sounds/soundfilesources.txt'));
echo ($credits);
?>

<!-- Footer -->
<div id="footer"><small>
  <br /><br />
  <p><span>Sound desk browser fun. Last updated 30/07/2023. SA<span></p>
  <p><span><a href="index.php">Return to the sound desk</a></span></p>
</small></div>

</body>
</html>

