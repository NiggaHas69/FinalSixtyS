<?php session_start();
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
    The fourth level. Same map, but an additional element.<br><hr width=55%> I'm gonna make you click on the Rules button to find out.<br><hr width=45%> Read them.<hr width=30%> 
</div>
<div id="overlay"></div>
<div id="cont">
<div id="popup">
    RULES:<br><br>
	[1] The clock starts ticking when you start moving<br>
    [2] Get the key and unlock the vault before you do anything<br>
	[2] You must be in the vault at the end of time. Else, its Game Over<br>
	[3] The Gas mask is essential to survive in the post apocalyptic world<br>
	[4] You can carry only a single resource at a time. Plan accordingly <br>
	[5] The portals help you move swiftly through the house.  
    <br>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp Blue: In  &nbsp&nbsp&nbsp&nbsp Red : Out
    <h1><a href="../level4.php" alt="next level" id="CloseBtn">Got It !</a></h1>  
</div>
 
</div>

</body>
</html>