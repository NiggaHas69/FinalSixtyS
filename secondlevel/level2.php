
<html>
 <?php include 'commoncode.php';
 include 'connect.php';
session_start();
$username=$_SESSION['tek_emailid'];
$bits=explode("@",$username);
$username=$bits['0'];
 
    $sql="SELECT * FROM level2 WHERE tk_emailid='$username'";
    $query=mysql_query($sql) or die(mysql_error());
    $row=mysql_fetch_assoc($query);
    $played=$row['level_played'];
    if($played == 0)
 {   
    SendScore("sixty-seconds",125,$username);
    $sql1="UPDATE level2 SET level_played=1 WHERE tk_emailid='$username' ";
    $query1=mysql_query($sql1)or die(mysql_error());
 }
     ?>
 <head>
    <meta charset="UTF-8" />
    <title>Sixty Seconds</title>
    <script src="assets/js/jquery.min.js"></script>
     <script src="assets/js/player1.js"> </script>
  <script src="assets/js/pageflip.js"> </script>
  </head>

<style type="text/css">
body{
  background-image: url('assets/img/images.jpg');
  background-position: absolute;
  background-repeat: no-repeat;
  background-size: 100% 100%;
}

 #clock
         {
            text-align: center; 
            width: 100px;
            height: 40px;
            font-size: 2em;
            
            top:6%;
            position: fixed;
            right:2%;
            
            color:#00FF00;
            outline:2px black solid;
            z-index:300;
            opacity:0.6;
            background:black;

        }
        #fade{
    display: none;
    position: fixed;
    top: 0%;
    left: 0%;
    width: 100%;
    height: 100%;
    background-color: #000;
    z-index:1001;
    -moz-opacity: 0.7;
    opacity:.70;
    filter: alpha(opacity=70);
}
#light{
    display: none;
    position: absolute;
    top: 50%;
    left: 50%;
    width: 300px;
    height: 200px;
    margin-left: -150px;
    margin-top: -100px;                
    padding: 10px;
    border: 2px solid #FFF;
    background: #CCC;
    z-index:1002;
    overflow:none;
}
        </style>
 <body><div id="wallcontainer">
 <audio id="audio" loop> <source src="../audio/60_seconds.ogg" type='audio/ogg; '></audio>
  
  <audio id="collide"> <source src="../audio/hit.wav" type='audio/wav; '></audio>
  <audio id="collect"> <source src="../audio/collect.wav" type='audio/wav; '></audio>
 
 <div id="canvas-container"><canvas id="canvas" width="1250" height=" 750"></canvas></div>
  <span id="clock"></span>
 </div>
 <div id="light"> <center><span> End of Level Two.   </span> <button id="click" > Continue</button><center></div>

<div id="fade"></div>
<script type="text/javascript">

function lightbox_open(){
    window.scrollTo(0,0);
    document.getElementById('light').style.display='block';
    document.getElementById('fade').style.display='block'; 
}
function lightbox_close(){
    document.getElementById('light').style.display='none';
    document.getElementById('fade').style.display='none';
}
var audio=document.getElementById("audio");   //Walk
var collide=document.getElementById("collide");  //Wall
var collect=document.getElementById("collect");  

 var buster = new Sprite("assets/img/Buster0.png");
var flag = 0;
var ctx = document.getElementById('canvas').getContext('2d');
var dx = 12;
var dy = 12;
var xq= 60;
var vault =new Array();
var c = 0;        //for timer
var t;            //for timer
var timer_is_on = 0;
var section =null;
var x =150;
var y = 150;

var keyFunction=true;



var have =new Array();

var particles =[];
var count;  var l=490; var ini =0;    var collided=0;    var busterini=0;
var objects = new Array();
var resources =new Array();
var resource_count=0;
var rx =new Array();
var ry =new Array();

var oxy =new Image();    var wat=new Image();    var fue=new Image();    var foo=new Image();
oxy.src ="assets/img/gas.png";    wat.src ="assets/img/water.png"; //Issue: Check extensions. Add all resources
fue.src="assets/img/fuel.png";    foo.src="assets/img/food.png";

for(var i=0;i<5;i++)
        {resources[i]= wat ;
         resources[i].id='wat';
        }
    for(var i=5;i<9;i++)
        {resources[i]= fue ;
         resources[i].id='fue';
        }
    for(var i=9;i<13;i++)
        {resources[i]= foo ;
         resources[i].id='foo';
        }
    
    resources[13] = oxy ;
    resources[13].id = 'oxy'

