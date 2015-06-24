<!doctype html>
<html><head>
<script src="enchant.js"></script>
<style type="text/css">
/* Highscores CSS */
ul#menu li {
    display:block;
    float: left;
    border-bottom: 1px solid black;
    font-family: verdana;
    margin-bottom: 5px;
    padding-bottom: 5px;
}
.li1{
    width: 550px;
    padding-left:20px;
}
.li2{
    width: 80px;
}
#highscores {
    width: 670px;
    height: 500px;
    margin: 0 auto;
    cursor: default;
}
#highscoreWrapper{
    width: 100%;
    height: 100%;
    background: url('bg.jpg');
    top: 0px;
    position: absolute;
}
#menu {
    padding-left: 0px;
    overflow-y: auto;
    height: 100%;
}
h2 {
    text-align: center;
    width: 100%;
    color: white;
    font-family: verdana;
    position: static;
}
body,html{
    width: 1000px;
    height: 700px;
}
</style>

</head>
<body style="margin:0; padding: 0; top:0px;">

<?php
require_once('database.class.php');
// Create database object
$db = new Database();
$db->getHighscore();
$db->getKietimages();
//print json_encode($db->kietimages);
$db->deleteLowHS();
?> 

<script>

    var highscoresArray = <?php echo json_encode($db->highscores); ?>;
    var kietimagesIn = <?php echo json_encode($db->kietimages); ?>;
    var kietimages = [];

    enchant(); // initialize
    var game = new Core(1000, 700); // game stage

    for (i=1;i<kietimagesIn.length;++i){
        var str = kietimagesIn[i].users_picture;
        var str2 = "https://www.dwvhardboard.nl/sites/default/files/imagecache/avatar_crop/avatars";
        var n = str.lastIndexOf("/");
        var str = str.slice(n);
        var str3 = str2+str;
        var n2 = str3.lastIndexOf(".jpg");

        if (n2 > 0 && str3.lastIndexOf("-438") < 0 && str3.lastIndexOf("-50") < 0){
            kietimages.push(str3);
            game.preload(str3);
        }
    }

    game.preload('bg.jpg','donkey.jpg','windsurferNoBG2.png','windsurferNoBG2L.png');
    game.preload('kiets/kiet1aS.png','kiets/kiet2aS.png','kiets/kiet3aS.png','kiets/kiet4aS.png','kiets/kiet5aS.png'); // preload image
    game.preload('kiets/kiet1aSL.png','kiets/kiet2aSL.png','kiets/kiet3aSL.png','kiets/kiet4aSL.png','kiets/kiet5aSL.png'); // preload image
    game.fps = 40;

    game.onload = function(){
        // Root scene is now a start screen.
        // Go! button uses the droplet image.
        document.getElementsByTagName("body")[0].style.cursor = "url('Crosshairs2.ico'), auto"; 
        var background = new Sprite(1000, 700); 
        background.x = background.y = 0; 
        background.image = game.assets['bg.jpg']; 
        // setup the static stuff
        game.rootScene.addChild(background);

        game.goButton = new Sprite(100, 100);
        game.goButton.image = game.assets['kiets/kiet1aS.png'];
        game.goButton.x = (game.width - game.goButton.width) / 2 - 50;
        game.goButton.y = (game.height - game.goButton.height) / 2 + 50;
        game.rootScene.addChild(game.goButton);

        game.goButtonOverlay = new Sprite(150,150);
        game.goButtonOverlay.x = (game.width - game.goButton.width) / 2 - 125;
        game.goButtonOverlay.y = (game.height - game.goButton.height) / 2;
        game.rootScene.addChild(game.goButtonOverlay);        
      
        game.screenMsg = new Label("Schiet de kiet om de game te starten!");
        game.screenMsg.x = 200;
        game.screenMsg.y = 100;
        game.screenMsg.width = 600;
        game.screenMsg.font = "italic bold 30px Georgia, serif";
        game.rootScene.addChild(game.screenMsg);

        game.screenMsg3 = new Label("<br><br>HIGHSCORES");
        game.screenMsg3.x = 350;
        game.screenMsg3.y = 120;
        game.screenMsg3.width = 400;
        game.screenMsg3.font = "bold 30px Georgia, serif";
        game.rootScene.addChild(game.screenMsg3);

        game.screenMsg2 = new Label("(C) Wout Oude Elferink 2014, made with enchant.js");
        game.screenMsg2.x = 10;
        game.screenMsg2.y = 670;
        game.screenMsg2.width = 300;
        game.screenMsg2.font = "10px Georgia, serif";
        game.rootScene.addChild(game.screenMsg2);
       
        // Make the scene with the game in it go when button clicked.
        game.goButtonOverlay.addEventListener(Event.TOUCH_END, function (event) {
            game.pushScene(makeSchietScene());
        });          

        game.screenMsg3.addEventListener(Event.TOUCH_END, function (event) {
            toggleHighscores();
        });

    }

    game.start(); // start your game!

    function makeSchietScene(){
        var schietScene = new Scene();


        var score = 0;
        var label_score = new Label("score: " + score);     

        var background = new Sprite(1000, 700); 
        background.x = background.y = 0; 
        background.image = game.assets['bg.jpg']; 

        // setup the static stuff
        schietScene.addChild(background);
        schietScene.addChild(label_score);


        // make new class for enemy
        var Enemy = enchant.Class.create(enchant.Sprite, {
            initialize: function(){
                var diffKiet = Math.floor(Math.random()*5 - 0.01);
                var diffDirection = Math.floor(Math.random()*2 - 0.01);
                enchant.Sprite.call(this, 100, 100);

                switch (diffDirection) {
                    case 0:
                        switch (diffKiet) {
                            case 0:
                                this.image = game.assets['kiets/kiet1aS.png']; // set image
                                break;
                            case 1:
                                this.image = game.assets['kiets/kiet2aS.png']; // set image
                                break;
                            case 2:
                                this.image = game.assets['kiets/kiet3aS.png']; // set image
                                break;
                            case 3:
                                this.image = game.assets['kiets/kiet4aS.png']; // set image
                                break;
                            case 4:
                                this.image = game.assets['kiets/kiet5aS.png']; // set image
                                break;
                        }
                        this.InitSpeed = Math.floor(Math.random() * 200 + 150);
                        this.EndPos = -1101;
                        this.moveTo(1000, Math.floor(Math.random() * 400)); // set position
                        this.scaleX = -1;
                        this.tl.moveBy(this.EndPos, 0, this.InitSpeed);      // set movement
                        break;
                    case 1:
                        switch (diffKiet) {
                            case 0:
                                this.image = game.assets['kiets/kiet1aSL.png']; // set image
                                break;
                            case 1:
                                this.image = game.assets['kiets/kiet2aSL.png']; // set image
                                break;
                            case 2:
                                this.image = game.assets['kiets/kiet3aSL.png']; // set image
                                break;
                            case 3:
                                this.image = game.assets['kiets/kiet4aSL.png']; // set image
                                break;
                            case 4:
                                this.image = game.assets['kiets/kiet5aSL.png']; // set image
                                break;
                        }
                        this.InitSpeed = Math.floor(Math.random() * 200 + 150);
                        this.EndPos = 1101;
                        this.moveTo(-100, Math.floor(Math.random() * 400)); // set position
                        this.scaleX = -1;
                        this.tl.moveBy(this.EndPos, 0, this.InitSpeed);      // set movement
                        break;
                }

                schietScene.addChild(this);     // canvas

            },

            onenterframe: function() {
                if (this.intersect(Mouse)){
                    score++;
                    label_score.text = "score: " + score;
                    schietScene.removeChild(this.ItsKieter);
                    schietScene.removeChild(this);   
                }
                if (this.x > 1000 || (this.x < -99 && this.EndPos < 0)){
                    gameOver();
                }
            }
        });

        // make new class for enemy
        var WindSurfer = enchant.Class.create(enchant.Sprite, {
            initialize: function(){
                enchant.Sprite.call(this, 106, 75);

                if (Math.random() > 0.5){
                    this.image = game.assets['windsurferNoBG2.png']; // set image                
                    this.EndPos = -1101;
                    this.moveTo(1000, 450); // set position
                } else {
                    this.image = game.assets['windsurferNoBG2L.png']; // set image                
                    this.EndPos = 1101;
                    this.moveTo(-100, 450); // set position    
                }

                this.scaleX = -1;
                this.InitSpeed = Math.floor(Math.random() * 150 + 150);
                this.tl.moveBy(this.EndPos, 0, this.InitSpeed);      // set movement

                schietScene.addChild(this);     // canvas

            },

            onenterframe: function() {
                if (this.intersect(Mouse)){
                    gameOver(); 
                }
                if (this.x > 1000 || (this.x < -99 && this.EndPos < 0) ){
                    score++;
                    label_score.text = "score: " + score;
                    schietScene.removeChild(this);
                }
            }
        });

        // make new class for kieter
        var Kieter = enchant.Class.create(enchant.Sprite, {
            initialize: function(InitPosX, InitPosY, InitSpeed, EndPos){
                if (InitPosX > 0){
                    InitPosX += 0;
                    EndPos -= 150;
                }
                else {
                    InitPosX -= 40;
                    EndPos += 150;
                }

                var InitPosYDonkey = Math.floor(Math.random() * 80 + 560);
                var sizeY = InitPosYDonkey - InitPosY;
                var dst = new Surface(150, sizeY);
                var Img = kietimages[Math.floor(Math.random() * kietimages.length)];
                var src = game.assets[Img];
                //var src = game.assets['donkey.jpg'];
                var speed = InitSpeed * ((game.width+150) / game.width);
                
                if (EndPos<0){
                    dst.context.beginPath();
                    dst.context.moveTo(20,sizeY-20);
                    dst.context.lineTo(120,70);
                    dst.context.strokeStyle="#ffffff";
                    dst.context.stroke();
                    dst.context.beginPath();
                    dst.context.moveTo(20,sizeY-20);
                    dst.context.lineTo(70,50);
                    dst.context.strokeStyle="#ffffff";
                    dst.context.stroke();
                    dst.draw(src,0,sizeY-50);         // Draws source at (0, 0)
                } else {
                    dst.context.beginPath();
                    dst.context.moveTo(130,sizeY-20);
                    dst.context.lineTo(30,70);
                    dst.context.strokeStyle="#ffffff";
                    dst.context.stroke();
                    dst.context.beginPath();
                    dst.context.moveTo(130,sizeY-20);
                    dst.context.lineTo(80,50);
                    dst.context.strokeStyle="#ffffff";
                    dst.context.stroke();
                    dst.draw(src,100,sizeY-50);         // Draws source at (0, 0)                    
                } 
                
                enchant.Sprite.call(this, 150, sizeY);
                this.image = dst;
                this.moveTo(InitPosX, InitPosYDonkey - sizeY); // set position

                this.scaleX = -1;
                this.tl.moveBy(EndPos, 0, speed);  // set movement
                schietScene.addChild(this);     // canvas
            }
        });

        var Mouse = enchant.Class.create(enchant.Sprite, {
            initialize: function(){
                enchant.Sprite.call(this, 32, 32);
                this.moveTo(0, 0); // set position
                this.scaleX = -1;
                this.tx = this.x;
                this.ty = this.y;
                this.newClick = true;

                schietScene.addChild(this);     // canvas
            },

            onenterframe: function() {
                if (this.newClick){
                    this.x = this.tx;
                    this.y = this.ty;
                    this.newClick = false;
                }
                else {
                    this.x = -100;
                    this.y = -100;
                }
            }
        });

        // generate enemy every ?? frames
        schietScene.tl.then(function() {
            if ( (Math.random() - 0.9 + ((80 - 90/(Math.sqrt(score+1)/3+1))/75)) > 0 ){
            var enemy = new Enemy();
            var kieter = new Kieter(enemy.x, enemy.y, enemy.InitSpeed, enemy.EndPos);
            enemy.ItsKieter = kieter;
            if (Math.random() > 0.8){
                new WindSurfer();
            }
            }
         }).delay(30).loop(); 

        var Mouse = new Mouse();

        schietScene.addEventListener('touchend', function(e){
            Mouse.tx = e.x+16;
            Mouse.ty = e.y+16;
            Mouse.newClick = true;
        });

        function gameOver(){
            var name = prompt ('Game Over! Score: ' + score,'Naam');
            if (name){
                highscoresArray.push({name: name, score: score});
                toggleHighscores();
                postStuff('addHStoDB.php', name, score);
            }
            game.popScene();
        }

        return schietScene;        
    };



    function toggleHighscores(){
        createHighscoreDiv();

        divH = document.getElementById('highscoreWrapper');
        divG = document.getElementById('enchant-stage');

        if (divH.style.display == "none"){
            divH.style.display = "block";
            divG.style.display = "none";
        } else {
            divH.style.display = "none";
            divG.style.display = "block";
        }

    }
    function createHighscoreDiv(){
        // Sort array
        var all = highscoresArray;

        all.sort(function(a, b) {
          return a.score - b.score;
        });

        var A = [];
        var B = [];

        all.reverse();
        for (var i = 0; i < all.length; i++) {
           A.push(all[i].score);
           B.push(all[i].name);
        }  

        MenuUL = document.getElementById("menu");
        htmlcode = "";
        for (i=0; i<highscoresArray.length;i++){
            nameHTML = '<li class="li1">' + (i+1) + ' ' + B[i] + '</li>';
            scoreHTML = '<li class="li2">' + A[i] + '</li><br>';
            htmlcode = htmlcode + nameHTML + scoreHTML; 
        }
        MenuUL.innerHTML = htmlcode;

    }

    function postStuff(path, name, score){
        // Create our XMLhttpRequest object
        var hr = new XMLHttpRequest();
        // Create some variables we need to send to our PHP file
        var url = path;
        var vars = "name="+name+"&score="+score;
        hr.open("POST", url, true);
        hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        // Access the onreadystatechange event for the XMLhttpsRequest object
        hr.onreadystatechange = function() {
            if(hr.readyState == 4 && hr.status == 200) {
                var return_data = hr.responseText;
            }
        }
        // Send the data to PHP now... and wait for response to update the status div
        hr.send(vars); // Actually execute the request
    }

</script>

<div id="highscoreWrapper" style="display:none;" onclick="toggleHighscores();">
    <div id="highscores">
        <h2>HIGHSCORES</h2>
        <ul id="menu"></ul>
    </div>
</div> 

</body>
</html>
