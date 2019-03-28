<div class="admin-view-event">
    <h3>Event Description</h3>

    <div>
        <table cellspacing="0" cellpadding="0">
            <tr>
                <td>Name</td>
                <td><?php echo $name ?></td>
            </tr>

            <tr>
                <td>Location</td>
                <td><?php echo $location ?></td>
            </tr>

            <tr>
                <td>Date</td>
                <td><?php echo $date ?></td>
            </tr>

            <tr>
                <td># of People</td>
                <td><?php echo $people_count ?></td>
            </tr>

            <tr>
                <td>Original Budget</td>
                <td>KES <?php echo $total_cost ?></td>
            </tr>

            <tr>
                <td>Budget Balance</td>
                <td>KES <?php echo $total_bal ?></td>
            </tr>

            <tr>
                <td>Client</td>
                <td><?php echo $full_name ?></td>
            </tr>

            <tr>
                <th><?php if ($status_id == 2 || $status_id == 3) { ?> Actions <?php } ?></th>
                <td>
                    <div class="admin-view-event-action">
                        <?php if ($status_id == 3) { ?>
                            <form action="" method="post">
                                <input type="hidden" name="view_event_action" value="accept">

                                <input type="hidden" name="event_id" value="<?php echo $id; ?>">

                                <button class="btn btn-success">Accept</button>
                            </form>
                            <form action="" method="post">
                                <input type="hidden" name="view_event_action" value="reject">

                                <input type="hidden" name="event_id" value="<?php echo $id; ?>">

                                <button class="btn btn-delete">Reject</button>
                            </form>
                        <?php } elseif ($status_id == 2) { ?>
                            <form action="" method="post">
                                <input type="hidden" name="view_event_action" value="add_sub_task">

                                <input type="hidden" name="event_id" value="<?php echo $id; ?>">

                                <button class="btn btn-edit">Add Subtask</button>
                            </form>
                            <?php if ($total_bal == 0) { ?>
                                <form action="" method="post">
                                    <input type="hidden" name="event_sub_task_actions" value="request_add_funds">

                                    <input type="hidden" name="event_id" value="<?php echo $id; ?>">
                                    <input type="hidden" name="client_id" value="<?php echo $user_id; ?>">

                                    <button class="btn btn-primary">Request Funds</button>
                                </form>
                            <?php } ?>
                            <form action="" method="post">
                                <input type="hidden" name="view_event_action" value="done">

                                <input type="hidden" name="event_id" value="<?php echo $id; ?>">

                                <button class="btn btn-success">Event Completed</button>
                            </form>
                            <?php if (sizeof($events_task_data) > 0) { ?>
                                <form action="../../backend/export.php" method="post">
                                    <input type="hidden" name="export_action" value="print_expenses">

                                    <input type="hidden" name="event_id" value="<?php echo $id; ?>">
                                    <input type="hidden" name="client_id" value="<?php echo $user_id; ?>">

                                    <button class="btn btn-primary">Print Expenditure</button>
                                </form>
                            <?php } ?>
                        <?php } ?>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <br>

    <!--only for ongoing events-->
    <?php if ($status_id == 2) { ?>

        <h3>Event Sub Tasks</h3>

        <?php if ($sub_task_form && !$event_sub_task) { ?>
            <div>
                <!-- form to add the tasks -->
                <form id="edit_form" action="../../admin/admin_page.php" method="post">
                    <h3>Add SubTask</h3>

                    <div class="edit-form-group">
                        <label for="name">Name</label>
                        <input type="text" tabindex="3" id="name" name="name"
                               required/>
                    </div>

                    <div class="edit-form-group">
                        <label for="cost">Cost</label>
                        <input type="number" tabindex="3" id="cost" name="cost" required>
                    </div>

                    <div class="edit-form-group">
                        <label for="description">Description</label>
                        <input type="text" tabindex="3" id="description" name="description" required>
                    </div>

                    <input type="hidden" name="event_id" value="<?php echo $id; ?>">
                    <input type="hidden" name="task_sum" value="<?php echo $sum; ?>">
                    <input type="hidden" name="event_sub_task_actions" value="add_sub_task">

                    <div class="edit-form-group">
                        <button name="submit" type="submit" id="contact-submit" class="edit_form_btn"
                                data-submit="...Sending">Submit Details
                        </button>
                    </div>
                </form>
            </div>
        <?php } else { ?>

            <?php

            if (sizeof($events_task_data) > 0) { ?>
                <div>
                    <!--table to list all the sub-task-->
                    <table style="width:100%">
                        <tr>
                            <th class="">Name</th>
                            <th class="">Description</th>
                            <th class="">Cost (KES)</th>
                            <th class="">Actions</th>
                        </tr>

                        <?php foreach ($events_task_data as $row) { ?>
                            <tr>
                                <td><?php echo $row['name']; ?></td>
                                <td><?php echo $row['description']; ?></td>
                                <td><?php echo $row['cost']; ?></td>
                                <td style="display: none;"><?php setSum($row['cost']) ?></td>
                                <td>
                                    <form action="" method="post">
                                        <input type="hidden" value="<?php echo $row['id']; ?>" name="task_id"/>
                                        <input type="hidden" name="event_id" value="<?php echo $id; ?>">

                                        <input type="hidden" name="event_sub_task_actions" value="show_del_sub_task">

                                        <button class="btn btn-delete">DELETE</button>
                                    </form>
                                </td>
                            </tr>
                            <?php
                        } ?>

                        <tr>
                            <th>Total</th>
                            <th></th>
                            <th><?php echo $sum; ?></th>
                            <th></th>
                        </tr>
                    </table>
                </div>
            <?php } else { ?>

                <p>No Sub-Tasks available</p>

            <?php }
        } ?>

        <?php if ($edit_task && !$event_sub_task) { ?>
            <h3>DELETE SUBTASK</h3>

            <br>

            <p>Are you sure as this will delete the subtask and affect Budget</p>

            <div class="edit-form-group">

                <form action="" method="post" style="float: left;width: 50%;">
                    <input type="hidden" name="event_sub_task_actions" value="cancel_sub_task_delete">
                    <input type="hidden" name="event_id" value="<?php echo $id; ?>">

                    <button name="submit" type="submit" id="contact-submit" class="cancel_form_btn"
                            data-submit="...Sending">NO
                    </button>
                </form>

                <form action="" method="post" style="float: left;width: 50%;">
                    <input type="hidden" name="event_sub_task_actions" value="sub_task_delete">
                    <input type="hidden" name="event_id" value="<?php echo $id; ?>">
                    <input type="hidden" name="sub_task_id" value="<?php echo $task_id; ?>">

                    <button name="submit" type="submit" id="contact-submit" class="delete_form_btn"
                            data-submit="...Sending">YES
                    </button>
                </form>
            </div>
        <?php } ?>

    <?php } ?>

    <!--only for ongoing events-->
    <?php if ($status_id == 1) { ?>
        <h3>SubTasks</h3>
        <?php

        if (sizeof($events_task_data) > 0) { ?>
            <div>
                <!--table to list all the sub-task-->
                <table style="width:100%">
                    <tr>
                        <th class="">Name</th>
                        <th class="">Description</th>
                        <th class="">Cost (KES)</th>
                    </tr>

                    <?php foreach ($events_task_data as $row) { ?>
                        <tr>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['description']; ?></td>
                            <td><?php echo $row['cost']; ?></td>
                            <td style="display: none;"><?php setSum($row['cost']) ?></td>
                        </tr>
                        <?php
                    } ?>

                    <tr>
                        <th>Total</th>
                        <th></th>
                        <th><?php echo $sum; ?></th>
                        <th></th>
                    </tr>
                </table>
            </div>
        <?php } else { ?>

            <p>No Sub-Tasks available</p>

        <?php }
    } ?>

</div>
