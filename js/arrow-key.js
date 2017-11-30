document.addEventListener("keyup", function(e) {
    var key = e.which||e.keyCode;
    switch(key) {
        //left arrow
        case 37:
            document.getElementById("prevLink").click();
            break;
        // up arrow
        case 38: 
            document.getElementById("upLink").click();
            break;
        // right arrow
        case 39:
            document.getElementById("nextLink").click();
            break;
        // down arrow
        case 40:
            document.getElementById("downLink").click();
            break;
        // enter key
        case 13:
            document.getElementById("enterLink").click();
            break;
        // backspace key
        case 8:
            document.getElementById("backLink").click();
            break;
    }
});
