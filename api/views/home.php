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
    <title>ITC!</title>
</head>

<body>
    <style>
        #loadingScreen {
            display: flex;
            justify-content: center;
            align-items: center;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            z-index: 9999;
        }

        #loadingScreen p {
            color: white;
            font-size: 24px;
        }
    </style>

    <div id="loadingScreen">
        <p>Loading...</p>
    </div>
    <div id="navBar">
        <h1>Idle Tosti Clicker</h1>
        <img src="https://cdn.discordapp.com/attachments/663678139268071437/1211971854529462304/image-removebg-preview.png?ex=65f023bf&is=65ddaebf&hm=45feba06ba03e6e8f3017066c4dbc755a689b758b92004b9fd29d95a7f774a52&"
            alt="JC" id="jCoinsPicture">
        <p id="jCoins">0</p>
    </div>
    <div id="leftBox">
        <img id="clickButton"
            src="https://cdn.discordapp.com/attachments/663678139268071437/1211973836895944724/WhatsApp_Image_2024-02-26_at_21.02.07-removebg-preview.png?ex=65f02598&is=65ddb098&hm=7c72cc7a4a2ccfe3c87442d0bc2a2da13dee1680f435e2dd578cf45bd160e0d7&"
            alt="Center Image">
    </div>
    <div id="startingText">
        <p>Welcome to Idle Tosti Clicker! Click the Tosti to earn points.</p>
    </div>

    <div id="inventory"></div>

    <div id="registerForm" style="display: none;">
        <form action="/api/v1/register" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <input type="submit" value="Submit">
        </form>
    </div>
    <button id="shopButton" style="position: fixed; bottom: 20px; right: 20px;">Shop</button>
    <div id="shopDiv" style="display: none; position: fixed; bottom: 50px; right: 20px;">
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
            <!-- Add more shop items as needed -->
        </div>
        <script>
            var userData;
            var inventoryData;
            var userId = "<?php echo strval($userId); ?>";
            window.onload = function () {
                document.getElementById('loadingScreen').style.display = 'block';
                console.log(userId);
                if (!userId || userId === "undefined" || userId === "null") {
                    // Show the registration form
                    document.getElementById('registerForm').style.display = 'block';
                    userId = null;
                }
                var url = "http://10.100.101.65:8000/api/v1/stats?userId=" + userId;
                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        if (!data.success) {
                            var newUsername = prompt('Please enter your username:');
                            if (newUsername) {
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
                            // Process the data
                            console.log(data);
                            userData = data.data.user;
                            inventoryData = data.data.inventory;
                            console.log(inventoryData)
                            var autoClickerCount = 0;
                            if (inventoryData && inventoryData.length > 0) {
                                autoClickerCount = inventoryData[0].itemCount;
                            } else console.log("No inventory data found");
                            costAutoClicker.innerText = calculateCost(10, autoClickerCount);
                            document.getElementById('jCoins').innerText = userData.points;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });


            };

            // Add an event listener to the click button
            document.getElementById('clickButton').addEventListener('click', function () {
                // Update the user's points locally
                var pointsElement = document.getElementById('jCoins');
                if (pointsElement.innerText === "null" || pointsElement.innerText === "undefined" || pointsElement.innerText === "NaN" || pointsElement.innerText === "0" || pointsElement.innerText === "") {
                    pointsElement.innerText = userData.points;
                }
                var currentPoints = parseInt(pointsElement.innerText);
                var tostiElement = document.getElementById('clickButton');
                pointsElement.innerText = currentPoints + 1;
                // Check if the user data is null
                if (userData === null) {
                    // Show the registration form
                    document.getElementById('registerForm').style.display = 'block';
                } else {
                    // Send a request to the API to increase the user's points
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', '/api/v1/click', true);
                    xhr.setRequestHeader('Content-Type', 'application/json');
                    xhr.send();


                }
                tostiElement.style.width = '145px';
                setTimeout(function () {
                    tostiElement.style.width = '150px';
                }, 100);
            });

            document.getElementById('shopButton').addEventListener('click', function () {
                var shopDiv = document.getElementById('shopDiv');
                if (shopDiv.style.display === 'none') {
                    shopDiv.style.display = 'block';
                } else {
                    shopDiv.style.display = 'none';
                }
            });

            document.getElementById('buyAutoClicker').addEventListener('click', function () {
                var itemName = 'Auto Clicker'; // Update this to the name of the item

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
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            });

            setInterval(() => {
                if (inventoryData === undefined || !inventoryData[1]) {
                    return;

                } else {
                    inventoryData.forEach(item => {
                        if (item.itemName === "Auto Clicker") {
                            var amount = 0.1 * item.itemCount;
                            var newPoints = parseInt(document.getElementById('jCoins').innerText) + amount;
                            document.getElementById('jCoins').innerText = newPoints;
                        }
                    });
                }
            }, 1000);

            window.onbeforeunload = function () {
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
            };

            function calculateCost(baseCost, amount) {
                return baseCost * Math.pow(1.15, amount);
            }
        </script>

        <style>
            #jCoinsPicture {
                width: 50px;
                height: 50px;
                size: 10%;
                display: inline;
                position: relative;
                right: 10px;
                top: 10px;
            }

            #jCoins {
                display: inline;
                font-size: 50px;
                right: 10px;
                top: 5px;
                position: relative;
            }

            #navBar {
                background-color: #696969;
                padding-left: 15px;
                padding-top: 2px;
                height: 150px
            }

            body {
                font-family: Arial, sans-serif;
                overflow: hidden;
            }

            #leftBox {
                position: absolute;
                left: 0;
                width: 200px;
                height: 100vh;
                border-right: 3px solid black;
                display: flex;
                justify-content: center;
                align-items: center;
            }

            #clickButton {
                width: 150px;
                height: auto;
            }

            #startingText {
                position: relative;
                left: 200px;
                padding: 20px;
            }

            .shopItem {
                display: flex;
                align-items: center;
                border: 1px solid black;
                padding: 10px;
                margin-bottom: 10px;
            }

            .shopItem img {
                width: 50px;
                height: 50px;
                margin-right: 10px;
            }
        </style>
</body>

</html>