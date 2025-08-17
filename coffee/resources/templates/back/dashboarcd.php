<?php

$select = query("SELECT order_date , total  from tbl_invoice  group by order_date LIMIT 50");
confirm($select);
$ttl = [];
$date = [];

while ($row = $select->fetch_assoc()) {
  extract($row);

  $ttl[] = $total;
  $date[] = $order_date;
}

// echo json_encode($total);

?>

<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="row">


      <div class="col-md-12">

        <!-- /.card -->
        <!-- BAR CHART -->
        <div class="card card-success">
          <div class="card-header">
            <h3 class="card-title">Bar Chart</h3>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
              <button type="button" class="btn btn-tool" data-card-widget="remove">
                <i class="fas fa-times"></i>
              </button>
            </div>
          </div>
          <div class="card-body">
            <div class="chart">
              <canvas id="earningbydate" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
            </div>
          </div>
          <!-- /.card-body -->
        </div>

      </div>
      <!-- /.col (RIGHT) -->
    </div>
    <!-- /.row -->
  </div><!-- /.container-fluid -->
</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->




<!-- ChartJS -->
<script src="../plugins/chart.js/Chart.min.js"></script>

<script>
  $(function() {

    var ctx = document.getElementById('earningbydate').getContext('2d');

    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: <?php echo json_encode($date); ?>,
        datasets: [{
          label: 'Total Earning',
          backgroundColor: 'rgb(255,99,132)',
          borderColor: 'rgb(255,99,132)',
          data: <?php echo json_encode($ttl); ?>
        }]
      },
      options: {
        scales: {
          y: {
            beginAtZero: true

          }
        }
      }
    });



  })
</script>
</body>

</html>