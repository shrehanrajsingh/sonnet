<link rel="stylesheet" href="./styles.css">
<script>
    function snack_back(txt) {
    // Get the snackbar DIV
    var c = document.createElement("div");
    c.id = "snackbar";
    c.innerHTML = txt;
    document.body.appendChild(c);
    c.className = "show snb";
    setTimeout(function(){ c.className = c.className.replace("show", ""); }, 3000);
    }
    const isToday = (date) => {
    const today = new Date()
    return date.getDate() === today.getDate() &&
        date.getMonth() === today.getMonth() &&
        date.getFullYear() === today.getFullYear();
    };

    // var x = document.getElementById("snackbar");

    // // Add the "show" class to DIV
    // x.className = "show";
    // x.innerHTML = txt;
    // // After 3 seconds, remove the show class from DIV
    // setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
    // }
</script>
<div id="snackbar"></div>