<html>
    <?php include 'commoncode.php';
    include 'connect.php';
session_start();
$username=$_SESSION['tek_emailid'];
$bits=explode("@",$username);
$username=$bits['0'];
 
    $sql="SELECT * FROM level3 WHERE tk_emailid='$username'";
    $query=mysql_query($sql) or die(mysql_error());
    $row=mysql_fetch_assoc($query);
    $played=$row['level_played'];
    if($played == 0)
 {   
    SendScore("sixty-seconds",175,$username);
    $sql1="UPDATE level3 SET level_played=1 WHERE tk_emailid='$username' ";
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
 <body>

 <div id="wallcontainer">
 
 <audio id="audio"> <source src="../audio/60_seconds.ogg" type='audio/ogg; '></audio>
  
  <audio id="collide"> <source src="assets/audio/hit.wav" type='audio/wav; '></audio>
  <audio id="collect"> <source src="assets/audio/collect.ogg" type='audio/ogg; '></audio>

 <div id="canvas-container"><canvas id="canvas" width="1250" height=" 750"></canvas></div>
  <span id="clock"></span>
 </div>
 <div id="light"> <center><span> End of Level Three.   </span> <button id="click" > Continue</button><center></div>

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
var aud=document.getElementById("audio");   //background
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
var y =  50;
var bBoxRight;
var bBoxLeft;
var bBoxBottom;
var bBoxTop;
var aBoxRight;
var aBoxLeft;   
var aBoxBottom;
var aBoxTop; 
var c;
var keyFunction=true;
                   //Half of buster's height and width
//ajax parameters  

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


oxy.src ="assets/img/gas.png";   
wat.src ="assets/img/water.png";  
fue.src="assets/img/fuel.png";    
foo.src="assets/img/foo.png";  
tor.src="assets/img/tor.png";
port1.src="assets/img/portal1.png";
port2.src="assets/img/portal2.png";



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

        resources[14] = tor ;
        resources[14].id = 'tor'

rx.push(800);ry.push(73); //water
rx.push(669);ry.push(542);
rx.push(1001);ry.push(308);
rx.push(476);ry.push(117);
rx.push(353);ry.push(649);

rx.push(1144);ry.push(144); //feul
rx.push(761);ry.push(360);
rx.push(993);ry.push(502);
rx.push(758);ry.push(639);

rx.push(476);ry.push(364); //food
rx.push(924);ry.push(581);
rx.push(134);ry.push(634);
rx.push(543);ry.push(117);

rx.push(493);ry.push(436); //oxygen
rx.push(255);ry.push(200); //tor

 
        var have =new Array();    //Created this
        resource_count=resources.length;
    
      function in_portal()
      {
        if(x>834 && x<884)
          if(y>175 && y<225)
            {x=65;
            y=65;}
     }

function inVault() {    //Cupboard in room 1, Section 1
if (x>74 && x<151)
{
if(y>209 && y<323 )
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

function intersects( aBoxLeft,  aBoxRight,  aBoxTop,  aBoxBottom, bBoxLeft,  bBoxRight,  bBoxTop,  bBoxBottom) {
   if( bBoxRight >= aBoxLeft && bBoxBottom >= aBoxTop && aBoxRight >= bBoxLeft && aBoxBottom >= bBoxTop)
            collided = true;
}                                                         //End Intersects

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

intersects( aBoxLeft,  aBoxRight,  aBoxTop,  aBoxBottom,
                           bBoxLeft,  bBoxRight,  bBoxTop,  bBoxBottom);
if(collided)
{
   remove(i);
   collect.play();
   collided=false;
}
}
 
if (ini==0)                                            //Start Timer on first move by player
{//document.getElementById("clock").value = 0;
startCount();}
ini=1;
 
 
var sect=getsection(x,y);
if (sect==1)                                                      //Section 1 of 4
{

switch(evt.keyCode)
{

case 38:{         flag=0;
                        if(x>12 && x<612 )
                        if  (y-dy<19 && y+6>=19)
                        {y=dy+19; flag=1;

                         }                            //Upper wall tick
                     
                        if(x>250 && x<400)
                        {
                        if (y-dy<162 && y+6>=162)
                        {y=dy+162; flag=1; }                            //White bedroom wall 1 tick
                        }
                     
                        if(x>455 && x<612)
                        {
                        if (y-dy<162 && y+6>=162)
                        {y=dy+162; flag=1; }                            //White bedroom wall 2 tick
                        }
                     
                        if(x>261 && x<374)
                        {
                        if (y-dy<112 && y+6>=112)
                        {y=dy+112; flag=1; }                            //White bed
                        }
                     
                        if(x>521 && x<612)
                        {
                        if (y-dy<83 && y+6>=83)
                        {y=dy+83; flag=1; }                                //Section 1 PC Table
                        }
                       

                         //sect 3 ka part since we have only two sections now
                      if(x>12  && x<113)
                        {if(y-dy <409 && y+6>=409)
                       {  y=409+dy;
                        flag=1;     }
                        }
                     
                        if(x>142  && x<309)
                        {
                        if (y -dy < 409 && y+6>=409 )
                        {  y=409+dy;
                        flag=1;     }
                        }
                     
                        if(x>370  && x<472)
                        {
                        if (y -dy <545 && y+6>=545 )
                        {  y=545+dy;
                        flag=1;     }
                        }
                     if(x>355  && x<390)
                        {
                        if (y -dy <408 && y+6>=408 )             //tick all of the above
                        {  y=408+dy;
                        flag=1;     }
                        }
                        if(x>464  && x<636)
                        {
                        if (y -dy <408 && y+6>=408 )             //tick all of the above
                        {  y=408+dy;
                        flag=1;     }
                        }
                       if(x>468 && x<529)
                       {
                        if(y-dy <350 && y+6>=350)
                        {
                          y=350+dy;flag=1;
                        }
                       }
                       if(x>532 && x<556)
                       {
                        if(y-dy <365 && y+6>=365)
                        {
                          y=365+dy;flag=1;
                        }
                       }
                        if(x>16 && x<103)
                        {
                        if (y -dy <624 && y+6>=624)
                        {  y=624+dy;
                        flag=1;     }
                        }
                        if(x>89 && x<239)
                        {
                        if (y -dy <617 && y+6>=617)
                        {  y=617+dy;
                        flag=1;     }
                        }
                        if (flag==1)
                        collide.play();
                        if (flag == 0 ){
                        y -= dy;
                        buster.drawAnimated(x, y, [67,68,69,71],ctx);
                        }
        } break;                                //End case 38 sect 1

case 40:{               flag=0;                                 
                        if(x>176 && x<243)
                        {
                        if (y+dy>122 && y-6<=122)
                        {y=122-dy; flag=1; }                                //Section 1 Room 1
                        }
                     
                        if(x>250 && x<407)
                        {
                        if (y+dy>128 && y-6<=128)
                        {y=128-dy; flag=1; }                                //Section 1 White Bed
                        }
                     
                        if(x>462 && x<614)
                        {
                        if (y+dy>128 && y-6<=128)
                        {y=128-dy; flag=1; }                                //Section 1 White bed part 2 and PC room
                        }
                     
                        if(x>525 && x<650)
                        {
                        if (y+dy>145 && y-6<=145)
                        {y=145-dy; flag=1; }                                //Section 1 Dining table
                        }
                     
                        if(x>576 && x<630)
                        {
                        if (y+dy>290 && y-6<=290)
                        {y=290-dy; flag=1; }                                //Section 1 Dining table Part 1
                        }
                          //section ka part same reason
                          if(x>12 && x<113)
                        { if(y+dy> 374 && y-6<= 374)
                          {y=374-dy;
                          flag=1;}
                        }
                     
                        if(x>154 && x<195)
                        { if(y+dy> 377 && y-6 <= 377)
                          {y=377-dy;
                          flag=1;}
                        }
                     
                        if(x>250 && x<310)
                        { if(y+dy> 377 && y-6 <= 377)
                          {y=377-dy;
                          flag=1;}
                        }
                     
                        if(x>364 && x<639)
                        { if(y+dy> 377 && y-6 <= 377)
                          {y=377-dy;
                          flag=1;}
                        }
                     
                        if(x>21 && x<241)
                        { if(y+dy>475 && y-6 <= 475)
                          {y=475-dy;
                          flag=1;}
                        }
                     
                        if(x>12 && x<395)
                        { if(y+dy>665 && y-6 <=665)
                          {y= 665-dy;
                          flag=1;}
                        }
                     
                        if(x>380 && x<647)
                        { if(y+dy>625 && y-6 <=625)
                          {y= 625-dy;
                          flag=1;}
                        }
                      
                        if (flag==0 ){
                        y += dy;
                        buster.drawAnimated(x, y, [79,80,81,82,83],ctx);
                        }
        }break;                                //End case 40 sect 1

case 37:{
                        flag = 0;
                         //Reset flag
                        if(y>10 && y<692){
                        if(x-dx <17 && x>=17 )
                        {
                        flag=1;
                        x=24;}
                        }
                     
                        if(y>140 && y<384)
                        {
                        if (x-dx<250 && x>250)
                        {x=250+dx; flag=1; }                            //Section 1  Dining
                        }
                     
                        if(y>11 && y<95)
                        {
                        if (x-dx<360 && x>360)
                        {x=362+dx; flag=1; }                            //Section 1  Bedtick
                        }
                     
                        if(y>90 && y<150)
                        {
                        if (x-dx<252 && x>252)
                        {x=254+dx; flag=1; }                            //Below bed tick
                        }
                     
                        if(y>16 && y<148)
                        {
                        if (x-dx<522 && x>522)
                        {x=524+dx; flag=1; }                            //PC Room
                        }
              
                     //section 3 ka part
                     if(y>310 && y<692) {
                        if(x-dx <17 && x>=17 )
                        {
                        flag=1;
                        x=24;}
                        }
                     
                        if(y>150 && y<404) {                  //tick                 /////////////////////
                        if(x-dx <252 && x>=252 )
                        {
                        flag=1;
                        x=254+dx;}
                        }
                     
                        if(y>480 && y<616) {                  //tick
                        if(x-dx <237 && x>=237 )
                        {
                        flag=1;
                        x=237+dx;}
                        }
                     
                        if(y>411 && y<543) {
                        if(x-dx <474 && x>=474)
                        {
                        flag=1;
                        x= 474+dx;}
                        }
                       if (flag==1)collide.play();

                        if (flag == 0) {
                        x -= dx;
                        buster.drawAnimated(x, y, [13,14,15,16,17,18],ctx);
                        }
        } break;                                //End case 37 sect 1
     
case 39:     { ////////nai hua h ye yrt
              flag=0;
                //Reset flag
                if(y>130 && y<410)
                {
                if (x+dx>173 && x<=173)
                {x=173-dx; flag=1; }                            // Section 1 Room 1
                }
             
                if(y>0 && y<153)
                {
                if (x+dx>225 && x<=225)
                {x=225-dx; flag=1; }                            // Section 1 Room 1 Top
                }
             
                if(y>0 && y<153)
                {
                if (x+dx>485 && x<=485)
                {x=485-dx; flag=1; }                            // Section 1 Bedroom
                }
             
                if(y>271 && y<312)
                {
                if (x+dx>534 && x<=534)
                {x=534-dx; flag=1; }                            // Section 1 D Table
                }
             

               //sect 3 ka part
            
                        //Reset flag
                        if (y>403 && y < 630) {
                        if (x + dx > 615 && x <= 615) {
                        x = 615-dx;
                        flag = 1;
                        }
                        }

                        if (y>384 && y < 543) {
                        if (x + dx > 370 && x <= 370) {
                        x = 370-dx;
                        flag = 1;
                        }
                        }
                     
                        if (y>622 && y <686) {
                        if (x + dx > 370 && x <= 370) {
                        x = 370-dx;
                        flag = 1;
                        }
                        }
                     
                     
             if (flag==1)collide.play();
                if (flag == 0 ) {
                x += dx;
                buster.drawAnimated(x, y, [7,8,9,10,11,12],ctx);
                }
       
            } break;                                //End case 39 sect 1
} //End Switch 1
} //End section 1
if (sect==2)
{
switch(evt.keyCode)
{
case 38:{                flag=0;
                        if(x>676 && x <779 )
                        {
                        if (y-dy<19 && y+6>=19)
                        {  y=dy+19;
                        flag=1;     }                                //Top wall
                        }
                     
                        if(x>605 && x <678 )
                        {
                        if (y-dy<80 && y+6>=80)
                        {  y=80+dy;
                        flag=1;     }                                //Small
                        }
                        if(x>605 && x <650 )
                        {
                        if (y-dy<162 && y+6>=162)
                        {  y=162+dy;
                        flag=1;     }                                //Small
                        }
                     
                        if(x>700 && x <785 )
                        {
                        if (y-dy<162 && y+6>=162)
                        {  y=162+dy;
                        flag=1;     }                                //Small
                        }
                     
                        if(x>611 && x <678 )
                        {
                        if (y-dy<80 && y+6>=80)
                        {  y=80+dy;
                        flag=1;     }                                //PC
                        }
                     
                        if(x>946 && x <981 )
                        {
                        if (y-dy<249 && y+6>=249)
                        {  y=249+dy;
                        flag=1;     }                                //Wall
                        }
                     
                        if(x>1004 && x <1161 )
                        {
                        if (y-dy<131 && y+6>=131)
                        {  y=131+dy;
                        flag=1;     }                                //Bed
                        }
                       if(x>970 && x<1180)
                       {
                        if(y-dy <19 && y+6>=19)
                            {y=19+dy; flag=1;}
                       }
                        if(x>775 && x <955 )
                        {
                        if (y-dy<59 && y+6>=59)
                        {  y=59+dy;
                        flag=1;     }                                //Tub
                        }

                        //sect 4 ka part
                        if(x>645  && x<860)
                        {
                        if (y -dy < 409 && y+6>=409 )
                        {  y=409+dy;
                        flag=1;     }
                        }//
                     
                        if(x>830  && x<884)
                        {
                        if (y -dy <556 && y+6>=556 )
                        {  y=556+dy;
                        flag=1;     }
                        }//
                        if(x>702  && x<730)
                        {
                        if (y -dy <348 && y+6>=348 )
                        {  y=348+dy;
                        flag=1;     }
                        }//
                       if(x>674  && x<703)
                        {
                        if (y -dy <366 && y+6>=366 )
                        {  y=366+dy;
                        flag=1;     }
                        }//
                       /*if(x>582  && x<834) //chnfd
                        {
                        if (y -dy <343 && y+6>=343 )
                        {  y=343+dy;
                        flag=1;     }
                        }*/
                     
                        if(x>914  && x<970)
                        {
                        if (y -dy <556 && y+6>=556 )
                        {  y=556+dy;
                        flag=1;     }
                        }//
                        if(x>946  && x<981)
                        {
                        if (y -dy <398 && y+6>=398 )
                        {  y=398+dy;
                        flag=1;     }
                        }//

                                              
                        if(x>1035 && x<1182)
                        {
                        if (y -dy <335 && y+6>=335)
                        {  y=335+dy;
                        flag=1;     }
                        }
                     
                        if(x>1005 && x<1164)
                        {
                        if (y -dy <656 && y+6>=656)
                        {  y=656+dy;
                        flag=1;     }
                        }
                     
                        if(x>980 && x<1050)
                        {
                        if (y -dy <360 && y+6>=360)
                        {  y=360+dy;
                        flag=1;     }
                        }
                         if (flag==1)collide.play();
                        if (flag == 0 ){
                        y -= dy;
                        buster.drawAnimated(x, y, [67,68,69,71],ctx);
                        }
        } break;                                //End case 38 sect 2
case 40:{                  flag=0;
                        if(x>616 && x<652)
                        { if(y+dy>125 && y-6<=125)
                          {y=125-dy;
                          flag=1;}                                //Wall
                        }                               
                     
                        if(x>705 && x<768)
                        { if(y+dy>150 && y-6<=150)
                          {y=150-dy;
                          flag=1;}                                //Wall
                        }
                       if(x>612 && x<831)
                        {
                        if(x>630 && x<767)
                        { if(y+dy>240 && y-6<=240)
                          {y=240-dy;
                          flag=1;}                                //Chair
                        }else if(x>620 && x<655 )
                        { if(y+dy >272 && y-6<=272)
                          {y= 272-dy;flag=1;   }     }
                          else if(x>760 && x<800 )
                        { if(y+dy >272 && y-6<=272)
                          {y= 272-dy;flag=1;   }     }
                         else if(y+dy>285 && y-6<=285)
                          {y=285-dy;
                          flag=1;}                                //Chair
                        }
                     
                        if(x>1022 && x<1160)
                        { if(y+dy>30 && y-6<=30)
                          {y=30-dy;
                          flag=1;}                                //Bed
                        }
                     
                        if(x>971 && x<1041)
                        { if(y+dy>330 && y-6<=330)
                          {y=330-dy;
                          flag=1;}                                //Wall
                        }
                     
                        if(x>1021 && x<1184)
                        { if(y+dy>300 && y-6<=300)
                          {y=300-dy;
                          flag=1;}                                //Wall
                        }
                     
                        if(x>955 && x<972)
                        { if(y+dy>280 && y-6<=280)
                          {y=280-dy;
                          flag=1;}                                //Wall not sure
                        }
                                                        
                        //sect 4
                        if(x>665 && x<865)
                        { if(y+dy> 380 && y-6 <= 380)
                          {y=380-dy;
                          flag=1;}
                        }
                        if(x>955 && x<972)
                        { if(y+dy>420 && y-6<=420)
                          {y=420-dy;
                          flag=1;}                                //Wall not sure
                        }
                        
                        if(x>920 && x<970)
                        { if(y+dy> 523 && y-6 <= 523)
                          {y=523-dy;
                          flag=1;}
                        }
                     
                        if(x>784 && x<965)
                        { if(y+dy> 594 && y-6 <= 594)
                          {y=594-dy;
                          flag=1;}
                        }
                     
                        if(x>642 && x<728)
                        { if(y+dy>  564 && y-6 <= 564)
                          {y=564-dy;
                          flag=1;}
                        }
                     
                        if(x>1008 && x<1165)
                        { if(y+dy>547 && y-6 <= 547)
                          {y=547-dy;
                          flag=1;}
                        }
                     
                        if(x>644 && x<1183)
                        { if(y+dy>665 && y-6 <=665)
                          {y= 665-dy;
                          flag=1;}
                        }
                     
                       if (flag==1)collide.play();
                        if (flag==0){
                        y += dy;
                        buster.drawAnimated(x, y, [79,80,81,82,83],ctx);
                        }
        } break;                                 //40 section 2

case 37:{
                flag = 0; //Reset flag
             
                        if(y>0 && y<70)
                        {
                        if (x-dx<696 && x>=696)
                        {x=698+dx; flag=1; }                                //PC
                        }
                     
                        if(y>10 && y<163)
                        {
                        if (x-dx<785 && x>=785)
                        {x=785+dx; flag=1; }                                //Wall
                        }
                     
                        if(y>10 && y<60)
                        {
                        if (x-dx<946 && x>=946)
                        {x=946+dx; flag=1; }                                //tub
                        }
                     
                        if(y>10 && y<250)
                        {
                        if (x-dx<982 && x>=982)
                        {x=982+dx; flag=1; }                                //wall long
                        }
                     
                        if(y>41 && y<131)
                        {
                        if (x-dx<1161 && x>=1161)
                        {x=1161+dx; flag=1; }                                //bed
                        }
                     
                        if(y>270 && y<352)
                        {
                        if (x-dx<982 && x>=982)
                        {x=984+dx; flag=1; }                                //wall
                        }
                       if(y>337 && y<360)
                        {
                        if (x-dx<1050 && x>=1050)
                        {x=1050+dx; flag=1; }                                //wall
                        }
                     
                        if(y>295 && y<321)
                        {
                        if (x-dx<729 && x>=729)
                        {x=729+dx; flag=1; }                                //chair
                        }
                     
                        if(y>252 && y<294)
                        {
                        if (x-dx<671 && x>=671)
                        {x=671+dx; flag=1; }                                //chair
                        }
                      
                        if(y>124 && y<165)
                        {
                        if (x-dx<654 && x>=654)
                        {x=654+dx; flag=1; }                                //wall
                        }
                    
                     
                        if(y>153 && y<166)
                        {
                        if (x-dx<654 && x>=654)
                        {x=654+dx; flag=1; }                                //wall
                        }
                      //sect 4
                      if(y>405 && y<580) {
                        if(x-dx <655 && x>=655 )
                        {
                        flag=1;
                        x=655+dx;}
                        }
                     
                        if(y>315 && y<400) {
                        if(x-dx <732 && x>=732 )
                        {
                        flag=1;
                        x=732+dx;}
                        }
                     
                        if(y>577 && y<688) {
                        if(x-dx <732 && x>=732 )
                        {
                        flag=1;
                        x=732+dx;}
                        }
                     
                        if(y>365 && y<560) {
                        if(x-dx <867 && x>=867)
                        {
                        flag=1;
                        x= 867+dx;}
                        }
                     
                        if(y>357&& y<398) {
                        if(x-dx <982 && x>=982)
                        {
                        flag=1;
                        x= 982+dx;}
                        }
                     
                        if(y>410 && y<696) {
                        if(x-dx <982 && x>=982)
                        {
                        flag=1;
                        x= 982+dx;}
                        }
                      if (flag==1)collide.play();
                if (flag == 0 ) {
                  x -= dx;
                  buster.drawAnimated(x, y, [13,14,15,16,17,18],ctx);
                }
        }break;                                     //37 section 2
case 39:{
           
                flag = 0; //Reset flag
                if(y>10 && y<150)
                {
                if (x+dx>750 && x<=750)
                {x=750-dx; flag=1; }                            // wall
                }
             
                if(y>11 && y<249)
                {
                if (x+dx>945 && x<=945)
                {x=945-dx; flag=1; }                            // wall
                }
             
                if(y>300 && y<321)
                {
                if (x+dx>945 && x<=945)
                {x=945-dx; flag=1; }                            // wall
                }
             
                if(y>11 && y<60)
                {
                if (x+dx>765 && x<=765)
                {x=765-dx; flag=1; }                            //tub
                }
             
                if(y>43 && y<130)
                {
                if (x+dx>1001 && x<=1001)
                {x=1001-dx; flag=1; }                            //bed
                }
             
                if(y>11 && y<700)
                {
                if (x+dx>1163 && x<=1163)
                {x=1163-dx; flag=1; }                            //mainwall
                }

               //sect 4
                   if (y>406 && y < 643) {
                        if (x + dx > 617 && x <= 617) {
                        x = 617-dx;
                        flag = 1;
                        }
                        }

                        if (y>404 && y < 554) {
                        if (x + dx > 831 && x <= 831) {
                        x = 831-dx;
                        flag = 1;
                        }
                        }
                     
                        if (y>614 && y <695) {
                        if (x + dx > 774 && x <= 774) {
                        x = 774-dx;
                        flag = 1;
                        }
                        }
                     
                        if (y>434 && y <688) {
                        if (x + dx > 943 && x <= 943) {
                        x = 943-dx;
                        flag = 1;
                        }
                        }
                     
                        if (y>336 && y <691) {
                        if (x + dx > 1163 && x <= 1163) {
                        x = 1163-dx;
                        flag = 1;
                        }
                        }
                     
                        if (y>543 && y <659) {
                        if (x + dx > 1000 && x <= 1000) {
                        x = 1000-dx;
                        flag = 1;
                        }
                        }
                     
                        if (y>317 && y <399) {
                        if (x + dx > 942 && x <= 942) {
                        x = 942-dx;
                        flag = 1;
                        }
                        }
                      if (flag==1)collide.play();
                if (flag == 0) {
                x+= dx;
                buster.drawAnimated(x, y, [7,8,9,10,11,12],ctx);
                }
       
        } break;                                     //39 section 2

}      //Switch 2
}        // Section 2&4
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
  collide.load();
  collect.load();
          function draw() {
            //ctx.clearRect(0,0,1200,700);
            document.getElementById("clock").innerHTML=c;
          ctx.drawImage(port1,834,175,50,50);
            ctx.drawImage(port2,60,60,70,70);
            
            if (inVault() && have.length>=1)   //Player present in the vault and he has a resource
            {
            
            if (have[0]==wat)
            { 
            ctx.drawImage(wat,100,100,80,80);
            vault.push(have[0]);
            have.splice(wat,1);
            }   
            
              if (have[0]==oxy)
            { 
            ctx.drawImage(oxy,100,100,80,80);
            vault.push(have[0]);
            have.splice(oxy,1);
             }
             
             if (have[0]==foo)
             {ctx.drawImage(foo,100,100,80,80);
                vault.push(have[0]);
             have.splice(foo,1); 
           
              }
             
             if (have[0]==fue)
             {ctx.drawImage(fue,100,100,80,80);
                vault.push(have[0]);
             have.splice(fue,1);
               }
             
             if (have[0]==oxy)
             {ctx.drawImage(fue,100,100,80,80);  //CHANGE!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
             vault.push(have[0]);
             have.splice(oxy,1);  
               }
             
             if (have[0]==tor)
             {ctx.drawImage(tor,100,100,80,80);
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
