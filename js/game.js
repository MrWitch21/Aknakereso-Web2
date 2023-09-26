//#########################################################\\
//--------------Készítette: Gyönge Máté László--------------\\
//---------------------Neptun kód:LFVY9F---------------------\\
//############################################################\\

//tesztet segítő eszközök kommentelve
let testMode = false;  

let game = document.getElementById("game");

const mapSizeInput = document.getElementById("mapSize"), startButton = document.getElementById("startButton"), difficulty = document.getElementById("difficulty"), infoBox = document.getElementById("infoBox"), message = document.getElementById("message");

let mapSize = 0, bomb = 0, time = 0, mineTally = 0;

let difficultySelected = false , mapSizeSelected = false, timer = false, pauseTime, gameOver= false;

let $timer = $('#timer'), $bombCounter = $('#bombCounter');

//--------------User-input szabályozása--------------\\

document.addEventListener("DOMContentLoaded", function () {
  startButton.addEventListener("click", checkIn);
  mapSizeInput.addEventListener("input", handleMapSizeInput);
  difficulty.addEventListener("input", handleDifficultyInput);
});

function handleMapSizeInput() {
  mapSize = parseInt(mapSizeInput.value);
  mapSizeSelected = true; // pályaméret ellenőrzése
  //console.log("mapSize értéke most: " + mapSize);
}

function handleDifficultyInput() {
  difficultySelected = true; // nehézségi szint ellenőrzése
}

function checkIn() {
  if (!mapSizeSelected) {
    mapSize = parseInt(mapSizeInput.value) || 6;
    mapSizeSelected = true; // pályaméret ellenőrzése
    //console.log("mapSize értéke most: " + mapSize);
  }
  generateGrid();
}

//--------------Pálya generálása--------------\\

function generateGrid() {
  
  if (!difficultySelected || !mapSizeSelected) {
    alert("Kérlek válassz pálya nagyságot és/vagy nehézségi szintet!");
    return;
  }
  if(!(mapSize >= 6 && mapSize <= 21)){
    alert("Kérlek válassz megfelelő pályaméretet (6-20)");
    return;
  }
  calcBomb();
  //console.log("Bomb: "+bomb) - test

  mineTally = bomb;
  const menu = document.getElementById("menu");
  menu.style.display = "none";
  document.getElementById("hidden").classList.remove("hide");
  game.classList.remove("hide");

  //Pálya generálása bemenet*bemenet
  game.innerHTML="";
  for (let i=0; i<mapSize; i++) {
    let row = game.insertRow(i);
    for (let j=0; j<mapSize; j++) {
      let cell = row.insertCell(j);
      cell.onclick = function() { clickBlock(this); };
      //Zászlózás jobb kattintással
      cell.oncontextmenu = function(e) {
        e.preventDefault()
        addFlag(this)
      }
      let mine = document.createAttribute("bomb");       
      mine.value = "false";

      cell.setAttributeNode(mine);
    }
  }
  //Bombák a pályához adása
  addMines();
  bombDisplay(mineTally);
  //idő elindítása
  timer = window.setInterval(startTimer, 1000);
}


//--------------idő szabályozása--------------\\

//idő kiírása a játékos számára
function startTimer() {
  time++;
  if (time < 10) {
    $timer.html('00'+time);
  } else if (time > 9 && time < 100) {
    $timer.html('0'+time);
  } else {
    $timer.html(time);
  }
  if(time == 999)
  {
    stopTime();
    showInfoMessage("Vesztettél, kifogytál az időből!");
    gameOver = true;
  }
}

//idő megállítása
function stopTime() {
  window.clearInterval(timer);
}

//--------------Bombák szabályozása--------------\\

//Bombák számának meghatározása
function calcBomb(){
  switch (difficulty.value)
  {
    case "könnyű":
      bomb= Math.floor((mapSize*mapSize)*0.1);
      break;
    case "közepes":
      bomb =Math.floor((mapSize*mapSize)*0.20);
      break;
    default:
      bomb =Math.floor((mapSize*mapSize)*0.35);
      break;
  }
  if(bomb<=1)bomb = 2;
}

