


<html><?php include 'commoncode.php';
include 'connect.php';
session_start();
$username=$_SESSION['tek_emailid'];
$bits=explode("@",$username);
$username=$bits['0'];
 
    $sql="SELECT * FROM level5 WHERE tk_emailid='$username'";
    $query=mysql_query($sql) or die(mysql_error());
    $row=mysql_fetch_assoc($query);
    $played=$row['level_played'];
    if($played == 0)
 {   
    SendScore("sixty-seconds",400,$username);
    $sql1="UPDATE level5 SET level_played=1 WHERE tk_emailid='$username' ";
    $query1=mysql_query($sql1)or die(mysql_error());
 }
     ?>
 <head>
    <meta charset="UTF-8" />
    <title>Sixty Seconds</title>
     <script src="assets/js/player.js"> </script>
      <script src="assets/js/pageflip.js"> </script>
     
      <script src="assets/js/jquery.min.js"></script>
 
  </head>
<style type="text/css">
body{
  
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
 <body>

 <div id="wallcontainer">

 <audio id="audio" loop> <source src="../audio/60_seconds.ogg" type='audio/ogg; '></audio>
  
  <audio id="collide"> <source src="../audio/hit.wav" type='audio/wav; '></audio>
  <audio id="collect"> <source src="../audio/collect.wav" type='audio/wav; '></audio>
 <div id="canvas-container"><canvas id="canvas" width="1250" height=" 750"></canvas></div>
  <span id="clock"></span>
 </div>
 <div id="light"> <center><span> End of Level Five.   </span> <button id="click" > Continue</button><center></div>

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

var keycollected=false;
var r=null;
var audio=document.getElementById("audio");   //Walk
var collide=document.getElementById("collide");  //Wall
var collect=document.getElementById("collect");  //Resource
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
var x = 80;
var y = 50;
var bBoxRight;
var bBoxLeft;
var bBoxBottom;
var bBoxTop;
var aBoxRight;
var aBoxLeft;
var aBoxBottom;
var aBoxTop;
var boxx;
var boxy;
var c;
var keyFunction=true;
                   //Half of buster's height and width
var p= new Array();//ajax parameters

var have =new Array();
var count;  var l=490; var ini =0;    var collided=0;    var busterini=0;
var objects = new Array();
var resources =new Array();
var resource_count=0;
var rx =new Array();
var ry =new Array();

var oxy =new Image();   
var wat=new Image(); 
var fue=new Image();   
var foo=new Image();
var tor=new Image();
var port1=new Image();
var port2=new Image();
var key=new Image();

oxy.src ="assets/img/gas.png";   
wat.src ="assets/img/water.png";  
fue.src="assets/img/fuel.png";    
foo.src="assets/img/foo.png";  
tor.src="assets/img/tor.png";
port1.src="assets/img/portal1.png";
port2.src="assets/img/portal2.png";
key.src="assets/img/key.png";

for (var i=0;i<4;i++)    
 {resources[i]=wat; resources[i].id='wat';  } // 0 to 3 water
for (var i=4;i<10; i++)  
 {resources[i]=foo; resources[i].id='foo'; }// 4 to 9 food
for (var i=10;i<13; i++) 
 {resources[i]=fue; resources[i].id='fue'; }// 10 to 12 fuel

resources[13]=oxy; resources[i].id='oxy'; 

resources[14]=key; resources[i].id='key';

resources[15]=tor; resources[i].id='tor'; 
 
rx.push(654);rx.push(110);rx.push(496);rx.push(475);
ry.push(40);ry.push(440);ry.push(80);ry.push(647);

rx.push(1095);rx.push(1150);rx.push(400);rx.push(30);rx.push(615);rx.push(95);
ry.push(235);ry.push(525);ry.push(530);ry.push(565);ry.push(528);ry.push(315);

rx.push(138);rx.push(1000);rx.push(914);
ry.push(589);ry.push(531);ry.push(32);

rx.push(33);ry.push(164);
rx.push(710);ry.push(421);
rx.push(200);ry.push(200);   //torch 
        var have =new Array();    //Created this
        resource_count=resources.length;

      function in_portal()
      {
        if(x>334 && x<378)
          if(y>272 && y<322)
            {x=840;
            y=440;}
     }
	 
function inVault() {    //Cupboard in room 1, Section 1
if (x>1045 && x<1170)
{
if(y>350 && y<410 )
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

                                                        //End for loop

function intersects( aLeft,  aRight,  aTop,  aBottom, bLeft,  bRight,  bTop,  bBottom) 
{
  if( bRight >= aLeft && bBottom >= aTop && aRight >= bLeft && aBottom >= bTop)
            {
              collided = true;

          if((boxx)==710 && (boxy)==421 ) 
          { 
             r=1;
          }else
            r=0;
}            
return r;
}                                             //End Intersects

function remove(i){
     if (have.length==1)                                //Player already has resource 
      return 0;
      collect.play();        //Collect audio
      have.push(resources[i]);  
      resources.splice(i,1);
            rx.splice(i,1);
            ry.splice(i,1);
            resource_count --;
} //End Remove

 

function keyInput(evt) {

in_portal();
 if(keyFunction)
 {
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
boxx=rx[i];
boxy=ry[i];
  
 r=intersects( aBoxLeft,  aBoxRight,  aBoxTop,  aBoxBottom,
                           bBoxLeft,  bBoxRight,  bBoxTop,  bBoxBottom);
if(collided)
{ 
  if (r==1)
  {
  keycollected=true;
  remove(i);
  collided=false;
}
else if(r==0 && keycollected)
{
remove(i);
collided=false;
}
 collect.play();
}
} //for loop end
if (ini==0)                                            //Start Timer on first move by player
{//document.getElementById("clock").value = 0;
startCount();}
ini=1;
 
 

switch(evt.keyCode)
{ case 38: {
            flag=0;
            
            if(x> 14&& x< 1181)
                        {
                        if (y-dy<19 && y+6>=19 )
                        {y=dy+19 ; flag=1; }                             
                        }
            
            if(x> 18&& x<127 )
                        {
                        if (y-dy< 213&& y+6>= 213)
                        {y=dy+213 ; flag=1; }                             
                        }
                        
            if(x>191 && x< 397)
                        {
                        if (y-dy<194 && y+6>= 194)
                        {y=dy+ 194; flag=1; }                             
                        }           
            
            if(x> 79&& x< 326)
                        {
                        if (y-dy<418 && y+6>= 418)
                        {y=dy+418 ; flag=1; }                             
                        }
                        
            if(x>432 && x< 450)
                        {
                        if (y-dy<296 && y+6>=296 )
                        {y=dy+296 ; flag=1; }                             
                        }
                        
            if(x> 18&& x< 132)
                        {
                        if (y-dy<127 && y+6>= 127)
                        {y=dy+127 ; flag=1; }                             
                        }
                        
            if(x> 229&& x< 386)
                        {
                        if (y-dy< 89&& y+6>=89 )
                        {y=dy+89 ; flag=1; }                             
                        }
            
            if(x>18 && x< 80)
                        {
                        if (y-dy< 516&& y+6>= 516)
                        {y=dy+516 ; flag=1; }                             
                        }
            
            if(x>327 && x< 432)
                        {
                        if (y-dy<263 && y+6>=263 )
                        {y=dy+263 ; flag=1; }                             
                        }
            
            if(x>287 && x< 432)
                        {
                        if (y-dy<510 && y+6>= 510)
                        {y=dy+510; flag=1; }                             
                        }
            
            
            if(x>229 && x<275 )
                        {
                        if (y-dy< 645&& y+6>= 645)
                        {y=dy+ 645; flag=1; }                             
                        }
            
            if(x>367 && x< 405)
                        {
                        if (y-dy< 643&& y+6>= 643)
                        {y=dy+ 643; flag=1; }                             
                        }
            
            if(x> 492&& x< 631)
                        {
                        if (y-dy<68 && y+6>=68 )
                        {y=dy+68 ; flag=1; }                             
                        }
            
            if(x>568 && x< 784)
                        {
                        if (y-dy<330 && y+6>=330 )
                        {y=dy+330 ; flag=1; }                             
                        }
            
            if(x>572 && x< 693)
                        {
                        if (y-dy< 467&& y+6>= 467)
                        {y=dy+ 467; flag=1; }                             
                        }
            
            if(x> 277&& x< 366)
                        {
                        if (y-dy< 672&& y+6>= 672)
                        {y=dy+ 672; flag=1; }                             
                        }
            
            if(x> 438&& x< 504)
                        {
                        if (y-dy< 131&& y+6>= 131)
                        {y=dy+131 ; flag=1; }                             
                        }
            
            if(x>522 && x< 680)
                        {
                        if (y-dy< 193&& y+6>= 193)
                        {y=dy+193 ; flag=1; }                             
                        }
            
            if(x> 450&& x< 631)
                        {
                        if (y-dy< 418&& y+6>= 418)
                        {y=dy+ 418; flag=1; }                             
                        }
            
            if(x> 760&& x<883 )
                        {
                        if (y-dy<129 && y+6>=129 )
                        {y=dy+ 129; flag=1; }                             
                        }
            
            
            if(x> 881&& x< 968)
                        {
                        if (y-dy< 206&& y+6>=206 )
                        {y=dy+206 ; flag=1; }                             
                        }
            
            if(x> 798&& x< 880)
                        {
                        if (y-dy<230 && y+6>= 230)
                        {y=dy+230 ; flag=1; }                             
                        }
            
            if(x> 760&& x< 950)
                        {
                        if (y-dy< 404&& y+6>= 404)
                        {y=dy+ 404; flag=1; }                             
                        }
            
            if(x> 941&& x< 980)
                        {
                        if (y-dy< 577&& y+6>= 577)
                        {y=dy+577 ; flag=1; }                             
                        }
            
            if(x> 917&& x< 1181)
                        {
                        if (y-dy< 512&& y+6>=512 )
                        {y=dy+512 ; flag=1; }                             
                        }
            
            if(x>977 && x< 1181)
                        {
                        if (y-dy< 287&& y+6>= 287)
                        {y=dy+ 287; flag=1; }                             
                        }
            
            if(x> 1010&& x< 1168)
                        {
                        if (y-dy< 124&& y+6>= 124)
                        {y=dy+ 124; flag=1; }                             
                        }


                        if (flag==1)
                        collide.play();
                        if (flag == 0 ){
                        y -= dy;
                        buster.drawAnimated(x, y, [67,68,69,71],ctx);
                        }
        } break;                                //End case 38 sect 1
case 40:{flag=0;
          if(x>10 && x< 128) 
          {
            if(y+dy >172 && y<= 172)
              { y=172-dy; flag=1; }

          }
          if(x>10 && x< 74) 
          {
            if(y+dy >240 && y<= 240)
              { y=240-dy; flag=1; }

          }
          if(x>70 && x< 165) 
          {
            if(y+dy >345 && y<= 345)
              { y=345-dy; flag=1; }

          }
          if(x>150 && x< 280) 
          {
            if(y+dy >376 && y<= 376)
              { y=376-dy; flag=1; }

          }
          if(x>250 && x< 470) 
          {
            if(y+dy >222 && y<= 222)
              { y=222-dy; flag=1; }

          }
          if(x>220 && x< 363) 
          {
            if(y+dy >152 && y<= 152)
              {y=152-dy; flag=1; }

          }
          if(x>340 && x< 380) 
          {
            if(y+dy >124 && y<= 124)
              {y=124-dy; flag=1; }

          }
          if(x>380 && x< 400) 
          {
            if(y+dy >152 && y<= 152)
              {y=152-dy; flag=1; }

          }
          if(x>465 && x< 506) 
          {
            if(y+dy >86 && y<= 86)
              {y=86-dy; flag=1; }

          }
          if(x>520 && x< 680) 
          {
            if(y+dy >154 && y<= 154)
              {y=154-dy; flag=1; }

          }
          if(x>570 && x< 680) 
          {
            if(y+dy >252 && y<= 252)
              {y=252-dy; flag=1; }

          }
          if(x>692 && x< 786) 
          {
            if(y+dy >252 && y<= 252)
              {y=252-dy; flag=1; }

          }
          if(x>885 && x< 950) 
          {
            if(y+dy >162 && y<= 162)
              {y=162-dy; flag=1; }

          }
          if(x>842 && x< 904) 
          {
            if(y+dy >172 && y<= 172)
              {y=172-dy; flag=1; }

          }
          if(x>796 && x< 863) 
          {
            if(y+dy >190 && y<= 190)
              {y=190-dy; flag=1; }

          }
          if(x>975 && x< 1050) 
          {
            if(y+dy >246 && y<= 246)
              {y=246-dy; flag=1; }

          }
          if(x>1030 && x< 1068) 
          {
            if(y+dy >190 && y<= 190)
              {y=198-dy; flag=1; }

          }
          if(x>1065 && x< 1195) 
          {
            if(y+dy >246 && y<= 246)
              {y=246-dy; flag=1; }

          }
          if(x>915 && x< 1195) 
          {
            if(y+dy >472 && y<= 472)
              {y=472-dy; flag=1; }

          }
          if(x>738 && x< 916) 
          {
            if(y+dy >362 && y<= 362)
              {y=362-dy; flag=1; }

          }
          if(x>896 && x< 936) 
          {
            if(y+dy >334 && y<= 334)
              {y=334-dy; flag=1; }

          }
          if(x>936 && x< 950) 
          {
            if(y+dy >362 && y<= 362)
              {y=362-dy; flag=1; }

          }
          if(x>410 && x< 630) 
          {
            if(y+dy >378 && y<= 378)
              {y=378-dy; flag=1; }

          }
          if(x>600 && x< 694) 
          {
            if(y+dy >427 && y<= 427)
              {y=427-dy; flag=1; }

          }
          if(x>288 && x< 368) 
          {
            if(y+dy >470 && y<= 470)
              {y=470-dy; flag=1; }
          }
          if(x>347 && x< 385) 
          {
            if(y+dy >440 && y<= 440)
              {y=440-dy; flag=1; }

          }
          if(x>384 && x< 436) 
          {
            if(y+dy >470 && y<= 470)
              {y=470-dy; flag=1; }

          }
          if(x>172 && x< 212) 
          {
            if(y+dy >508 && y<= 508)
              {y=508-dy; flag=1; }

          }
          if(x>208 && x< 680) 
          {
            if(y+dy >660 && y<= 660)
              {y=660-dy; flag=1; }

          }

          if(x>150 && x< 195) 
          {
            if(y+dy >660 && y<= 660)
              {y=660-dy; flag=1; }

          }
          if(x>12 && x< 155) 
          {
            if(y+dy >600 && y<= 600)
              {y=600-dy; flag=1; }

          }
          if(x>1010 && x< 1195) 
          {
            if(y+dy >560 && y<= 560)
              {y=560-dy; flag=1; }

          }
          if(x>850 && x< 1195) 
          {
            if(y+dy >660 && y<= 660)
              {y=660-dy; flag=1; }

          }
          if(x>758 && x< 854) 
          {
            if(y+dy >494 && y<= 494)
              {y=494-dy; flag=1; }

          }

          if(x>440 && x< 520) 
          {
            if(y+dy >660 && y<= 660)
              {y=660-dy; flag=1; }

          }
          if(x>484 && x< 648) 
          {
            if(y+dy >535 && y<= 535)
              {y=535-dy; flag=1; }

          }
          if(x>628 && x< 734) 
          {
            if(y+dy >522 && y<= 522)
              {y=522-dy; flag=1; }

          }
          if(x>728 && x< 788) 
          {
            if(y+dy >660 && y<= 660)
              {y=660-dy; flag=1; }

          }
          if(x>206 && x< 410) 
          {
            if(y+dy >588 && y<= 588)
              {y=588-dy; flag=1; }

          }
          if(x>240 && x< 386) 
          {
            if(y+dy >576 && y<= 576)
              {y=576-dy; flag=1; }

          }
          if(x>258 && x< 365) 
          {
            if(y+dy >275 && y<= 275)
              {y=275-dy; flag=1; }

          }
                        if (flag==1)
                       collide.play();
                        if (flag==0 ){
                        y += dy;
                        buster.drawAnimated(x, y, [79,80,81,82,83],ctx);
                        }
        }break;                                //End case 40 sect 1

case 37: {flag=0;
          if(y>12 && y< 681) 
          {
            if(x-dx <19 && x>= 19)
              { x=19 +dx; flag=1; }

          }
          
          
           if(y>13 && y<127)  
           {
            if(x-dx <132 && x >= 132)
              {x=132+dx; flag=1;}
           }
           
           
           if(y>174&& y<213) //gas
           {
            if(x-dx<127&& x>=127)
              {x=127+dx;flag=1;  }
           }
           
           
           if(y>243 && y<348)
           {
            if(x-dx<71 && x>=71)
              { x=71+dx;flag=1;}
           }
           
           
           if(y>328 && y<400)
           {
            if(x-dx<160 && x>=160)
              { x=160+dx;flag=1;}
           }
           
           if(y>14 && y<179)
           {
            if(x-dx<229 && x>=229)
              { x=229+dx;flag=1;}
           }
           
           
           
           
           if(y>17 && y<90)
           {
            if(x-dx<387 && x>=387)
              { x=387+dx;flag=1;}
           }
            
           if(y>126 && y<196)
           {
            if(x-dx<385 && x>=385)
              { x=385+dx;flag=1;}
           }
           
           
           
           
           
           
           if(y>48 && y<109)
           {
            if(x-dx<477 && x>=477)
              {x=477+dx;flag=1;}
           }
            
           if(y>88 && y<127)
           {
            if(x-dx<503 && x>=503)
            {x=503+dx;flag=1;}
           }
           
           if(y>19&& y<69)
           {
            if(x-dx<632 && x>=632)
              {x=632+dx;flag=1;}
           }
           if(y>20 && y< 276)
           {
            if(x-dx<700 && x>=700)
            {
              x=700+dx;flag=1;
            }
           }

          if(y>256 && y<331)
           {
            if(x-dx<786 && x>=786)
              { x=786+dx;flag=1;}
           }
           
           
           
           if(y>23 && y<130)
           {
            if(x-dx<885 && x>=885)
            {
            x=885+dx;flag=1;
            }
           }
           
           
           
           
           
           if(y>18 && y<204)
           {
            if(x-dx<969 && x>=969)
              { x=969+dx;flag=1;}
           }  
           if (y>194  && y< 212 )
           { if(x-dx< 927 && x>=927  )
           {x=dx+ 927 ;flag=1;  }
           
           }
           
            if (y>210  && y<233  )
           { if(x-dx< 881 && x>= 881 )
           {x=dx+ 881 ;flag=1;  }
           
           }
           
            if (y> 265 && y< 419 )
           { if(x-dx<328  && x>=328 )
           {x=dx+ 328 ;flag=1;  }
           
           }
           
            if (y> 222 && y< 262 )
           { if(x-dx< 468 && x>= 468 )
           {x=dx+ 456 ;flag=1;  }
           
           }
            if (y> 247 && y< 297 )
           { if(x-dx< 455 && x>= 455 )
           {x=dx+ 455 ;flag=1;  }
           
           }
           
            if (y> 200 && y< 271 )
           { if(x-dx< 1069  && x>= 1069 )
           {x=dx+ 1069 ;flag=1;  }
           
           }
           
            if (y> 422 && y<  516)
           { if(x-dx< 81 && x>=  81)
           {x=dx+  81;flag=1;  }
           
           }
           
            if (y> 602 && y< 683 )
           { if(x-dx< 157 && x>= 157 )
           {x=dx+ 157 ;flag=1;  }
           
           }
           
            if (y> 510 && y< 682 )
           { if(x-dx< 211 && x>= 211 )
           {x=dx+ 211 ;flag=1;  }
           
           } 
            if (y> 577 && y< 636 )
           { if(x-dx< 406 && x>= 406 )
           {x=dx+ 406 ;flag=1;  }
           
           }
           
            if (y> 557 && y< 674 )
           { if(x-dx< 366 && x>= 366 )
           {x=dx+ 366 ;flag=1;  }
           
           }
           
            if (y> 442 && y< 495 )
           { if(x-dx< 387 && x>= 387 )
           {x=dx+ 387 ;flag=1;  }
           
           }
           
            if (y> 419 && y< 683 )
           { if(x-dx< 451 && x>= 451 )
           {x=dx+ 451 ;flag=1;  }
           
           }
           
            if (y>380  && y< 419 )
           { if(x-dx< 631 && x>= 631 )
           {x=dx+ 631 ;flag=1;  }
           
           }
           
            if (y> 418 && y< 449 )
           { if(x-dx<611  && x>= 611 )
           {x=dx+611  ;flag=1;  }
           
           }
           
           if (y> 428 && y< 469 )
           { if(x-dx< 694 && x>= 694 )
           {x=dx+ 694 ;flag=1;  }
           
           }
           
           if (y> 526 && y< 683 )
           { if(x-dx< 733 && x>= 733 )
           {x=dx+ 733 ;flag=1;  }
           
           }
           
           if (y> 497 && y< 682 )
           { if(x-dx< 854 && x>= 854 )
           {x=dx+ 854 ;flag=1;  }
           
           }
           
           if (y> 405 && y< 515 )
           { if(x-dx< 797 && x>= 797 )
           {x=dx+ 797 ;flag=1;  }
           
           }
           
           if (y> 334 && y< 386 )
           { if(x-dx< 937 && x>= 937 )
           {x=dx+ 937 ;flag=1;  }
           
           }
           if (y> 363 && y< 404 )
           { if(x-dx< 951 && x>= 951 )
           {x=dx+ 951 ;flag=1;  }
           
           }
           if (y> 512 && y< 580 )
           { if(x-dx< 980 && x>= 980 )
           {x=dx+ 980 ;flag=1;  }
           
           }
                      if (flag==1)
                        collide.play();
                        if (flag == 0) {
                        x -= dx;
                        buster.drawAnimated(x, y, [13,14,15,16,17,18],ctx);
                        }
        } break;                                //End case 37 sect 1
  case 39: {    flag=0;
    
    if(y>10 && y< 195 )
                {
                if (x+dx>175 && x<=175 ) 
                {x=175-dx; flag=1; } 
                }
             
    if(y>126  && y<180 )
                {
                if (x+dx>340  && x<= 340)
                {x= 340-dx; flag=1; } 
                }
                
                    if(y>10 && y< 135)
                {
                if (x+dx>437 && x<=437 )
                {x= 437-dx; flag=1; } 
                }
             
    if(y>10  && y<175 )
                {
                if (x+dx> 660 && x<= 660)
                {x= 660 -dx; flag=1; } 
                }
                
                    if(y> 155 && y< 194)
                {
                if (x+dx>522 && x<= 522)
                {x= 522-dx; flag=1; } 
                }
             
    if(y>194  && y<272 )
                {
                if (x+dx>660  && x<= 660)
                {x=660 -dx; flag=1; } 
                }
                
                    if(y>252 && y< 328)
                {
                if (x+dx>568 && x<=568 )
                {x= 568-dx; flag=1; } 
                }
             
    if(y>10  && y<130 )
                {
                if (x+dx>760  && x<=760 )
                {x= 760-dx; flag=1; } 
                }
                
                    if(y>10 && y< 184)
                {
                if (x+dx> 930 && x<=930 )
                {x= 930-dx; flag=1; } 
                }
             
    if(y> 164  && y< 195)
                {
                if (x+dx> 884  && x<=884 )
                {x= 884-dx; flag=1; } 
                }
                
                    if(y>174 && y<210 )
                {
                if (x+dx>842 && x<=842 )
                {x=842 -dx; flag=1; } 
                }
             
    if(y>192  && y< 230 )
                {
                if (x+dx>796  && x<= 796)
                {x=796 -dx; flag=1; } 
                }
                
                
                
                
                if(y> 10 && y< 124)
                {
                if (x+dx> 1008 && x<= 1008 )
                {x= 1008-dx; flag=1; } 
                }
                
                
                if(y> 106 && y< 270)
                {
                if (x+dx> 1160  && x<= 1160)
                {x=1160 -dx; flag=1; } 
                }
                
                
                if(y>200  && y<270 )
                {
                if (x+dx> 1028 && x<= 1028 )
                {x= 1028 -dx; flag=1; } 
                }
                
                
                if(y>248  && y<288 )
                {
                if (x+dx>976  && x<=976 )
                {x= 976-dx; flag=1; } 
                }
                
                
                if(y> 262 && y< 295 )
                {
                if (x+dx> 258  && x<= 258)
                {x= 258 -dx; flag=1; } 
                }
                
                
                if(y> 224  && y< 398 )
                {
                if (x+dx> 250 && x<= 250)
                {x= 250-dx; flag=1; } 
                }
                
                
                if(y> 285 && y< 500)
                {
                if (x+dx>1160  && x<=1160 )
                {x= 1160-dx; flag=1; } 
                }
                
                
                if(y> 334 && y< 384 )
                {
                if (x+dx> 898  && x<= 898 )
                {x= 898-dx; flag=1; } 
                }
                
                
                if(y> 364 && y< 405)
                {
                if (x+dx>738  && x<= 738 )
                {x= 738-dx; flag=1; } 
                }
                
                
                if(y> 418 && y< 468)
                {
                if (x+dx> 572  && x<= 572)
                {x= 572 -dx; flag=1; } 
                }
                
                
                if(y> 404 && y< 682)
                {
                if (x+dx> 756  && x<= 756 )
                {x= 756 -dx; flag=1; } 
                }
                
                
                if(y> 473  && y< 512 )
                {
                if (x+dx> 916  && x<= 916)
                {x= 916-dx; flag=1; } 
                }
                
                
                if(y> 511 && y< 577)
                {
                if (x+dx> 940  && x<= 940 )
                {x= 940 -dx; flag=1; } 
                }
                
                
                if(y> 862 && y< 688 )
                {
                if (x+dx> 1010  && x<= 1010)
                {x= 1010 -dx; flag=1; } 
                }
                
                
                if(y> 510 && y< 688)
                {
                if (x+dx>1160  && x<= 1160)
                {x= 1160-dx; flag=1; } 
                }
                
                
                if(y> 524  && y< 558)
                {
                if (x+dx>628  && x<=628 )
                {x=628 -dx; flag=1; } 
                }
                
                
                if(y> 534 && y< 688)
                {
                if (x+dx> 483  && x<= 483 )
                {x= 483-dx; flag=1; } 
                }
                
                
                if(y>388  && y< 494 )
                {
                if (x+dx> 410 && x<= 410 )
                {x= 410 -dx; flag=1; } 
                }
                
                
                if(y> 511 && y< 688 )
                {
                if (x+dx> 410  && x<= 410 )
                {x= 410-dx; flag=1; } 
                }
                
                
                if(y> 440 && y< 492 )
                {
                if (x+dx> 346  && x<= 346)
                {x= 346 -dx; flag=1; } 
                }
                
                if(y>470  && y< 511 )
                {
                if (x+dx> 286  && x<= 286)
                {x= 286 -dx; flag=1; } 
                }
                
                
                if(y> 508 && y< 688 )
                {
                if (x+dx> 170  && x<= 170)
                {x= 170 -dx; flag=1; } 
                }
                
                
                if(y> 554 && y< 688 )
                {
                if (x+dx> 256  && x<= 256 )
                {x= 256-dx; flag=1; } 
                }
                
                
                if(y> 576 && y< 650 )
                {
                if (x+dx> 240  && x<= 240 )
                {x= 240 -dx; flag=1; } 
                }
                
                
                if(y> 586 && y< 638 )
                {
                if (x+dx> 207  && x<= 207 )
                {x= 207 -dx; flag=1; } 
                }
                        if(flag==1)
                        collide.play();
            
                if (flag == 0 ) {
                x += dx;
                buster.drawAnimated(x, y, [7,8,9,10,11,12],ctx);
                }
       
            } break;                                //End case 39 sect 1
} //End Switch 1

}//keyfunction
} //End keyInput


 
function timedCount() {
    //document.getElementById("txt").value = c;
   c = c + 1;
    if(c<60)
    t = setTimeout(function(){ timedCount() }, 3000);
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
    window.location="lastpagefinal/demo.php";
}
function init() {
  audio.load();
  collect.load();
  collide.load();
          function draw() {
            //ctx.clearRect(0,0,1200,700);
            document.getElementById("clock").innerHTML=c;
            ctx.drawImage(port1,334,275,50,50);
            ctx.drawImage(port2,820,400,70,70);
            
            if (inVault() && have.length>=1)   //Player present in the vault and he has a resource
            {
            
            if (have[0]==wat)
            { 
            ctx.drawImage(wat,1045,350,80,80);
            vault.push(have[0]);
            have.splice(wat,1);
            }   
            
              if (have[0]==oxy)
            { 
            ctx.drawImage(oxy,1045,350,80,80);
            vault.push(have[0]);
            have.splice(oxy,1);
             }
             
             if (have[0]==foo)
             {ctx.drawImage(foo,1045,350,80,80);
                vault.push(have[0]);
             have.splice(foo,1); 
           
              }
             
             if (have[0]==fue)
             {ctx.drawImage(fue,1045,350,80,80);
                vault.push(have[0]);
             have.splice(fue,1);
               }
             
              if (have[0]==key)
             {ctx.drawImage(key,1045,350,80,80);
                vault.push(have[0]);
             have.splice(key,1);
               }

             if (have[0]==key)
             {ctx.drawImage(key,1045,350,80,80);
             vault.push(have[0]);
             have.splice(key,1);   
             }

              if (have[0]==tor)
             {ctx.drawImage(tor,1045,350,80,80);
             vault.push(have[0]);
             have.splice(tor,1);   
             }
            }
           
        
              for (var i =0 ;i< resource_count;i++)
              {

           ctx.drawImage(resources[i],rx[i],ry[i],30,30);
            
          }
             
           requestAnimationFrame(draw);
          }
          draw();
          //document.getElementById("txt").value = 0;
       
          audio.play();

     
     }
     window.onload = init();
        window.addEventListener('keydown', keyInput, true);
      </script>
    </body>
      </html>
