//Ajax request with JQUERY ready event

$(document).ready(() => {
    document.querySelector("#button").onclick = () => {
        $.ajax({
            type: "POST",
            url: "/login.php",
            data: {"password": document.querySelector("#input").value},
            success: (data) => {
                console.log(data);
            },
            dataType: "json"
        });
    };
});
