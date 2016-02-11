<?php 
session_start();
$user=$_SESSION['tek_emailid'];
 ?>

<html>
<script>
window.onload = function() {
  document.getElementById("LearnMoreBtn").onclick = function(){
        var overlay = document.getElementById("overlay");
        var popup = document.getElementById("popup");
        overlay.style.display = "block";
        popup.style.display = "block";
    };
    
  document.getElementById("CloseBtn").onclick = function(){
        var overlay = document.getElementById("overlay");
        var popup = document.getElementById("popup");
        overlay.style.display = "none";
        popup.style.display = "none";      
  }
};

</script>
<link href="Rulesstyle.css" type="text/css" rel="stylesheet">
<body>
<div class="links">
			 <h1><a href="#level_2" alt="next level" id="LearnMoreBtn"> <b>RULES</b> </a></h1> 
			 
			</div>
			<div id="bg">
    Round Two. Fight!<br><hr width=55%>  Read these rules.<hr width=30%> 
</div>
<div id="overlay"></div>
<div id="cont">
<div id="popup">
    RULES:<br><br>
	[1] The clock starts ticking when you start moving<br>
	[2] You must be in the vault at the end of time. Else, its Game Over<br>
	[3] The gas mask is essential to survive in the post-apocalyptic world. Do not forget to get it.<br>
	[4] You can carry only a single resource at a time. Plan accordingly <br>
	[5] Darkness is not your ally 
    <h1><a href="../level2.php" alt="next level" id="CloseBtn">Got It !</a></h1>  
</div>
 
</div>

</body>
</html>