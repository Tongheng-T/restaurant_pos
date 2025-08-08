<aside>
    <div class="toggle">
        <div class="logo">
            <img src="../productimages/logo6.png">
            <h2>TH<span class="danger">Movies</span></h2>
        </div>
        <div class="close" id="close-btn">
            <span class="material-icons-sharp">
                close
            </span>
        </div>
    </div>

    <div class="sidebar">
        <a href="itemt?dashboard" class="<?php actr("dashboard"); ?>">
            <span class="material-icons-sharp">
                dashboard
            </span>
            <h3>Dashboard</h3>
        </a>
        <a href="itemt?addmovies" class="<?php actr("addmovies"); ?>">
            <span class="material-icons-sharp">
                post_add
            </span>
            <h3>Add Movies</h3>
        </a>
        <a href="itemt?payment_lis" class="<?php actr("payment_lis"); ?>">
            <span class="material-icons-sharp">
                create_new_folder
            </span>
            <h3>Payment <span class="count badge badge-warning navbar-badge" id="notifications"><?php echo num_alert() ?></span></h3>

        </a>
        <a href="itemt?payment_lis_coffee" class="<?php actr("payment_lis_coffee"); ?>">
            <span class="material-icons-sharp">
                create_new_folder
            </span>
            <h3>payment_coffee <span class="count badge badge-warning navbar-badge" id="notificationsC"><?php echo num_alert() ?></span></h3>

        </a>
        <a href="itemt?users" class="<?php actr("users"); ?>">
            <span class="material-icons-sharp">
                person_outline
            </span>
            <h3>Users</h3>
        </a>
        <a href="itemt?users_coffee" class="<?php actr("users_coffee"); ?>">
            <span class="material-icons-sharp">
                person_outline
            </span>
            <h3>Users Coffee</h3>
        </a>
        <a href="#" class="<?php actr("y"); ?>">
            <span class="material-icons-sharp">
                receipt_long
            </span>
            <h3>History</h3>
        </a>
        <a href="#" class="<?php actr("pos"); ?>">
            <span class="material-icons-sharp">
                insights
            </span>
            <h3>Analytics</h3>
        </a>
        <a href="#">
            <span class="material-icons-sharp">
                mail_outline
            </span>
            <h3>Tickets</h3>
            <span class="message-count">27</span>
        </a>
        <a href="itemt?movies_list" class="<?php actr("movies_list"); ?>">
            <span class="material-icons-sharp">
                inventory
            </span>
            <h3>Movies List</h3>
        </a>
        <a href="#">
            <span class="material-icons-sharp">
                report_gmailerrorred
            </span>
            <h3>Reports</h3>
        </a>
        <a href="#">
            <span class="material-icons-sharp">
                settings
            </span>
            <h3>Settings</h3>
        </a>
        <a href="itemt?registration">
            <span class="material-icons-sharp">
                add
            </span>
            <h3>New Login</h3>
        </a>
        <a href="itemt?logout">
            <span class="material-icons-sharp">
                logout
            </span>
            <h3>Logout</h3>
        </a>
    </div>
</aside>