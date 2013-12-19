<html>
<head>
        <title>TEAM Scan Details</title>
<script >
function validateForm(f)
{
var numcbs = f.cb.length;
var i = 0;
while (numcbs > 0) {
	
	//if (f['cb[]'][i].checked)
	if (f.cb[i].checked)
      {
         f.checks[i].value = 'Y';
      } else 
      {
        f.checks[i].value = 'N';
      }
      numcbs--;
      i++;
}
}
</script>
<form name="lesions" action="check2.php" onsubmit="return validateForm(this)" method="post">
<input type="checkbox" name="cb" value="Y">Target<br>
<input type="hidden" name="checks[]" id="checks" value="N" />
<input type="checkbox" name="cb" value="Y">Online<br>
<input type="hidden" name="checks[]" id="checks" value="N" />
<input type="checkbox" name="cb" value="Y">Node<br>
<input type="hidden" name="checks[]" id="checks" value="N" />
<input type="checkbox" name="cb" value="Y">Delete<br>
<input type="hidden" name="checks[]" id="checks" value="N" />
<br>
<input type="checkbox" name="cb" value="Y">Target<br>
<input type="hidden" name="checks[]" id="checks" value="N" />
<input type="checkbox" name="cb" value="Y">Online<br>
<input type="hidden" name="checks[]" id="checks" value="N" />
<input type="checkbox" name="cb" value="Y">Node<br>
<input type="hidden" name="checks[]" id="checks" value="N" />
<input type="checkbox" name="cb" value="Y">Delete<br>
<input type="hidden" name="checks[]" id="checks" value="N" />
<br>
<input type="checkbox" name="cb" value="Y">Target<br>
<input type="hidden" name="checks[]" id="checks" value="N" />
<input type="checkbox" name="cb" value="Y">Online<br>
<input type="hidden" name="checks[]" id="checks" value="N" />
<input type="checkbox" name="cb" value="Y">Node<br>
<input type="hidden" name="checks[]" id="checks" value="N" />
<input type="checkbox" name="cb" value="Y">Delete<br>
<input type="hidden" name="checks[]" id="checks" value="N" />
<br>

<input type="submit" value="Submit">
</form> 
</html>