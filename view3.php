<?php

?>
<html>
<head>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>

<script type="text/javascript" src="js/imagepanner.js"></script>
</head>
<body>
<div id="imagediv1"> </div><div id="imagediv2"> </div>
<script>
image1 = new Image();
image1.src = "../teamdoc1000/100-030912-1.jpg";
image2 = new Image();
image2.src = "../teamdoc1000/100-030912-2.jpg";
var width1 = image1.width;
var height1 = image1.height;
var width2 = image2.width;
var height2 = image2.height;
alert ("image 1 = " + width1 + " x " + height1);
var div1 = document.getElementById('imagediv1');
var newdiv1 = "<div class=\"pancontainer\" data-orient=\"center\" data-canzoom=\"yes\" style=\"width:" + width1 + "px; height:" + height1 + "px;\"><img src=\"http://www.websoftmagic.com/teamdoc1000/100-030912-1.jpg\" /></div>";
div1.innerHTML = newdiv1;
</script>
</body>
</html>