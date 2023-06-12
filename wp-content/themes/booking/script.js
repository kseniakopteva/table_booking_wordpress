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

        num = dataArr["num"];
        dateStr = dataArr["date"];
        timeStr = dataArr["time"];

        date = new Date(dataArr["date"].replace("-", "/"));
        s = dateStr + "T" + timeStr;
        datetime = new Date(s);

        today = new Date();
        today2 = new Date(today.toString());

        todayWithoutHours = today2.setHours(0, 0, 0, 0);

        if (!num || num <= 0) {
            document.querySelector("#num").focus();
            document.querySelector("#outputAvailability").innerHTML = "";
            document.querySelector("#errors").innerHTML = "Enter valid number of people.";
            return;
        }

        if (!dateStr || new Date(date).getTime() < new Date(todayWithoutHours)) {
            document.querySelector("#date").focus();
            document.querySelector("#outputAvailability").innerHTML = "";
            document.querySelector("#errors").innerHTML = "Enter valid date.";
            return;
        }

        // if           (----------the date chosen is the same as today---------)              (-----------------time is less then now-----------------)
        if (!timeStr || (new Date(date).getTime() === new Date(todayWithoutHours).getTime() && new Date(datetime).getTime() < new Date(today).getTime())) {
            document.querySelector("#time").focus();
            document.querySelector("#outputAvailability").innerHTML = "";
            document.querySelector("#errors").innerHTML = "Enter valid time.";
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
                document.querySelector("#outputAvailability").innerHTML = "";

                data = JSON.parse(response);

                people = "";
                if (data.num === 1) {
                    people = "person";
                } else {
                    people = "people";
                }

                if (data.ID !== null && data.num !== -1) {
                    workDay = new Date(data.dateStr).getDay();
                    currentWorkingHours = data.working_hours[workDay];

                    if (data.time < currentWorkingHours[0] || data.time > currentWorkingHours[1]) {
                        document.querySelector("#errors").innerHTML = "Restaurant is closed at this time. Please choose another.";
                        return;
                    }

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
