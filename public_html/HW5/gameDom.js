/*JS Fiddle Chnanges */
var canvas = document.getElementById('canvas');
// Get the canvas drawing context
var context = canvas.getContext('2d');

// Create an object representing a square on the canvas
function makeSquare(x, y, length, speed) {
  return {
    x: x,
    y: y,
    l: length,
    s: speed,
    draw: function() {
      context.fillRect(this.x, this.y, this.l, this.l);
    }
  };
}

function makeCircle(x, y, radi, sAngle, eAngle, speed, score) {
  return {
    x: x,
    y: y,
    r: radi,
    start: sAngle,
    end: eAngle,
    s: speed,
    sc : score,
    draw: function() {
      context.beginPath();
      context.arc(this.x, this.y, this.r, this.start, this.end);
      context.fillStyle = 'blue';
    	context.fill();
      context.font = "bold 50px Helvetica, Arial, sans-serif";
      context.fillStyle = "#00FF00";
      context.fillText(this.sc.toString(), this.x , this.y);
    }
  };
}

// add a makeCircle function here to make enenmires into circle not square 


// The ship the user controls
var shipW = 50;
var shipH = 5;
var ship = makeSquare(50, canvas.height / 2 - 25, shipW, shipH);

// Flags to tracked which keys are pressed
var up = false;
var down = false;
var space = false;

// Is a bullet already on the canvas?
var shooting = false;
// The bulled shot from the ship
var bullet = makeSquare(0, 0, 10, 10);

// An array for enemies (in case there are more than one)
var enemies = [];

// Add an enemy object to the array
var enemyBaseSpeed = 2;

function makeEnemy() {
  var enemyX = canvas.width;
  var enemySize = Math.round((Math.random() * 15)) + 15;
  var enemyY = Math.round(Math.random() * (canvas.height - enemySize * 2)) + enemySize;
  var enemySpeed = Math.round(Math.random() * enemyBaseSpeed) + enemyBaseSpeed;
  let enemyScore = Math.trunc((Math.random() * 6)) + 1;
  enemies.push(makeCircle(enemyX, enemyY, enemyScore * 5, 0, 2 * Math.PI, enemySpeed, enemyScore));
}

// Check if number a is in the range b to c (exclusive)
function isWithin(a, b, c) {
  return (a > b && a < c);
}

// Return true if two squares a and b are colliding, false otherwise
function isColliding(a, b) { //a = bullet square b = enemby circle 
  var result = false;
  if (isWithin(a.x, b.x - b.r, b.x + b.r) || isWithin(a.x + a.l, b.x - b.r, b.x + b.r) || isWithin(b.x - b.r, a.x, a.x + a.l)) {
    if (isWithin(a.y, b.y - b.r, b.y + b.r) || isWithin(a.y + a.l, b.y - b.r, b.y + b.r) || isWithin(b.y + b.r, a.y, a.y + a.l) || isWithin(b.y - b.r, a.y, a.y + a.l)) {
      result = true;
    }
  }
  return result;
}

// Track the user's score
var score = 0;
// The delay between enemies (in milliseconds)
var timeBetweenEnemies = 5 * 1000;
// ID to track the spawn timeout
var timeoutId = null;

// Show the game menu and instructions
function menu() {
  erase();
  context.fillStyle = '#000000';
  context.font = '36px Arial';
  context.textAlign = 'center';
  context.fillText('Shoot \'Em!', canvas.width / 2, canvas.height / 4);
  context.font = '24px Arial';
  context.fillText('Click to Start', canvas.width / 2, canvas.height / 2);
  context.font = '18px Arial';
  context.fillText('Up/Down to move, Space to shoot.', canvas.width / 2, (canvas.height / 4) * 3);
  // Start the game on a click
  canvas.addEventListener('click', startGame);
}

// Start the game
function startGame() {
  // Kick off the enemy spawn interval
  timeoutId = setInterval(makeEnemy, timeBetweenEnemies);
  // Make the first enemy
  setTimeout(makeEnemy, 1000);
  // Kick off the draw loop
  draw();
  // Stop listening for click events
  canvas.removeEventListener('click', startGame);
}

