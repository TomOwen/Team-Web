<?php
?>
<html>
<body>
<head>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>

<script type="text/javascript" src="js/ddpowerjoomer.js">

/***********************************************
* Image Power Zoomer- (c) Dynamic Drive DHTML code library (www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit Dynamic Drive at http://www.dynamicdrive.com/ for this script and 100s more
***********************************************/

</script>
<script type="text/javascript">
jQuery(document).ready(function($){ //fire on DOM ready
 $('#myimage').addpowerzoom()
})
</script>
<?php

echo "<img id=\"myimage\" src=\"http://www.websoftmagic.com/teamdoc1000/104-102111-1.jpg\" style=\"width:300px; height:300px\" />";

?>
</tbody>
</body>
</html>