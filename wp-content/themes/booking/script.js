// Get the modal
window.onload = function () {
    AOS.init();
    document.querySelector("#checkIfAvailable").addEventListener("click", () => {
        let fd = new FormData(document.querySelector("#reservationForm"));

        dataArr = [];
        let formDataStr = "";
        for (var p of fd) {
            formDataStr += p[0] + "^" + p[1] + "|";
            dataArr[p[0]] = p[1];
        }

        if (!dataArr["num"] || dataArr["num"] <= 0) {
            document.querySelector("#num").focus();
            document.querySelector("#outputAvailability").innerHTML = "";
            document.querySelector("#errors").innerHTML = "Enter valid number of people.";
            return;
        }

        // TODO: Date Validation

        //console.log(Date.parse(dataArr["date"].replace("-", "/") + " " + dataArr["date"] + " GMT"));

        if (!dataArr["date"]) {
            // || new Date(dataArr["date"]) > new Date(Date.now())) {
            document.querySelector("#date").focus();
            document.querySelector("#outputAvailability").innerHTML = "";
            document.querySelector("#errors").innerHTML = "Enter valid date.";
            return;
        }

        if (!dataArr["time"] || (new Date(dataArr["date"]) === new Date() && dataArr["time"] < Date.now())) {
            // TODO: check for WORK HOURS
            document.querySelector("#time").focus();
            document.querySelector("#outputAvailability").innerHTML = "";
            document.querySelector("#errors").innerHTML = "Enter time.";
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
                data: formDataStr,
            },
            success: function (response) {
                document.querySelector("#errors").innerHTML = "";

                data = JSON.parse(response);

                people = "";
                if (data.num === 1) {
                    people = "person";
                } else {
                    people = "people";
                }

                if (data.ID !== null && data.num !== -1) {
                    if (dataArr["num"] === data.num) {
                        output = '<span class="text-success">There is a table available!</span>' + "<br>" + "Click 'Submit reservation' to send the reservation for:<br>" + "<strong>" + data.date + " at " + data.time + " in " + data.restaurant + " for " + data.num + " " + people + "</strong>";
                    } else {
                        output =
                            '<span class="text-success">There is a table with seats for ' +
                            data.num +
                            " people available!</span>" +
                            "<br>" +
                            "Click 'Submit reservation' to send the reservation for <strong>" +
                            data.date +
                            " at " +
                            data.time +
                            " in " +
                            data.restaurant +
                            " for " +
                            data.num +
                            " people</strong>";
                    }
                    document.querySelector("#reserveSubmitBtn").disabled = false;
                } else {
                    if (data.num === -1) {
                        output = "There are no tables available for this amount of people.";
                    } else {
                        output = "There are no tables available!";
                    }
                }
                document.querySelector("#outputAvailability").innerHTML = output;
            },
        });
    });
};
