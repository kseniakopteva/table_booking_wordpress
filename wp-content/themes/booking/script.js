// Get the modal
window.onload = function () {
    document.querySelector("#checkIfAvailable").addEventListener("click", () => {
        let formData = new FormData(document.querySelector("#reservationForm"));

        let data = "";
        for (var p of formData) {
            data += p[0] + ":" + p[1] + ";";
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
                //console.log(JSON.parse(response).ID);
                //console.log(JSON.parse(response).num);

                document.querySelector("#outputAvailability").innerHTML = '<p class="mt-3">There is a table available with seats for ' + JSON.parse(response).num + " people</p>";
                document.querySelector("#reserveSubmitBtn").disabled = false;
            },
        });
    });
};
