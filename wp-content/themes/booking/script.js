// Get the modal
window.onload = function () {
    document.querySelector("#checkIfAvailable").addEventListener("click", () => {
        let formData = new FormData(document.querySelector("#reservationForm"));

        let data = "";
        for (var p of formData) {
            data += p[0] + "^" + p[1] + "|";
        }
        dataArr = [];
        for (var p of formData) {
            dataArr[p[0]] = p[1];
        }

        // console.log(data);
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
                console.log(response);
                if (JSON.parse(response).ID !== null) {
                    if (dataArr["num"] === JSON.parse(response).num) {
                        document.querySelector("#outputAvailability").innerHTML = "There is a table available!<br>Click 'Submit reservation' to send the reservation for " + JSON.parse(response).date + " at " + JSON.parse(response).time;
                    } else {
                        document.querySelector("#outputAvailability").innerHTML = "Only a table with seats for " + JSON.parse(response).num + " people is available";
                    }
                    document.querySelector("#reserveSubmitBtn").disabled = false;
                } else {
                    document.querySelector("#outputAvailability").innerHTML = "There are no tables available!";
                }
            },
        });
    });
};