rx.push(1130);ry.push(40); //water
rx.push(400);ry.push(45);
rx.push(770);ry.push(375);
rx.push(392);ry.push(600);
rx.push(119);ry.push(465);

rx.push(40);ry.push(339); //feul
rx.push(771);ry.push(666);
rx.push(1148);ry.push(263);
rx.push(488);ry.push(654);

rx.push(209);ry.push(662); //food
rx.push(678);ry.push(474);
rx.push(62);ry.push(42);
rx.push(1033);ry.push(353);

rx.push(35);ry.push(570); //oxygen

  //Created this
        resource_count=resources.length;

var ini=0;

function inVault() {    //Cupboard in room 1, Section 1
if (x>556 && x<645)
{
if(y>28 && y<165 )
{return 1;}
}
else return 0;
}

function getsection(x,y){   //Returns Section number
if (x <=612 )
     section =1;
else  if (x > 612  )
    section =2;
return section;
}
//Resource collision detection piece of stupid fuckin code starts here
            for(var i=0;i<resources.length;i++)
            {
            aBoxLeft=x-12;
            aBoxRight=x+12;
            aBoxTop=y-12;
            aBoxBottom=y+12;

            bBoxLeft=rx[i]-15;
            bBoxRight=rx[i]+15;
            bBoxTop=ry[i]-15;
            bBoxBottom=ry[i]+15;
           
            rezintersect( aBoxLeft,  aBoxRight,  aBoxTop,  aBoxBottom,
                           bBoxLeft,  bBoxRight,  bBoxTop,  bBoxBottom);
            if(collided)
            {
            remove(i);
            collided=false;
            }
            }
            function remove(i){
            if (have.length==0)   //if Player does not have resource then only he will grab it 
            {
            have.push(resources[i]);  
            resources.splice(i,1);
            rx.splice(i,1);
            ry.splice(i,1);
            resource_count --;
            }
            }


          
            function rezintersect( aBoxLeft,  aBoxRight,  aBoxTop,  aBoxBottom,
                                    bBoxLeft,  bBoxRight,  bBoxTop,  bBoxBottom) {

            if( bBoxRight >= aBoxLeft && bBoxBottom >= aBoxTop && aBoxRight >= bBoxLeft && aBoxBottom >= bBoxTop)
            collided = true;
            }

//var c=detect();

