<?php
session_start();
include 'db_connection.php';
$conn = OpenCon();
$userId;
// Check if the userId cookie is set
if (isset($_COOKIE['userId'])) {
    $userId = $_COOKIE['userId'];
} else {
    // show registration form
    $userId = bin2hex(random_bytes(8)); // generates a random 16 character string
    setcookie('userId', $userId, time() + (86400 * 30), "/"); // 86400 = 1 day
    echo "
    <script>
    var username = prompt('Please enter your username:');
    if (username) {
        // Make a POST request to /api/v1/register
        fetch('/api/v1/register', {
            method: 'POST',
            body: JSON.stringify({ userId: '$userId', username: username }),
            headers: {
                'Content-Type': 'application/json'
            }
        })
            .then(response => response.json())
            .then(data => {
                console.log(data);
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }
    document.getElementById('username').innerText = username;
    </script>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Idle Tosti Clicker</title>
    <link rel='stylesheet' type='text/css'
        href='https://cdn.discordapp.com/attachments/663678139268071437/1214201910849175602/home.css?ex=65f840a6&is=65e5cba6&hm=a3271a568f742c2c7ba4a96a6eced38706076c4cd7ef6d4281dc7e994f257b89&'>
</head>

<body>
    <div id="loadingScreen">
        <p>Loading...</p>
    </div>
    <!-- <div id="navbar">
        <div class="navbar-part" id="navBar">
            <h1>Idle Tosti Clicker</h1>
            <img src="https://cdn.discordapp.com/attachments/663678139268071437/1211971854529462304/image-removebg-preview.png?ex=65f023bf&is=65ddaebf&hm=45feba06ba03e6e8f3017066c4dbc755a689b758b92004b9fd29d95a7f774a52&"
                alt="JC" id="jCoinsPicture">
            <p id="jCoins">0</p>
            <p id="jCoins">0</p>
            <p id="jCoins">0</p>
        </div>
        <div class="navbar-part" id="navBarR">
            <img src="https://cdn.discordapp.com/attachments/663678139268071437/1211971854529462304/image-removebg-preview.png?ex=65f023bf&is=65ddaebf&hm=45feba06ba03e6e8f3017066c4dbc755a689b758b92004b9fd29d95a7f774a52&"
                alt="JC" id="jCoinsPicture">
            <p id="jCoins">0</p>
        </div>
    </div> -->
    <div id="leftBox">
            <div class="name" id="name">
                <h1>Idle Tosti Clicker</h1>
            </div>
            <div class="navbar-part" id="navBar">
                <img src="https://cdn.discordapp.com/attachments/663678139268071437/1211971854529462304/image-removebg-preview.png?ex=65f023bf&is=65ddaebf&hm=45feba06ba03e6e8f3017066c4dbc755a689b758b92004b9fd29d95a7f774a52&"
                    alt="JC" id="jCoinsPicture">
                <p id="jCoins">0</p><br>
                <p id="jCoinsPerSecond">0</p><br>
            </div>
    <div class="CT">  
        <img id="clickButton"
            src="https://cdn.discordapp.com/attachments/663678139268071437/1211973836895944724/WhatsApp_Image_2024-02-26_at_21.02.07-removebg-preview.png?ex=65f02598&is=65ddb098&hm=7c72cc7a4a2ccfe3c87442d0bc2a2da13dee1680f435e2dd578cf45bd160e0d7&"
            alt="Center Image">
    </div> 
    </div>
    <div id="rightBox">
        <div class="shop" id="shop">
            <h2 class="itemName">Shop</h2>
        </div>
        <div class="shopItem" id="Tosti_Ham_Kaas">
            <div class="U">
                
            </div>
            <div class="U">
                
            </div>
        </div>
        <div class="shopItem" id="buyAutoClicker">
            <img src="https://cdn.discordapp.com/attachments/663678139268071437/1212479867623571466/image-removebg-preview_2.png?ex=65f1fcdf&is=65df87df&hm=e0339fb37f5076577245a6be84ba908bb875d8b1906f11c7d28621377fba6867&" alt="item1">
            <div>
                <h2 class="itemName">Auto Clicker</h2>
                <!-- <p class="itemDescription">De Tosti word automatisch geclickt. 0.1 JostiCoin per seconde</p> -->
                <p class="itemCost" id="costAutoClicker">10</p>
                <span id="amountAutoClicker">0</span>
            </div>
        </div>
        <div class="shopItem2" id="Tosti_Ham_Kaas">
            <img src="https://cdn.discordapp.com/attachments/663678139268071437/1212479867623571466/image-removebg-preview_2.png?ex=65f1fcdf&is=65df87df&hm=e0339fb37f5076577245a6be84ba908bb875d8b1906f11c7d28621377fba6867&" alt="item1">
            <div>
                <h2 class="itemName">Tosti Ham Kaas</h2>
                <!-- <p class="itemDescription">De Tosti word automatisch geclickt. 0.1 JostiCoin per seconde</p> -->
                <p class="itemCost" id="costAutoClicker">10</p>
                <span id="amountAutoClicker">0</span>
            </div>
        </div>
        <div class="shopItem" id="Tosti_Ham_Kaas">
            <img src="https://cdn.discordapp.com/attachments/663678139268071437/1212479867623571466/image-removebg-preview_2.png?ex=65f1fcdf&is=65df87df&hm=e0339fb37f5076577245a6be84ba908bb875d8b1906f11c7d28621377fba6867&" alt="item1">
            <div>
                <h2 class="itemName">Tosti Ham Kaas</h2>
                <!-- <p class="itemDescription">De Tosti word automatisch geclickt. 0.1 JostiCoin per seconde</p> -->
                <p class="itemCost" id="costAutoClicker">10</p>
                <span id="amountAutoClicker">0</span>
            </div>
        </div>
    </div>
    <div class="Stext">
    <div id="startingText">
        <p>Welcome to Idle Tosti Clicker! Click the magical <span class="BTS">
            <img src="https://cdn.discordapp.com/attachments/663678139268071437/1211973836895944724/WhatsApp_Image_2024-02-26_at_21.02.07-removebg-preview.png?ex=65f02598&is=65ddb098&hm=7c72cc7a4a2ccfe3c87442d0bc2a2da13dee1680f435e2dd578cf45bd160e0d7&"></span>
            to earn points.</p>
    </div>
    </div>
    <div id="inventory"></div>

    <div id="registerForm" style="display: none;">
        <form action="/api/v1/register" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <input type="submit" value="Submit">
        </form>
    </div>
    <!-- <button id="shopButton" style="position: fixed; bottom: 20px; right: 20px;">Shop</button> -->
    <!-- <div id="shopDiv" style="display: none; position: fixed; bottom: 50px; right: 20px;">
        <div>
            <h1>Shop</h1>
            <div class="shopItem">
                <img src="https://cdn.discordapp.com/attachments/663678139268071437/1212479867623571466/image-removebg-preview_2.png?ex=65f1fcdf&is=65df87df&hm=e0339fb37f5076577245a6be84ba908bb875d8b1906f11c7d28621377fba6867&"
                    alt="item1">
                <div>
                    <h2>Auto Clicker</h2>
                    <p>De Tosti word automatisch geclickt. 0.1 JostiCoin per seconde</p>
                    <p id="costAutoClicker">10</p>
                    <button id="buyAutoClicker">Buy</button>
                </div>
            </div>
            <div class="shopItem">
                <img src="" alt="item2">
                <h2>Tosti Ham Kaas</h2>
                <p>De Tosti heeft ham. 2x JostiCoins per klik</p>
                <button id="Tosti Ham Kaas">Buy</button>
            </div>
            <div class="shopItem">
                <img src="" alt="item3">
                <h2>Item 2</h2>
                <p>Item 2 description</p>
                <button id="Item 3">Buy</button>
            </div>
            Add more shop items as needed 
            
        </div> -->
        <script>
            document.getElementById('Tosti_Ham_Kaas').addEventListener('click', function () {
                alert('it work pt2')
            })
        </script>
        <script>
            var userData;
            var inventoryData;
            var userId = "<?php echo strval($userId); ?>";
            /*window.onload = */(function () {
                var nameElement = document.getElementById('name');
                document.getElementById('loadingScreen').style.display = 'block';
                console.log(userId);
                if (!userId || userId === "undefined" || userId === "null") {
                    // Show the registration form
                    document.getElementById('registerForm').style.display = 'block';
                    userId = null;
                }
                var url = "http://104.248.95.43:8000/api/v1/stats?userId=" + userId;
                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        if (!data.success) {
                            var newUsername = prompt('Please enter your username:');
                            if (newUsername) {
                                nameElement.innerText = newUsername;
                                // Make a POST request to /api/v1/register
                                fetch('/api/v1/register', {
                                    method: 'POST',
                                    body: JSON.stringify({ userId: userId, username: newUsername }),
                                    headers: {
                                        'Content-Type': 'application/json'
                                    }
                                })
                                    .then(response => response.json())
                                    .then(data => {
                                        console.log(data);
                                    })
                                    .catch(error => {
                                        console.error('Error:', error);
                                    });
                            }
                        } else {
                            // Hide the loading screen
                            document.getElementById('loadingScreen').style.display = 'none';
                            var costAutoClicker = document.getElementById('costAutoClicker');
                            // Process the data
                            console.log(data);
                            userData = data.data.user;
                            inventoryData = data.data.inventory;
                            nameElement.innerText = userData.username;
                            console.log(inventoryData)
                            var autoClickerCount = 0;
                            if (inventoryData && inventoryData.length > 0) {
                                autoClickerCount = inventoryData[0].itemCount;
                                console.log(autoClickerCount);
                                document.getElementById('amountAutoClicker').innerText = autoClickerCount;
                            } else console.log("No inventory data found");
                            costAutoClicker.innerText = calculateCost(10, autoClickerCount);
                            document.getElementById('jCoins').innerText = userData.points;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });


            })();

            // Add an event listener to the click button

            var isCooldown = false;

            document.getElementById('clickButton').addEventListener('click', function () {
                if (isCooldown) return;  // If cooldown is active, don't execute the function
                var newAmount;
                var bU;
                if (!document.getElementById('tostiHamKaas')) {
                    newAmount = 1;
                    bU = false;
                } else newAmount = 2; bU = true;
                // Update the user's points locally
                var pointsElement = document.getElementById('jCoins');
                if (pointsElement.innerText === "null" || pointsElement.innerText === "undefined" || pointsElement.innerText === "NaN" || pointsElement.innerText === "0" || pointsElement.innerText === "") {
                    pointsElement.innerText = userData.points;
                }
                var currentPoints = parseInt(pointsElement.innerText);
                var tostiElement = document.getElementById('clickButton');
                pointsElement.innerText = currentPoints + newAmount;
                // Check if the user data is null
                if (userData === null) {
                    // Show the registration form
                    document.getElementById('registerForm').style.display = 'block';
                } else {
                    // Send a request to the API to increase the user's points
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', '/api/v1/click', true);
                    xhr.setRequestHeader('Content-Type', 'application/json');
                    xhr.send(JSON.stringify({ bU: bU }));


                }
                tostiElement.style.width = '145px';
                setTimeout(function () {
                    tostiElement.style.width = '150px';
                    isCooldown = false;
                }, 100);
            });

            var isBuying = false;
            document.getElementById('buyAutoClicker').addEventListener('click', function () {
                if (isBuying) return;
                isBuying = true;
                saveStats();
                var itemName = 'Auto Clicker'; // Update this to the name of the item
                var costElement = document.getElementById('costAutoClicker');
                var currentCost = parseInt(costElement.textContent, 10);

                // Make a POST request to activate.php
                fetch('/api/v1/activate', {
                    method: 'POST',
                    body: JSON.stringify({ userId: userId, itemName: itemName }),
                    headers: {
                        'Content-Type': 'application/json'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            console.log(data);
                            // Update the user's points
                            document.getElementById('jCoins').innerText = data.body.newPoints;
                            var newCost = calculateCost(10, inventoryData[0].itemCount + 1); // replace 1 with the actual amount
                            console.log(newCost)
                            costElement.innerText = newCost.toString();
                            var newAmount = document.getElementById('amountAutoClicker').innerText;
                            newAmount++;
                            document.getElementById('amountAutoClicker').innerText = newAmount;
                        } else {
                            if (data.message === "Not enough points") {
                                alert("Not enough points");
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
                setTimeout(() => {
                    isBuying = false;
                }, 1000);
            });

            /*document.getElementById('buyTostiHamKaas').addEventListener('click', function () {
                saveStats();
                var itemName = 'TostiHamKaas'; // Update this to the name of the item
                var shopItemElement = document.getElementById('tostiHamKaas');
                var currentCost = 1000;

                // Make a POST request to activate.php
                fetch('/api/v1/activate', {
                    method: 'POST',
                    body: JSON.stringify({ userId: userId, itemName: itemName }),
                    headers: {
                        'Content-Type': 'application/json'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            console.log(data);
                            // Update the user's points
                            document.getElementById('jCoins').innerText = data.body.newPoints;
                            costElement.innerHTML = "";
                        } else {
                            if (data.message === "Not enough points") {
                                alert("Not enough points");
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            });*/

            setInterval(() => {
                if (inventoryData === undefined || !inventoryData[1]) {
                    return;

                } else {
                    inventoryData.forEach(item => {
                        if (item.itemName === "Auto Clicker") {
                            var amount = 1 * item.itemCount;
                            var newPoints = parseInt(document.getElementById('jCoins').innerText) + amount;
                            document.getElementById('jCoins').innerText = newPoints;
                        }
                    });
                }
            }, 10000);

            window.onbeforeunload = function () {
                saveStats();
            };

            function calculateCost(baseCost, amount) {
                return Math.ceil(baseCost * Math.pow(1.05, amount));
            }

            function saveStats() {
                // Make a POST request to /api/v1/stats
                fetch('/api/v1/stats', {
                    method: 'POST',
                    body: JSON.stringify({ userId: userId, newPoints: document.getElementById('jCoins').innerText }),
                    headers: {
                        'Content-Type': 'application/json'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        console.log(data);
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            }
            function createTosti() {
                var perSecondElement = document.getElementById('jCoinsPerSecond');
                var perSecond = inventoryData[0].itemCount * 0.1;
                perSecondElement.innerText = `+${Math.ceil(perSecond)} per second`;
                var tosti = document.createElement('div');
                tosti.className = 'tosti';
                tosti.style.left = Math.random() * document.getElementById('leftBox').offsetWidth + 'px';
                tosti.style.animationDuration = Math.random() * 2 + 3 + 's'; // Random fall duration between 3 and 5 seconds
                document.getElementById('leftBox').appendChild(tosti);
                // Remove the tosti after it falls down
                setTimeout(function () {
                    document.getElementById('leftBox').removeChild(tosti);
                }, 5000);
            }
            // Create a new tosti every 100 milliseconds
            setTimeout(function () {
                setInterval(createTosti, 100);
            }, 500);
        </script>
</body>

</html>
