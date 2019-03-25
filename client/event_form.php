
<div class="event-form">
    <h3>Request Events Form</h3>

    <p>Fill in form correctly</p>

    <span class="help-block text-center"><?php echo $event_form_error; ?></span>

    <form action="client_page.php" method="post">
        <div class="">
            <label>Name:</label>
            <input type="text" name="event_name" class="input" placeholder="Event Name"
                   value="<?php echo $event_name; ?>">
        </div>
        <div class="">
            <label>Location:</label>
            <input type="text" name="event_location" class="input" placeholder="Location"
                   value="<?php echo $event_location; ?>">
        </div>
        <div class="">
            <label>Date: </label><span><?php echo $event_date; ?></span>
            <input type="date" name="event_date" class="input" placeholder="Due Date"
                   value="<?php echo $event_date; ?>">
        </div>
        <div class="">
            <label>Number of People:</label>
            <input type="number" name="event_people" class="input" placeholder="# of People"
                   value="<?php echo $event_people; ?>">
        </div>
        <div class="">
            <label>Budgeted Cost (KES):</label>
            <input type="text" name="event_costs" class="input" placeholder="Your Budget"
                   value="<?php echo $event_costs; ?>">
        </div>

        <input type="hidden" name="event_form" value="event_form">

        <?php if ($form_submitted === "edit") { ?>
            <input type="hidden" name="form_submitted" value="form_edit">
            <input type="hidden" name="event_id" value="<?php echo $event_id?>">
            <input type="hidden" name="event_date_edit" value="<?php echo $event_date?>">
        <?php } else { ?>
            <input type="hidden" name="form_submitted" value="form_new">
        <?php } ?>

        <div class="">
            <input type="submit" class="btn btn-success input" value="Request Event">
        </div>
    </form>
</div>