// Show the end game screen
function endGame() {
  // Stop the spawn interval
  clearInterval(timeoutId);
  // Show the final score
  erase();
  context.font = "100px Arial";
  context.fillText("💥",ship.x,ship.y);
  if(score > 10)
  {
    context.fillStyle = 'green';
    context.font = '24px Arial';
    context.textAlign = 'center';
    context.fillText('Game Over. Final Score: ' + score, canvas.width / 2, canvas.height / 2);
  }
  else if (score > 0 && score <= 10)
  {
    context.fillStyle = 'orange';
    context.font = '24px Arial';
    context.textAlign = 'center';
    context.fillText('Game Over. Final Score: ' + score, canvas.width / 2, canvas.height / 2); 
  }
  else
  {
    context.fillStyle = 'red';
    context.font = '24px Arial';
    context.textAlign = 'center';
    context.fillText('Game Over. Final Score: ' + score, canvas.width / 2, canvas.height / 2);
  }
}

// Listen for keydown events
canvas.addEventListener('keydown', function(event) {
  event.preventDefault();
  if (event.keyCode === 38) { // UP
    up = true;
  }
  if (event.keyCode === 40) { // DOWN
    down = true;
  }
  if (event.keyCode === 32) { // SPACE
    shoot();
  }
});

// Listen for keyup events
canvas.addEventListener('keyup', function(event) {
  event.preventDefault();
  if (event.keyCode === 38) { // UP 
    up = false;
  }
  if (event.keyCode === 40) { // DOWN
    down = false;
  }
});

// Clear the canvas
function erase() {
  context.fillStyle = '#FFFFFF';
  context.fillRect(0, 0, 600, 400);
}

// Shoot the bullet (if not already on screen)
function shoot() {
  if (!shooting) {
    shooting = true;
    bullet.x = ship.x + ship.l;
    bullet.y = ship.y + ship.l / 2;
  }
}

// The main draw loop
function draw() {
	//console.log("drawing");
  erase();
  var gameOver = false;
  // Move and draw the enemies
  enemies.forEach(function(enemy) {
    enemy.x -= enemy.s;
    // if (enemy.x < 0) {
    //   gameOver = true;
    // }
    enemy.draw();
  });
  // Collide the ship with enemies
  enemies.forEach(function(enemy, i) {
    if (isColliding(ship, enemy)) {
    	// decrease ship size
      gameOver = true;
    }
  });
  // Move the ship
  if (down) {
    ship.y += ship.s;
  }
  if (up) {
    ship.y -= ship.s;
  }
  // Don't go out of bounds
  if (ship.y < 0) {
    ship.y = 0;
  }
  if (ship.y > canvas.height - ship.l) {
    ship.y = canvas.height - ship.l;
  }
  // Draw the ship
  context.fillStyle = '#FF0000';
  ship.draw();
  // Move and draw the bullet
  if (shooting) {
    // Move the bullet
    bullet.x += bullet.s;
    // Collide the bullet with enemies
    enemies.forEach(function(enemy, i) {
      if (isColliding(bullet, enemy)) {
        enemies.splice(i, 1);
        score+=enemy.sc;
        shooting = false;
        
        // Make the game harder
        if (score % 10 === 0 && timeBetweenEnemies > 1000) {
          clearInterval(timeoutId);
          timeBetweenEnemies -= 1000;
          timeoutId = setInterval(makeEnemy, timeBetweenEnemies);
        } else if (score % 5 === 0) {
          enemyBaseSpeed += 1;
        }
      }
    });
    // Collide with the wall
    if (bullet.x > canvas.width) {
      shooting = false;
    }
    // Draw the bullet
    context.fillStyle = 'black';
    bullet.draw();
  }
  // Draw the score
  context.fillStyle = '#000000';
  context.font = '24px Arial';
  context.textAlign = 'left';
  context.fillText('Score: ' + score, 1, 25)
  // End or continue the game
  if (gameOver) {
    endGame();
  } else {
    window.requestAnimationFrame(draw);
  }
}

// Start the game
menu();
canvas.focus();

