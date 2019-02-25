<div class="events-admin">
    <h2>Events Section</h2>

    <div class="admin-page-actions">
        <form action="admin_page.php" method="post">
            <input type="hidden" name="admin_page_action" value="latest">

            <button class="btn btn-primary">Latest Request</button>
        </form>
        <span id="spacer"></span>
        <form action="admin_page.php" method="post">
            <input type="hidden" name="admin_page_action" value="ongoing">

            <button class="btn btn-success">Ongoing Request</button>
        </form>
        <span id="spacer"></span>
        <form action="admin_page.php" method="post">
            <input type="hidden" name="admin_page_action" value="completed">

            <button class="btn btn-edit">Completed Request</button>
        </form>
    </div>

    <div class="event-requests">
        <?php if ($latest_action) { ?>
            <div class="latest-requests">

                <div class="latest-requests-list">
                    <h3>Latest Request</h3>
                    <br>
                    <?php include "partials/latest_events.php"; ?>
                    <br>
                    <?php include "partials/view_event.php" ?>
                </div>
            </div>
        <?php } ?>

        <?php if ($ongoing_action) { ?>
            <div class="ongoing-requests">

                <div class="ongoing-requests-list">
                    <h3>Ongoing Request</h3>
                </div>
            </div>
        <?php } ?>

        <?php if ($completed_action) { ?>
            <div class="completed-requests">
                <div class="completed-requests-list">
                    <h3>Completed Request</h3>
                </div>
            </div>
        <?php } ?>
    </div>
</div>