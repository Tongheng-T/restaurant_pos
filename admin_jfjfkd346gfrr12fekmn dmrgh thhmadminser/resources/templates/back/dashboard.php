<?php



if ($_SESSION['useremail'] == ""  or $_SESSION['role'] == "User") {

  header('location:../');
}




$select = query("SELECT sum(user_id) as viw , count(user_id) as invoice from tbl_user");
confirm($select);
$row = $select->fetch_object();

$total_movie = $row->invoice;

$viewss = $row->viw;

$select = query("SELECT count(id) as cate from tbl_service");
confirm($select);
$row = $select->fetch_object();
$total_category = $row->cate;



// $select_sub = query("SELECT count(cat_id) as catesub from sub_categories");
// confirm($select_sub);

// $roww = $select_sub->fetch_object();

// $total_category_sub = $roww->catesub;




?>





<main>
  <h1>Analytics</h1>
  <!-- Analyses -->
  <div class="analyse">
    <div class="sales">
      <div class="status">
        <div class="info">
          <h3>Total Movie</h3>
          <h1><?php echo $total_movie; ?></h1>
        </div>
        <div class="progresss">
          <svg>
            <circle cx="38" cy="38" r="36"></circle>
          </svg>
          <div class="percentage">
            <p>+81%</p>
          </div>
        </div>
      </div>
    </div>
    <div class="visits">
      <div class="status">
        <div class="info">
          <h3>TOTAL CATEGORY</h3>
          <h1><?php echo $total_category; ?></h1>
        </div>
        <div class="progresss">
          <svg>
            <circle cx="38" cy="38" r="36"></circle>
          </svg>
          <div class="percentage">
            <p>-48%</p>
          </div>
        </div>
      </div>
    </div>
    <div class="searches">
      <div class="status">
        <div class="info">
          <h3>TOTAL VIEWS</h3>
          <h1><?php echo $viewss; ?></h1>
        </div>
        <div class="progresss">
          <svg>
            <circle cx="38" cy="38" r="36"></circle>
          </svg>
          <div class="percentage">
            <p>+21%</p>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- End of Analyses -->

  <!-- New Users Section -->
  <div class="new-users">
    <h2>New Users</h2>
    <div class="user-list"  id="user_grid">
      <?php
      $time = new DateTime('now', new DateTimeZone('Asia/bangkok'));
      $datee =  $time->format('Y-m-d H:i:s');
      $time=time();
      $select = query("SELECT * from tbl_admin ");
      confirm($select);

      while ($row = $select->fetch_assoc()) {
        extract($row);
        $date = date($row['last_login']);
        $timeago = timeago($date);
        $status = $timeago;
        $class = "text-danger";
        
        if ($row['login_online'] > $time){
          $status = 'Online';
          $class = "text-success";
        }
      ?>

        <div class="user">
          <img src="../productimages/user/<?php echo $row['img'] ?>">
          <h2><?php echo $row['username'] ?></h2>
          <p class="<?php echo $class?>"><i class="fas fa-signal"></i> <?php echo $status?></p>
        </div>
      <?php } ?>
      <div class="user">
        <a href="itemt?registration">
        <img src="../productimages/plus.png">
        <h2>More</h2>
        <p>New User</p></a>
      </div>
    </div>
  </div>
  <!-- End of New Users Section -->

  <!-- Recent Orders Table -->
  <div class="recent-orders">
    <h2>Recent movies</h2>
    <table>
      <thead>
        <tr>
          <th>Id</th>
          <th>Course Name</th>
          <th>Number Payment</th>
          <th>Img</th>
          <th>Date</th>
          <th>Num Month</th>
          <th>Num Day</th>
          <th>Num USER</th>
          <th>Alert</th>
        </tr>
      </thead>
      <tbody>

        <?php

        $select = query("SELECT * from tbl_payment order by id DESC LIMIT 5");
        confirm($select);

        while ($row = $select->fetch_object()) {

          echo '
    <tr>
    <td>' . $row->id . '</td>
    <td><span class="badge badge-dark">' . $row->user_name . '</td></span>
    
    <td><span class="badge badge-success">' . $row->numberidpay . '</td></span>
    <td>  <img  src="../../resources/images/userpay/'.$row->img.'" alt="" style="width: 100px;"></td>
    <td>'.$row->date.'</td>
    <td>'.$row->num_month.'</td>
    <td>'.$row->tim.'</td>
    <td>'.$row->aus.'</td>
    <td>'.$row->alert.'</td>  
    ';
        }
        ?>
      </tbody>
    </table>
    <a  href="itemt?dashboardall">Show All</a>
  </div>
  <!-- End of Recent Orders -->

</main>

