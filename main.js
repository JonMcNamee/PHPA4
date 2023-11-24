let addOrUpdate; //tracks whether user clicked add or update button

window.onload = function () {
    addEventListeners();
    hideUpdatePanel();
}

//adds event listeners for all buttons and table
function addEventListeners() {
    //buttons
    document.querySelector("#btnAdd").addEventListener("click", addArtist);
    document.querySelector("#btnDelete").addEventListener("click", deleteArtist);
    document.querySelector("#btnUpdate").addEventListener("click", updateArtist);
    document.querySelector("#btnDone").addEventListener("click", processForm);
    document.querySelector("#btnCancel").addEventListener("click", hideUpdatePanel);
    document.querySelector("#btnLoad").addEventListener("click", getAllArtists);
    //table
    document.querySelector("#outputTable").addEventListener("click", handleRowClick);
}
//changes class level variable, clears update panel, & shows update panel
function addArtist() {
    addOrUpdate = "add";
    clearSelections();
    setDeleteUpdateButtonState(false);
    clearUpdatePanel();
    showUpdatePanel();
}

//clears update panel
function clearUpdatePanel() {
    document.querySelector("#artistID").value = "";
    document.querySelector("#artistName").value = "";
    document.querySelector("#genre").value = "";
    document.querySelector("#monthlyListeners").value = "";
}

//shows update panel
function showUpdatePanel() {
    document.querySelector("#addUpdatePanel").classList.remove("hidden");
}

//handles delete artist functionality from db
function deleteArtist() {
    clearUpdatePanel();
    hideUpdatePanel();
    let row = document.querySelector(".selected"); // we know there's only one
    let id = Number(row.querySelectorAll("td")[0].innerHTML);

    // AJAX
    let url = "api/deleteArtist.php/?artistID=" + id; // "?param=value"
    let xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            let resp = xhr.responseText;
            if (resp === "1") {
                alert("Artist deleted.");
            } else if (resp === "0") {
                alert("Artist was not deleted.");
            } else {
                alert("Server Error!");
            }
            getAllArtists();
        }
    };
    xhr.open("GET", url, true);
    xhr.send();
}
//handles update artist functionality on db
function updateArtist() {
    addOrUpdate = "update";
    // Get selected row (only 1)
    let selectedRow = document.querySelector(".selected");
    if (selectedRow) {
        // get data
        updateInputFields(selectedRow);

        showUpdatePanel();
    } else {
        alert("Please select an artist to update.");
    }
}

function updateInputFields(selectedRow) {
    // get data
    let artistID = selectedRow.querySelector("td:nth-child(1)").innerHTML;
    let artistName = selectedRow.querySelector("td:nth-child(2)").innerHTML;
    let genre = selectedRow.querySelector("td:nth-child(3)").innerHTML;
    let monthlyListeners = selectedRow.querySelector("td:nth-child(4)").innerHTML;

    // Populate update form fields
    document.querySelector("#artistID").value = artistID;
    document.querySelector("#artistName").value = artistName;
    document.querySelector("#genre").value = genre;
    document.querySelector("#monthlyListeners").value = monthlyListeners;
}

//processes data when user clicks Done button
function processForm() {
    console.log("OK");
    // Get data from the form and build an object.
    let id = Number(document.querySelector("#artistID").value);
    let name = document.querySelector("#artistName").value;
    let genre = document.querySelector("#genre").value;
    let monthlyListeners = Number(document.querySelector("#monthlyListeners").value);

    let obj = {
        artistID: id,
        artistName: name,
        genre: genre,
        monthlySpotifyListeners: monthlyListeners
    };

    //AJAX call add/updates record in db
    let url = addOrUpdate === "add" ? "api/addArtist.php" : "api/updateArtist.php";
    let xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            let resp = xhr.responseText;
            console.log(resp, resp.length);
            if (resp === "1") {
                alert(
                    "Artist " + (addOrUpdate === "add" ? "added." : "updated.")
                );
            } else if (resp === "0") {
                alert(
                    "Artist NOT " +
                    (addOrUpdate === "add" ? "added." : "updated.")
                );
            } else {
                alert("Server Error!");
            }
            hideUpdatePanel();
            getAllArtists();
        }
    };
    xhr.open("POST", url, true); // must be POST because we need to send data
    xhr.send(JSON.stringify(obj));

}

//add selected class when user clicks a row
function handleRowClick(evt) {
    clearSelections();
    let panel = document.querySelector("#addUpdatePanel");
    if (evt.target.nodeName == "TD") {
        evt.target.parentElement.classList.add("selected");
        setDeleteUpdateButtonState(true);


        //hides add panel if it was used for add
        if (!panel.classList.contains("hidden") && addOrUpdate == "add") {
            hideUpdatePanel();
        }
        //otherwise if the panel was already open for update, automatically update input fields
        else if (!panel.classList.contains("hidden") && addOrUpdate == "update") {
            let selectedRow = document.querySelector(".selected");
            updateInputFields(selectedRow);
        }
    }
    else {
        setDeleteUpdateButtonState(false);
        if (!panel.classList.contains("hidden") && addOrUpdate == "update") {
            hideUpdatePanel();
        }
    }

}

//clears all selected rows by looping through them all
function clearSelections() {
    let trs = document.querySelectorAll("tr");
    for (let i = 0; i < trs.length; i++) {
        trs[i].classList.remove("selected");
    }
}

//hides update panel
function hideUpdatePanel() {
    document.querySelector("#addUpdatePanel").classList.add("hidden");
}

//retrieves all artists from getAllArtists.php
function getAllArtists() {
    let url = "api/getAllArtists.php";
    let xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                let resp = xhr.responseText;
                console.log(resp);
                if (resp.search("ERROR") >= 0) {
                    alert("oh no, something is wrong with the GET ...");
                } else {
                    buildTable(xhr.responseText);
                    setDeleteUpdateButtonState(false);
                }
            } else {
                alert("received status code " + xhr.status);
            }
        }
    };
    xhr.open("GET", url, true);
    xhr.send();
}

//builds table with artist data
function buildTable(text) {
    console.log(text);
    let arr = JSON.parse(text); // get JS Objects
    let html =
        "<table><tr><th>ID</th><th>Name</th><th>Genre</th><th>Monthly Spotify Listeners</th></tr>";
    for (let i = 0; i < arr.length; i++) {
        let row = arr[i];
        html += "<tr>";
        html += "<td>" + row.artistID + "</td>";
        html += "<td>" + row.artistName + "</td>";
        html += "<td>" + row.genre + "</td>";
        html += "<td>" + row.monthlySpotifyListeners + "</td>";
        html += "</tr>";
    }
    html += "</table>";
    let theTable = document.querySelector("#outputTable");
    theTable.innerHTML = html;
}

//toggles disabled attribute of delete/update buttons
function setDeleteUpdateButtonState(state) {

    if (state) { //add disabled
        document.querySelector("#btnDelete").removeAttribute("disabled");
        document.querySelector("#btnUpdate").removeAttribute("disabled");
    } else { //remove disabled
        document
            .querySelector("#btnDelete")
            .setAttribute("disabled", "disabled");
        document
            .querySelector("#btnUpdate")
            .setAttribute("disabled", "disabled");
    }
}