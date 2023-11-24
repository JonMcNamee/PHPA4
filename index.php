<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Martin McNamee - A4</title>
    <link rel="stylesheet" href="main.css">
    <script src="main.js"></script>
</head>
<body>
    <h1>Assignment 4 - CRUD App</h1>

    <!--buttons -->
    <button id="btnLoad">Load Items</button>

    <div id="buttonPanel">
        <button id="btnAdd">Add</button>
        <button id="btnDelete" disabled>Delete</button>
        <button id="btnUpdate" disabled>Update</button>
    </div>

    <!-- appears when update/add is clicked-->
    <div id="addUpdatePanel">

        <div class="formLine">
        <label for="artistID">ID:</label>
        <input id="artistID" type="number" min="1">
        </div>

        <div class="formLine">
        <label for="artistName">Name:</label>
        <input id="artistName">
        </div>

        <div class="formLine">
        <label for="genre">Genre:</label>
        <input id="genre">
        </div>

        <div class="formLine">
        <label for="monthlyListeners">Monthly Spotify Listeners:</label>
        <input id="monthlyListeners" type="number" min="0">
        </div>

        <button id="btnCancel">Cancel</button>
        <button id="btnDone">Done</button>
    </div>

    
    <div id="outputTable">
        <!-- main.JS will fill this in-->
    </div>

</body>
</html>