function keyInput(evt) {  

if(keyFunction){
  switch(evt.keyCode){
case 37: {flag=0;
          if(y>0 && y< 420)//sect1 left wall
          {
            if(x-dx < 17 && x>= 17)
              { x=24; flag=1; }

          }
           if(y>434 && y<550) //sink
           {
            if(x-dx < 90 && x >= 90)
              {x=90+dx; flag=1;}
           }
          
           if(y>610 && y<685) //gas
           {
            if(x-dx<192 && x>=192)
              {x=192+dx;flag=1; }
           }
          
           if(y>0 && y<209)
           {
            if(x-dx<464 && x>=464)
              { x=464+dx;flag=1;}
           }
           if(y>556 && y<609)
           {
            if(x-dx<17 && x>=17)
              { x=17+dx;flag=1;}
           }
           if(y>180 && y<226)
           {
            if(x-dx<586 && x>=586)
              { x=586+dx;flag=1;}
           }
           if(y>200 && y<265)
           {
            if(x-dx<464 && x>=464)
              { x=464+dx;flag=1;}
           }
           if(y>325 && y<420)
           {
            if(x-dx<362 && x>=362)
              { x=362+dx;flag=1;}
           }
           if(y>550 && y<608)
           {
            if(x-dx<696 && x>=696)
              {x=696+dx;flag=1;}
           }
           if(y>400 && y<444)
           {
            if(x-dx<382 && x>=382)
            {x=382+dx;flag=1;}
           }
           if(y>32 && y<234)
           {
            if(x-dx<370 && x>=370)
              {x=370+dx;flag=1;}
           }
           if(y>183 && y< 225)
           {
            if(x-dx<585 && x>=585)
            {
              x=585+dx;flag=1;
            }
           }

          if(y>415 && y<570)
           {
            if(x-dx<1082 && x>=1082)
              { x=1082+dx;flag=1;}
           }
           if(y>0 && y<158)
           {
            if(x-dx<756 && x>=756)
            {
            x=756+dx;flag=1;
            }
           }
           if(y>150 && y<200)
           {
            if(x-dx<756 && x>=756)
              { x=756+dx;flag=1;}
           }
           if(y>128 && y<176)
            {if(x-dx<812 && x>=812)
            {x=812+dx;flag=1;}
            }
            if(y>55 && y<205)
            {
              if(x-dx<1085 && x>=1085)
                {x=1085+dx;flag=1;}
            }
            if(y>290 && y<376)
            {
              if(x-dx<832 && x>=832)
               {x=832+dx;flag=1;}
            }
            if(y>385 && y<685)
            {
              if(x-dx<756 && x>=756)
                {x=756+dx;flag=1;}
            }
            if(y>532 && y<574)
            {
              if(x-dx<418 && x>=418){
                x=418+dx;flag=1;
              }
            }

            if(y>512 && y<635)
            {
              if(x-dx<379 && x>=379)
            {x=379+dx;flag=1;}
            }
           
            if(y>544 && y<606)
            {
              if(x-dx<700 && x>=700)
                {x=700+dx;flag=1;}
            }
           
            if(y>528 && y<621)
            {
              if(x-dx<671 && x>=671)
                {x=671+dx;flag=1;}
            }
           
            if(y>500 && y<657)
            {
              if(x-dx<640 && x>=640 )
              {
              x=640+dx;flag=1;
              }
            }
            if(flag==1)collide.play();
            if(flag==0)
            {
              x-=dx;buster.drawAnimated(x,y,[13,14,15,16,17,18],ctx);
            }

        }//case left
            
break;
case 38:{flag=0;
          if(x>12 && x<460)
          {
            if(y-dy<15 && y>=15)
            {
              y=15+dy; flag=1;
            }
          }
          if(x>455 && x<585)
          {
            if(y-dy<225 && y>=225)
            {
              y=225+dy; flag=1;
            }
          }
          if(x>422 && x<464)
          {
            if(y-dy<266 && y>=266)
            {
              y=266+dy; flag=1;
            }
          }
          if(x>452 && x<733)
          {
           if(y-dy<16 && y>=16)
            {y=16+dy; flag=1; }
          }
         
          if(x>714 && x<756)
          {
            if(y-dy<202 && y>=202)
              {y=202+dy; flag=1; }
          }
          if(x>755 && x<812)
          {
            if(y-dy<176 && y>=176)
              {y=176+dy; flag=1; }
          }
          if(x>870 && x< 1084)
          {
            if(y-dy<207 && y>=207)
              {y=207+dy; flag=1;}
          }
          if(x>754 && x<1200)
          {
            if(y-dy<16 && y>=16)
              {y=16+dy; flag=1; }
          }
          if(x>714 && x<1200)
          {
            if(y-dy<312 && y>=312)
              {y=312+dy; flag=1; ;}
          }
          if(x>790 && x<832)
          {
            if(y-dy<378 && y>=378)
              {y=378+dy; flag=1; }
          }
          if(x>858 && x<1084)
          {
            if(y-dy<572 && y>=572)
              {y=572+dy; flag=1; }
          }
          if(x>620 && x<740)
          {
            if(y-dy<451 && y>=451) 
            {
              y=451+dy; flag=1;
            }
          }
          if(x>497 && x<642)
          {
            if(y-dy<656 && y>=656)
          {y=656+dy; flag=1; }
          }
          if(x>476 && x<670)
          {
            if(y-dy<623 && y>=623)
              {y=623+dy; flag=1; }
          }
          if(x>432 && x<700)
          {
            if(y-dy <608 && y>=608)
              { y=608+dy; flag=1; }
          }
           if(x>365 && x<417)
           {
            if(y-dy<574 && y>=574)
              {y=574+dy; flag=1; }
           }
           if(x>0 && x<382)
           {
            if(y-dy <444 && y>=444)    
           {y=444+dy; flag=1;  }
           }
           if(x>15 && x<92)
           {
            if(y-dy<554 && y>=554)
              {y=554+dy; flag=1; }
           }
           if(x>30 && x<237)
           {
            if(y-dy<225 && y>=225)
              {y=225+dy; flag=1; }
           }
           if(x>230 && x<368)
           {
            if(y-dy<235 && y>=235)
              {y=235+dy; flag=1; }
           }
            if(flag==1)collide.play();
     
      if(flag==0)
      {
        y-=dy;
        buster.drawAnimated(x,y,[67,68,69,71],ctx);
      }
        }//case up
break;
case 39:
{ 
  flag=0;
         if(y>45 && y<220)
         {
          if(x+dy>35 && x<= 35)
            {x=35-dx;flag=1;}
         }
         if(y>12 && y<267)
         {
          if(x+dx>425 && x<=425)
          {
            x=425-dx;flag=1;
          }
         }

         if(y>12 && y<201)
         {
          if(x+dx>710 && x<=710)
          {
            x=710-dx;flag=1;
          }
         }

         
         if(y>50 && y<208)
         {
          if(x+dx>870 && x<=870)
          {
            x=870-dx;flag=1;
          }
         }
         if(y>308 && y<380)
         {if(x+dx>790 && x<=790)
          {
            x=790-dx;flag=1;
          }
         }
         if(y>264 && y<310)
         {if(x+dx>714 && x<=714)
          {
            x=714-dx;flag=1;
          }
         }
         if(y>10 && y<685)
         {
          if(x+dx>1160 && x<=1160)
          {
            x=1160;flag=1;
          }
         }
         if(y>418 && y<574)
         {
          if(x+dx>860 && x<=860)
          {
            x=860;flag=1;
          }
         }
         if(y>405 && y<450)
         {
          if(x+dx>620 && x<=620)
          {
            x=620-dx;flag=1;
          }
         }

         if(y>385 && y<686)
         {
          if(x+dx>713 && x<=713)
          {
            x=713-dx;flag=1;
          }
         }

         if(y>546 && y<608)
         {
          if(x+dx>432 && x<=432)
          {
            x=432-dx;flag=1;
          }
         }

         if(y>530 && y<623)
         {
          if(x+dx>478 && x<478)
          {
            x=478-dx;flag=1;
          }
         }
         if(y>500 && y<657)
         {
          if(x+dx>500 && x<=500)
          {
            x=500-dx;flag=1;
          }
         }
         if(y>510 && y<685)
         {
          if(x+dx>335 && x<=335)
          {
            x=335-dx;flag=1;320
          }
         }
         if(y>328 && y<425)
         {
          if(x+dx>320 && x<=320)
          {
            x=320-dx;flag=1;
          }
         }
            if(flag==1)collide.play();
         if(flag==0)
         {
          x+=dx;buster.drawAnimated(x,y,[7,8,9,10,11,12],ctx);
         }



}//case right
break;
case 40:{flag=0;
          if(x>13 && x<382)
          {
            if(y+dy>397 && y<=397 )
              {y=397-dy;flag=1;}
          }
          if(x>32 && x<252)
          {
            if(y+dy>35 && y<=35 )
              {y=35-dy;flag=1;}
          }
          if(x>235 && x<370)
          {
            if(y+dy>25 && y<=25 )
              {y=25-dy;flag=1;}
          }
          if(x>320 && x<360)
          {
            if(y+dy>325 && y<=325 )
              {y=325;flag=1;}
          }
          if(x>460 && x<584)
          {
            if(y+dy>175 && y<=175 )
              {y=175-dy;flag=1;}
          }
          if(x>752 && x<811)
          {
            if(y+dy>130 && y<=130 )
              {y=130-dy;flag=1;}
          }
          if(x>868 && x<1084)
          {
            if(y+dy>50 && y<=50 )
              {y=50-dy;flag=1;}
          }
          if(x>714 && x < 1200)
          {
            if(y+dy>265 && y<=265 )
              {y=265-dy;flag=1;}
          }
          if(x>858 && x<1084)
          {
            if(y+dy>436 && y<=436 )
              {y=436-dy;flag=1;}
          }
          if(x>714 && x<756)
          {
            if(y+dy>384 && y<=384 )
              {y=384-dy;flag=1;}
          }
          if(x>620 && x<742)
          {
            if(y+dy>408 && y<408 )
              {y=408-dy;flag=1;}
          }
         
          if(x>752 && x<1200)
          {
            if(y+dy>657 && y<=657 )
              {y=657-dy;flag=1;}
          }
          if(x>14 && x<195)
          {
            if(y+dy>580 && y<=580 )
              {y=580-dy;flag=1;}
          }
          if(x>336 && x<380)
          {
            if(y+dy>505 && y<=505 )
              {y=505-dy;flag=1;}
          }
          if(x>375 && x<420)
          {
            if(y+dy>525 && y<=525 )
              {y=525-dy;flag=1;}
          }
         if(x>374 && x<740)
          {
            if(y+dy>660 && y<=660 )
              {y=660-dy;flag=1;}
          }
         
          if(x>180 && x<365)
          {
            if(y+dy>660 && y<=660 )
              {y=660-dy;flag=1;}
          }
         
          if(x>432 && x<700)
          {
            if(y+dy>540 && y<=540 )
              {y=540-dy;flag=1;}
          }
          if(x>478 && x<670)
          {
            if(y+dy>524 && y<=524 )
              {y=524-dy;flag=1;}
          }
           if(x>500 && x<638)
          {
            if(y+dy>500 && y<=500 )
              {y=500-dy;flag=1;}
          }
          if(x>644 && x<735)
          {
            if(y+dy>430 && y<=430 )
              {y=425-dy;flag=1;}
          }
            if(flag==1)collide.play();
          if(flag==0){
            y+=dy;buster.drawAnimated(x,y,[79,80,81,82,83],ctx);
          }
         
}//case down

 }//switch end
 

 
 
 
 
if (ini==0)                                            //Start Timer on first move by player
{//document.getElementById("clock").value = 0;
startCount();}
ini=1;

for(var i=0;i<resources.length;i++)                         // Resource Intersection Part 1
{
aBoxLeft=x-8;
aBoxRight=x+8;
aBoxTop=y-8;
aBoxBottom=y+5;

bBoxLeft=rx[i]-15;
bBoxRight=rx[i]+25;
bBoxTop=ry[i]-5;
bBoxBottom=ry[i]+28;

function intersects( aBoxLeft,  aBoxRight,  aBoxTop,  aBoxBottom, bBoxLeft,  bBoxRight,  bBoxTop,  bBoxBottom) {
   if( bBoxRight >= aBoxLeft && bBoxBottom >= aBoxTop && aBoxRight >= bBoxLeft && aBoxBottom >= bBoxTop)
            collided = true;
}                                                         //End Intersects

function remove(i){
     if (have.length==1)                                //Player already has resource
      return 0;
      have.push(resources[i]); 
      resources.splice(i,1);
            rx.splice(i,1);
            ry.splice(i,1);
            resource_count --;
} //End Remove
 
intersects( aBoxLeft,  aBoxRight,  aBoxTop,  aBoxBottom,
                           bBoxLeft,  bBoxRight,  bBoxTop,  bBoxBottom);
if(collided)
{
   remove(i);
   collect.play();
   collided=false;
}
}                                                        //End for loop

            
 }//keyfunction ends
} //End keyInput


