	<html> <?php
include '../connect.php';
session_start();
//$user=$_SESSION['tk_emailid'];
$user=$_SESSION['tek_emailid'];
$bits=explode("@",$user);
$user=$bits['0'];
 

//$user='manish';
$sql="SELECT * FROM level1 WHERE tk_emailid ='$user'";
$result=mysql_query($sql) or die(mysql_error());
$row=mysql_fetch_assoc($result);


$days_lived = $row['tk_no_of_days_lived'];
//session variable

$level_score = $row['tk_no_of_days_lived'];
$water=$row['tk_water'];
$food=$row['tk_food'];
$fuel=$row['tk_fuel'];
$dayoffood =$row['tk_nofood'];
$dayofwater =$row['tk_nowater'];
$dayoffuel =$row['tk_nofuel'];


$sql1="SELECT * FROM main WHERE tk_emailid ='$user'";
$query1=mysql_query($sql1) or die(mysql_error());
$row2=mysql_fetch_assoc($query1);

$cl=$row2['tk_current_level'];
if($cl==0)$megapoints=100;
if($cl==1)$megapoints=100;
if($cl==2)$megapoints=225;
if($cl==3)$megapoints=400;
if($cl==4)$megapoints=600;
if($cl==5)$megapoints=1000;
$cl++;
?>



    <head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link type="text/css" href="main.css" rel="stylesheet"  />
		<script type="text/javascript" src="../assets/js/pageflip.js">
	</script>