//Bombák lehelyezése
function addMines() {
  //lehelyezett bombák követéséhez - test
  let placedMines = []; 

  //Bombák random elhelyezése és ellenőrzés duplikáció ellen
  for (let i = 0; i < bomb; i++) {
    let row, col, cell;
    do {
      row = Math.floor(Math.random() * mapSize);
      col = Math.floor(Math.random() * mapSize);
      cell = game.rows[row].cells[col];
    } while (cell.getAttribute("bomb") == "true" || placedMines.includes(row + "-" + col));

    cell.setAttribute("bomb", "true");
    placedMines.push(row + "-" + col); 
    if (testMode) cell.innerHTML = "X"; 
  }
  //console.log(placedMines); - test
}

//Bombák poziciói megmutatása a játékos számára
function revealMines() {
    for (let i=0; i<mapSize; i++) {
      for(let j=0; j<mapSize; j++) {
        let cell = game.rows[i].cells[j];
        if (cell.getAttribute("bomb")=="true"){
          if(!cell.classList.contains('flag')){
            cell.innerHTML = "💣"
          }
          cell.className="mine";
        }
      }
    }
    gameOver = true;
}

//Bombák számának kiírása a játékosnak
function bombDisplay(mineTally) {
  document.getElementById("BombCounter").textContent = mineTally < 10 ? '0' + mineTally : mineTally;
}

//--------------Zászlók szabályozása--------------\\

//Zászlók hozzáadása
function addFlag(cell) {
  if (gameOver) return;
  if (!cell.classList.contains('clicked')) {
    if (!cell.classList.contains('flag')) {
      cell.classList.add('flag');
      cell.innerHTML = '🚩';
    } else {
      cell.classList.remove('flag');
      cell.innerHTML = '';
    }
  }
}

//--------------Játékmenet szabályozása--------------\\

//Egy "lépés"
function clickBlock(cell) {
  if(gameOver) return;
  //Ha bombára kattint a user
  if (cell.getAttribute("bomb")=="true") {
    revealMines();
    stopTime();
    showInfoMessage("Vesztettél, vége a játéknak!");
    gameOver = true;
  } else {
    cell.className="clicked";

    //Bombák száma meghatározása körülötte
    let bombCount = 0;
    let cellRow = cell.parentNode.rowIndex, cellCol = cell.cellIndex;
    
    for (let i=Math.max(cellRow-1,0); i<=Math.min(cellRow+1,mapSize-1); i++) {
      for(let j=Math.max(cellCol-1,0); j<=Math.min(cellCol+1,mapSize-1); j++) {
        if (game.rows[i].cells[j].getAttribute("bomb")=="true") bombCount++;
      }
    }
    cell.innerHTML=bombCount;

    if (bombCount==0) { 
      //Ha nincs bomba körülötte, akkor minden további 0-ás meghatározása
      for (let i=Math.max(cellRow-1,0); i<=Math.min(cellRow+1,mapSize-1); i++) {
        for(let j=Math.max(cellCol-1,0); j<=Math.min(cellCol+1,mapSize-1); j++) {
          //Rekurzió
          if (game.rows[i].cells[j].innerHTML=="") clickBlock(game.rows[i].cells[j]);
        }
      }
    }
    //végén ellenőrizzük hogy nyert a játékos
    checkWin();
  }
}

//ellenőrizzük, hogy nyert-e, és ha igen, akkor megállítjuk az időt 
function checkWin() {
  let win = true;
    for (let i=0; i<mapSize; i++) {
      for(let j=0; j<mapSize; j++) {
        if ((game.rows[i].cells[j].getAttribute("bomb")=="false") && (game.rows[i].cells[j].innerHTML=="")) win=false;
      }
  }
  if (win) {
    stopTime();
    showInfoMessage("Gratulálok, te nyertél!")
    gameOver = true;
    //játékadatok egybefűzése
    let gamedata = {
      "gametime" : time,
      "dif" : difficulty.value,
      "size" : mapSize
    }
    //adatok elküldése
    revealMines();
    fetch("gameover.php", {
      "method" : "POST",
      "headers" : {
        "Content-Type" : "application/json; charset=utf-8"
      },
      "body" : JSON.stringify(gamedata)
    }).then(function(response){
      return response.text();
    }).then(function(data){
      //console.log(data)
    }) //hiba kezelés - visszajövő üzenet
  }
}

function showInfoMessage(ms) {
  infoBox.style.display = "block";
  message.innerHTML = ms;
}

document.getElementById("newgame").addEventListener("click", function() {
  location.reload();
});

