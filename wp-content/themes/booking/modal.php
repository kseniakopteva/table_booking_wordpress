<div class="modal fade" id="modalCenter" tabindex="-1" role="dialog" aria-labelledby="modalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCenterLongTitle">Reserve table</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form method="POST" action="<?php echo esc_url(admin_url('admin-post.php')) ?>" id="reservationForm">

                <div class="modal-body">

                    <div class="row">
                        <div class="col form-group">
                            <label for="name">Name</label>
                            <input class="form-control" type="text" id="name" name="name" placeholder="Enter Your Name" required>
                        </div>
                        <div class="col form-group">
                            <label for="tel">Phone</label>
                            <input class="form-control" type="tel" name="tel" id="tel" placeholder="Enter Your Phone Number" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col form-group">
                            <label for="num">Number of People</label>
                            <input class="form-control" type="text" id="num" name="num" placeholder="How many people?" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col form-group">
                            <label for="date">Date</label>
                            <input class="form-control" type="date" name="date" id="date" required>
                        </div>
                        <div class="col form-group">
                            <label for="time">Time</label>
                            <input class="form-control" type="time" name="time" id="time" required>
                        </div>
                    </div>

                    <input type="hidden" name="action" value="modal-form">

                    <input type="hidden" id="restaurantID" name="restaurantID" value="<?php echo $post->ID ?>">

                    <button type="button" id="checkIfAvailable" class="btn bk-button mt-3">Check Availability</button>

                    <div>
                        <p class="mt-3 text-danger" id="errors"></p>
                        <p class="mt-3" id="outputAvailability"></p>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="this.form.reset();">Close</button>
                    <button id="reserveSubmitBtn" type="submit" class="btn btn-primary" disabled>Submit reservation</button>
                </div>

            </form>

        </div>
    </div>
</div>