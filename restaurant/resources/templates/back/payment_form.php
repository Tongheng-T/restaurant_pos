<?php



if ($_SESSION['useremail'] == ""  or $_SESSION['role'] == "User") {

  header('location:../');
}



add_expense();

?>


<div class="content-header">
  <div class="container-fluid">
    <h1 class="m-0">បញ្ចូលចំណាយថ្មី</h1>
  </div>
</div>

<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12">

        <!-- Card Form -->
        <div class="card card-primary card-outline mb-4">
          <div class="card-header">
            <h5 class="m-0">ចំណាយថ្មី</h5>
          </div>

          <form action="" method="post" enctype="multipart/form-data" class="p-3">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>ប្រភេទចំណាយ *</label>
                  <input type="text" name="txt_expense_name" class="form-control" required>
                </div>

                <div class="form-group">
                  <label>ចំណាយក្នុងប្រភេទ *</label>
                  <select name="txt_expense_category" class="form-control" required>
                    <option value="" disabled selected>ជ្រើសរើសប្រភេទ</option>
                    <option value="ingredient">គ្រឿងផ្សំ</option>
                    <option value="salary">ប្រាក់ខែ</option>
                    <option value="electricity">អគ្គិសនី/ទឹក</option>
                    <option value="other">ផ្សេងៗ</option>
                  </select>
                </div>

                <div class="form-group">
                  <label>ថ្ងៃចំណាយ *</label>
                  <input type="date" name="txt_date" class="form-control" required>
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group">
                  <label>ចំនួនទឹកប្រាក់ *</label>
                  <input type="number" name="txt_amount" class="form-control" min="0" step="any" required>
                </div>

                <div class="form-group">
                  <label>ពិពណ៌នាផ្សេងៗ</label>
                  <textarea name="txt_description" class="form-control"></textarea>
                </div>

                <div class="form-group">
                  <label>ភ្ជាប់រូបវិក្កយបត្រ</label>
                  <input type="file" name="txt_receipt" class="form-control-file">
                </div>
              </div>
            </div>

            <div class="text-center">
              <button type="submit" class="btn btn-danger btn-lg" name="btnsave_expense">
                <i class="fas fa-save"></i> រក្សាទុកចំណាយ
              </button>
            </div>
          </form>
        </div>

        <!-- Card Data Table -->
        <div class="card card-outline card-success">
          <div class="card-header">
            <h5 class="m-0">តារាងចំណាយ</h5>
          </div>
          <div class="card-body table-responsive">
            <table class="table table-bordered table-striped">
              <thead>
                <tr class="bg-dark text-white text-center">
                  <th>#</th>
                  <th>ប្រភេទចំណាយ</th>
                  <th>Category</th>
                  <th>ថ្ងៃចំណាយ</th>
                  <th>ចំនួន</th>
                  <th>ពិពណ៌នា</th>
                  <th>វិក្កយបត្រ</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $aus = $_SESSION['aus'];
                $result = query("SELECT * FROM tbl_expense WHERE aus='$aus' ORDER BY expense_date DESC");
                confirm($result);

                $i = 1;
                while ($row = $result->fetch_assoc()) {
                  echo "<tr>
                          <td class='text-center'>{$i}</td>
                          <td>{$row['expense_name']}</td>
                          <td>{$row['expense_category']}</td>
                          <td>{$row['expense_date']}</td>
                          <td class='text-right'>" . number_format($row['amount'], 2) . "</td>
                          <td>{$row['description']}</td>
                          <td class='text-center'>";
                  if (!empty($row['receipt_path'])) {
                    echo "<a href='../resources/receipts/{$row['receipt_path']}' target='_blank' class='btn btn-info btn-sm'>View</a>";
                  } else {
                    echo "-";
                  }
                  echo "</td></tr>";
                  $i++;
                }
                ?>
              </tbody>
            </table>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>

<!-- /.content -->