// Get the modal
window.onload = function () {
    document.querySelector("#checkIfAvailable").addEventListener("click", () => {
        //
        //
        //  Check if available!
        //

        document.querySelector("#outputAvailability").innerHTML = '<p class="mt-3">It is available!</p>';
        document.querySelector("#reserveSubmitBtn").disabled = false;
    });
};