/*
38: Up
40: Down
37: Left
39: Right
*/

function timedCount() {
    //document.getElementById("txt").value = c;
    c = c + 1;
    if(c<12)
    t = setTimeout(function(){ timedCount() },2000);
  else stopCount();
}

function startCount() {
    if (!timer_is_on  ) {
        timer_is_on = 1;
        timedCount();
       
    }
}

function stopCount() {
    clearTimeout(t);
    
    timer_is_on = 0;
    keyFunction=false;
    calculate(vault);

lightbox_open();
    $( "#click" ).click(function() {
  window.location ="lastpagefinal/demo.php";
});
  window.location ="lastpagefinal/demo.php";

}


function init() {
          
        audio.load();
        collide.load();
        collect.load();
          function player(x, y ) {

            var floor =new Image();
        floor.src="assets/img/housel1.jpg";
       // ctx.drawImage(floor,0,0,1200,700);
             buster.drawAnimated(x, y, [4],ctx);
             }
  
          function draw() {
            //ctx.clearRect(0,0,1200,700);
                
            document.getElementById('clock').innerHTML=c;
            if (inVault() && have.length>=1)   //Player present in the vault and he has a resource
            
          {        

            if (have[0]==wat)
            { 
            ctx.drawImage(wat,600,50,80,80);
       
            vault.push(have[0]);
           
            have.splice(wat,1);
            
            }   
            
              if (have[0]==oxy)
            { 
            ctx.drawImage(oxy,600,50,80,80);
            vault.push(have[0]);
            have.splice(oxy,1);
            
             }
             
             if (have[0]==foo)
             {ctx.drawImage(foo,600,50,80,80);
                vault.push(have[0]);
             have.splice(foo,1); 
      
              }
             
             if (have[0]==fue)
             {ctx.drawImage(fue,600,50,80,80);
                vault.push(have[0]);
             have.splice(fue,1);
            
               }
             
             
             
            }
           
        
              for (var i =0 ;i< resource_count;i++)
              { 
            ctx.drawImage(resources[i],rx[i],ry[i],30,30);
          }

            if (inVault() && resource_count==0)
            {//document.getElementById("txt").value = "Your score:"+c;
            stopCount();}
           requestAnimationFrame(draw);
          }

          
          draw();
          //document.getElementById("txt").value = 0;

         player (200,200);
       
audio.play();
     
     }



     window.onload = init();
        window.addEventListener('keydown', keyInput, true);
      </script>
    </body>
      </html> 
