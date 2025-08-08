<?php 

if ($_SESSION['useremail'] == ""  or $_SESSION['role'] == "") {

    header('location:../');
  }
?>
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

        <a href="itemt?addmovies" class="<?php actr("addmovies"); ?>">
            <span class="material-icons-sharp">
                post_add
            </span>
            <h3>Add Movies</h3>
        </a>
        <a href="itemt?addpatmovies" class="<?php actr("addpatmovies"); ?>">
            <span class="material-icons-sharp">
                create_new_folder
            </span>
            <h3>Add Pat Movies</h3>
        </a>

        <a href="#" class="<?php actr("y"); ?>">
            <span class="material-icons-sharp">
                receipt_long
            </span>
            <h3>History</h3>
        </a>
        <a href="itemt?insights" class="<?php actr("insights"); ?>">
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
        
        <a href="itemt?logout">
            <span class="material-icons-sharp">
                logout
            </span>
            <h3>Logout</h3>
        </a>
    </div>
</aside>