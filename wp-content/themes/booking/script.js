// Get the modal
window.onload = function () {
    AOS.init();
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
            document.querySelector("#errors").innerHTML = "Enter number of people!!!";
            return;
        }

        if (!dataArr["date"]) {
            document.querySelector("#outputAvailability").innerHTML = "";
            document.querySelector("#errors").innerHTML = "Enter date!!!";
            return;
        }

        if (!dataArr["time"]) {
            document.querySelector("#outputAvailability").innerHTML = "";
            document.querySelector("#errors").innerHTML = "Enter time!!!";
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
                document.querySelector("#errors").innerHTML = "";
                //console.log(response);
                if (JSON.parse(response).ID !== null && JSON.parse(response).num !== -1) {
                    if (dataArr["num"] === JSON.parse(response).num) {
                        document.querySelector("#outputAvailability").innerHTML = "There is a table available!<br>Click 'Submit reservation' to send the reservation for " + JSON.parse(response).date + " at " + JSON.parse(response).time;
                    } else {
                        document.querySelector("#outputAvailability").innerHTML = "Only a table with seats for " + JSON.parse(response).num + " people is available. <br>Click 'Submit reservation' to send the reservation for " + JSON.parse(response).date + " at " + JSON.parse(response).time;
                    }
                    document.querySelector("#reserveSubmitBtn").disabled = false;
                } else {
                    if (JSON.parse(response).num === -1) document.querySelector("#outputAvailability").innerHTML = "There are no tables available for this amount of people.";
                    else document.querySelector("#outputAvailability").innerHTML = "There are no tables available!";
                }
            },
        });
    });
};
