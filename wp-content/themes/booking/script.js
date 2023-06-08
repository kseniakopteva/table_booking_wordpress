// Get the modal
window.onload = function () {
    document.querySelector("#checkIfAvailable").addEventListener("click", () => {
        let formData = new FormData(document.querySelector("#reservationForm"));

        let data = "";
        for (var p of formData) {
            data += p[0] + ":" + p[1] + ";";
        }
        dataArr = [];
        for (var p of formData) {
            dataArr[p[0]] = p[1];
        }

        console.log(data);
        if (!dataArr["num"]) {
            document.querySelector("#outputAvailability").innerHTML = "";
            alert("Enter number of people!!!");
            return;
        }

        //
        //  Check if available!
        //

        jQuery.ajax({
            type: "POST",
            url: "/wp-admin/admin-ajax.php",
            dataType: "text",
            data: {
                action: "checkIfAvailable",
                data: data,
            },
            success: function (response) {
                console.log(JSON.parse(response).ID);
                console.log(JSON.parse(response).num);
                console.log(response);
                if (JSON.parse(response).ID !== null) {
                    document.querySelector("#outputAvailability").innerHTML = "There is a table available with seats for " + JSON.parse(response).num + " people";
                    document.querySelector("#reserveSubmitBtn").disabled = false;
                } else {
                    document.querySelector("#outputAvailability").innerHTML = "There are no tables available!";
                }
            },
        });
    });
};