<script type="text/javascript" src="../assets/js/jquery.min.js">
	</script>
	<script >
	function update(){
 $.post("../../lp/setmegapoints.php",{username:"<?php echo $user; ?>",level:"<?php echo $cl; ?>",megapoints:"<?php echo $megapoints; ?>"},function(){});
	}
	</script>
	</head>

	<body >
		<audio id="bg" loop> <source src="../../audio/Fly.wav" type='audio/wav;'> </audio>

		<div id="justtext">
		To maintain your sanity in the time spent alone in the vault. You maintained a diary and recorded the occurences.
		Go through the diary by flipping the pages or read the gist below.
		</div>
			 
			
			
		<div id="book" >
			<canvas id="pageflip-canvas"></canvas>
			<div id="pages">
				<section><div><h2>DAY ONE<hr></h2><p id="one" align="left" >Missing the luxuries that we tend to get used to already. Long way ahead.</p></div></section>

				<section><div><h2>DAY TWO<hr></h2><p id="two"align="left"> Just thinking about the condition of the world outside gives me chills  </p> </div></section>

				<section><div><h2>DAY THREE<hr> </h2><p id="three"align="left">Third day. <br> Status: Alive<br> Hopefully the coming days do not change that  </p>	 </div></section>

				<section><div><h2>DAY FOUR<hr></h2><p id="four"align="left"> Another day in the vault. Time seems to be getting slower every day now. </p></div></section>

				<section><div><h2>DAY FIVE<hr></h2><p id="five"align="left">Starting to wonder whether I've grabbed enough food and water to last atleast a week</p></div></section>

				<section><div><h2>DAY SIX<hr></h2><p id="six"align="left">Am I the only one left on earth? Could I do something heroic. Time to chalk out a plan to save humanity.</p></div></section>

				<section><div><h2>DAY SEVEN<hr> </h2><p id="seven"align="left">Read back yesterday's entry.Lack of food is clearly affecting my mental state.</p></div></section>

				<section><div><h2>DAY EIGHT<hr></h2><p id="eight"align="left">Played with the empty food cans today to keep myself entertained.Should've grabbed my PS4 instead.</p></div></section>

				<section><div><h2>DAY NINE<hr></h2><p id="nine"align="left"align="left">Keep tuning into the radio to listen to any broadcasts. Nothing but static noise as of yet.</p></div></section>

				<section><div><h2>DAY TEN<hr></h2><p id="ten"align="left">Devised a system of my own to ration resources. Fourth grade math..Useful even in the apocalypse.</p></div></section>

				<section><div><h2>DAY ELEVEN<hr></h2><p id="eleven"align="left">When you start breathing in measured gasps, you begin to comprehend how much you took the earth for granted.</p></div></section>

				<section><div><h2>DAY TWELVE<hr></h2><p id="twelve"align="left">Losing track of time. Difficult to keep up when you havent seen the sun in so long.</p></div></section>

				<section><div><h2>DAY THIRTEEN<hr></h2><p id="thirteen"align="left"> Writing feels so strange.Wonder if the internet is still around. Could've tweeted all these.</p></div></section>

				<section><div><h2>DAY FOURTEEN<hr></h2><p id="fourteen"align="left">Two weeks in the vault today.Lack of company is starting to get to me.</p></div></section>

				<section><div><h2>DAY FIFTEEN<hr></h2><p id="fifteen"align="left">Health is deteriorating faster each day.Doubt I'll be able to hold on much longer.</p></div></section>

				<section><div><h2>DAY SIXTEEN<hr></h2><p id="sixteen"align="left">Tick...Tock...Tick...Tock...  </p></div></section>

				<section><div><h2>DAY SEVENTEEN<hr></h2><p id="seventeen"align="left"> Thought I heard a voice on the radio today. Nothing after that though. Might just have been my imagination.
				 </p></div></section>

				<section><div><h2>DAY EIGHTEEN<hr></h2><p id="eighteen"align="left"> There were tons of warnings on the media in the days leading to this. If only I'd paid heed to those.</p></div></section>

				<section><div><h2>DAY NINETEEN<hr></h2><p id="nineteen"align="left"> Still hoping that the military will break through the doors and come rescue me. This thought gives me the strength to survive these dark days. </p></div></section>

				<section><div><h2>DAY TWENTY<hr></h2><p id="twenty"align="left"> A bit of joy in a sea of grief. Its my birthday today if I've calculated right. Yay. Happy birthday to me. </p></div></section>

				<section><div><h2>DAY TWENTY ONE</h2><p id="21"align="left"> There's only so much canned soup that a man can have at a stretch.Skipped lunch today. Not in the best of health either right now. </p></div></section>
				
				<section><div><h2>DAY TWENTY TWO</h2><p id="22"align="left"> Randomly burst out into a song and dance routine today. Should do that more often. Maybe not. </p></div></section>
				<section><div><h2>DAY TWENTY THREE</h2><p id="23"align="left"> Spent most of the time staring at the ceiling today.. like almost all other days. </p></div></section>
				<section><div><h2>DAY TWENTY FOUR</h2><p id="24"align="left"> Would it be safe to venture out of the vault at this point of time? A fleeting thought </p></div></section>
				<section><div><h2>DAY TWENTY FIVE</h2><p id="25"align="left"> Not much ink left in this pen. Dont think I'll survive to see the last of it though.</p></div></section>
				<section><div><h2>DAY TWENTY SIX</h2><p id="26"align="left"> More canned food. Bottled water. Static on the radio. </p></div></section>
				<section><div><h2>DAY TWENTY SEVEN</h2><p id="27"align="left"> Batteries for the radio died out and with it my last contact with the outside world. </p></div></section>
				<section><div><h2>DAY TWENTY EIGHT</h2><p id="28"align="left"> Nope. I'll definitely outlive this pen. Small victories. </p></div></section>
				<section><div><h2>DAY TWENTY NINE</h2><p id="29"align="left"> Why am I writing these anyway? Not like people are going to actually sit and flip through pages, right? </p></div></section>
				<section><div><h2>DAY THIRTY</h2><p id="30"align="left"> Getting up is now the toughest part of the day. Every day in here is like a monday. </p></div></section>
				<section><div><h2>DAY THIRTY ONE</h2><p id="31"align="left"> New Month. What month was it when this happened anyway? Feels like ages ago. </p></div></section>
				<section><div><h2>DAY THIRTY TWO</h2><p id="32"align="left"> *The survivor ran out of ink today and the developer ran out of patience. Stop flipping. </p></div></section>
				<section><div><h2>DAY THIRTY THREE</h2><p id="33"align="left">  </p></div></section>
				<section><div><h2>DAY THIRTY FOUR</h2><p id="34"align="left">  </p></div></section>
				<section><div><h2>DAY THIRTY FIVE</h2><p id="35"align="left">  </p></div></section>
				<section><div><h2>DAY THIRTY SIX</h2><p id="36"align="left">  </p></div></section>
				<section><div><h2>DAY THIRTY SEVEN</h2><p id="37"align="left">  </p></div></section>
				<section><div><h2>DAY THIRTY EIGHT</h2><p id="38"align="left">  </p></div></section>
				<section><div><h2>DAY THIRTY NINE</h2><p id="39"align="left">  </p></div></section>
				<section><div><h2> </h2><p   id="40"align="left">   </p></div></section>
			</div>
		</div>
       
		<div class="exampleoverlay">
			 
			<h2>  <font color="white" > Resources in vault </font>   </h2> <!--php use karke username lena h yaha -->
			 <font color="white" size="3px" face="Comic Sans MS"> <p> You managed to grab  
			<span id="foo"> <?php echo $food;?> </span>  Food   <span id="wat"> <?php echo $water;?> </span>  Water and <span id="fue"> <?php echo $fuel;?>  </span>  Fuel</p><br>
			 <h2>  <font color="white" > Outcome </font>   </h2>
			 
			<h4>   Food ran out on Day <span id="nofood"> <?php echo $dayoffood;?>  </span>  <br>
			<h4>   Last drops of water. Day <span id="nowater"><?php echo $dayofwater;?>  </span> <br>
			<h4>   and fuel on day  <span id="nofuel"> <?php echo $dayoffuel;?> </span>  

 
			<h3>   You survived <span id="achieved"><?php echo $days_lived;?>  </span> days  </h3> <!-- no .of days_lived-->
              
			</div>

			<div class="links">
			 <h1><a href="../../lp/lp.php" onclick="update()" alt="next level">Next Level</a></h1> 
			 <h1><a href="../level1.php" alt="next level">Retry</a></h1>  
			</div>
				 <script type="text/javascript">
				  var au=document.getElementById("bg").play();
				  
 		init();
        </script>
	
	
	</body>
<html>
