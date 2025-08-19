<main>
  <h1>Users</h1>
  <?php
  $selectn = query("SELECT count(user_id) as number from tbl_user ");
  $rown = $selectn->fetch_object();
  $number = $rown->number; ?>
  <div class="new-users">
    <h2>Users List <?php echo $number?></h2>
    <div class="user-list" id="user_onlin">
      <?php
      $time = new DateTime('now', new DateTimeZone('Asia/bangkok'));
      $datee =  $time->format('Y-m-d H:i:s');
      $time = time();
      $select = query("SELECT * from tbl_user ");
      confirm($select);

      while ($row = $select->fetch_assoc()) {
        extract($row);

        $aus = $row['aus'];
        $selectt = query("SELECT min(user_id) as user from tbl_user where aus= '$aus' ");
        $roww = $selectt->fetch_object();
        $user = $roww->user;
        $selectk = query("SELECT * from tbl_user where user_id = '$user'");
        $rows = $selectk->fetch_object();
        $user_id = $rows->user_id;
        
        $classs = '';
        $id = $row['user_id'];
        if ($id == $user_id) {
          $classs = 'text-warning';
        }
        $showdate = $row['date_new'];
        $new_date = date('Y-m-d');
        $datetime4 = new DateTime($new_date);
        $datetime3 = new DateTime($showdate);
        $intervall = $datetime3->diff($datetime4);
        $texttt =   $intervall->format('%a');
        $numdatee = $row['tim'] - $texttt;
        if ($numdatee >= 10) {
          $color = 'success';
        } elseif ($numdatee >= 5) {
          $color = 'warning';
        } else {
          $color = 'danger';
        }

        $date = date($row['last_login']);
        $timeago = timeago($date);
        $status = $timeago;
        $class = "text-danger";

        if ($row['login_online'] > $time) {
          $status = 'Online';
          $class = "text-success";
        }
      ?>

        <div class="user">
          <img src="../../resources/images/userpic/<?php echo $row['img'] ?>">
          <h2><?php echo $row['username'] . ' ' . $numdatee . 'day' ?></h2>
          <p class="<?php echo $classs ?>"> <i class="fas fa-id-card"></i><?php echo ' '. $aus?></p>
          <p class="<?php echo $classs ?>"> <i class="fas fa-id-card"></i><?php echo ' '. $row['useremail'] ?></p>
          <p class="text-info"> <i class="fas fa-map-pin"></i><?php echo ' '. $row['location_ip'] ?></p>
          <p class="<?php echo $class ?>"><i class="fas fa-signal"></i> <?php echo $status ?></p>
        </div>
      <?php } ?>
      <!-- <div class="user">
        <a href="itemt?registration">
        <img src="../productimages/plus.png">
        <h2>More</h2>
        <p>New User</p></a>
      </div> -->
    </div>
  </div>

</main>