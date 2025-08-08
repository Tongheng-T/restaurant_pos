<?php



if ($_SESSION['useremail'] == ""  or $_SESSION['role'] == "") {

  header('location:../');
}




$select = query("SELECT sum(views) as viw , count(product_id) as invoice from products");
confirm($select);
$row = $select->fetch_object();

$total_movie = $row->invoice;

$viewss = $row->viw;

$select = query("SELECT count(cat_id) as cate from categories");
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

  <!-- End of New Users Section -->

  <!-- Recent Orders Table -->
  <div class="recent-orders hover_movie">
    <h2>Recent movies</h2>
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Movie Name</th>
          <th>View</th>
          <th>Categorie Movie</th>
          <th>Pat</th>
        </tr>
      </thead>
      <tbody>

        <?php

        $select = query("SELECT product_id,product_title,product_category_id,sum(views) as q  from products group by product_id order by views DESC ");
        confirm($select);

        while ($row = $select->fetch_object()) {

          

          echo '
    <tr>
    <td>' . $row->product_id . '</td>
    <td><span class="badge badge-warning">' . $row->product_title . '</td></span>
    
    <td><span class="badge badge-success">' . $row->q . '</td></span>
    <td><span class="badge badge-primary">' . show_category_name($row->product_category_id) . '</td>
    <td><span class="badge badge-danger">' . $minpat = show_pat($row->product_id) . '</td></tr>
    ';
        }
        ?>
      </tbody>
    </table>
    <!-- <a href="#">Show All</a> -->
  </div>
  <!-- End of Recent Orders -->

</main>