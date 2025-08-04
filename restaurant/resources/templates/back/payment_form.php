<?php
if ($_SESSION['useremail'] == ""  or $_SESSION['role'] == "User") {
  header('location:../');
}
display_message();
add_expense();
update_expense();
delete_expense();

?>

<div class="content-header">
  <div class="container-fluid">
    <h1 class="m-0">ចំណាយ</h1>
  </div>
</div>

<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12">

        <!-- Button បើក Modal Add -->
        <div class="mb-3 text-right">
          <button type="button" class="btn btn-danger btn-lg" data-toggle="modal" data-target="#expenseModal">
            <i class="fas fa-plus"></i> បញ្ចូលចំណាយថ្មី
          </button>
        </div>

        <!-- Modal ADD -->
        <div class="modal fade" id="expenseModal" tabindex="-1" aria-labelledby="expenseModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="expenseModalLabel">បញ្ចូលចំណាយថ្មី</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>

              <form action="" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                  <div class="row">
                    <!-- Left Column -->
                    <div class="col-md-6">

                      <!-- Expense Name -->
                      <div class="form-group">
                        <label for="txt_expense_name">ប្រភេទចំណាយ <span class="text-danger">*</span></label>
                        <input type="text" id="txt_expense_name" class="form-control" placeholder="ឧ. ទិញសម្ភារៈ"
                          name="txt_expense_name" autocomplete="off" required>
                      </div>

                      <!-- Expense Category -->
                      <div class="form-group">
                        <label for="txt_expense_category">ចំណាយក្នុងប្រភេទ <span class="text-danger">*</span></label>
                        <select class="form-control" id="txt_expense_category" name="txt_expense_category" required>
                          <option value="" disabled selected>ជ្រើសរើសប្រភេទ</option>
                          <option value="ingredient">គ្រឿងផ្សំ</option>
                          <option value="salary">ប្រាក់ខែ</option>
                          <option value="electricity">អគ្គិសនី/ទឹក</option>
                          <option value="other">ផ្សេងៗ</option>
                        </select>
                      </div>

                      <!-- Date -->
                      <div class="form-group">
                        <label for="txt_date">ថ្ងៃចំណាយ <span class="text-danger">*</span></label>
                        <input type="date" id="txt_date" class="form-control" name="txt_date" required>
                      </div>

                    </div>

                    <!-- Right Column -->
                    <div class="col-md-6">

                      <!-- Amount -->
                      <div class="form-group">
                        <label for="txt_amount">ចំនួនទឹកប្រាក់ <span class="text-danger">*</span></label>
                        <input type="number" min="0" step="any" id="txt_amount" class="form-control"
                          placeholder="បញ្ចូលចំនួនប្រាក់" name="txt_amount" autocomplete="off" required>
                      </div>

                      <!-- Description -->
                      <div class="form-group">
                        <label for="txt_description">ពិពណ៌នាផ្សេងៗ</label>
                        <textarea id="txt_description" class="form-control" placeholder="បញ្ចូលព័ត៌មានបន្ថែម (បើមាន)"
                          name="txt_description" rows="4"></textarea>
                      </div>

                      <!-- Receipt Image -->
                      <div class="form-group">
                        <label for="txt_receipt">ភ្ជាប់រូបវិក្កយបត្រ (បើមាន)</label>
                        <input type="file" id="txt_receipt" class="form-control-file" name="txt_receipt">
                        <small class="form-text text-muted">ប្រភេទ: jpg, jpeg, png, pdf (Max: 2MB)</small>
                      </div>

                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">បិទ</button>
                  <button type="submit" class="btn btn-danger" name="btnsave_expense">
                    <i class="fas fa-save"></i> រក្សាទុកចំណាយ
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>

        <!-- Modal EDIT -->
        <div class="modal fade" id="editExpenseModal" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header bg-success text-white">
                <h5 class="modal-title">កែប្រែចំណាយ</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                  <span>&times;</span>
                </button>
              </div>

              <form action="" method="post" enctype="multipart/form-data">
                <input type="hidden" name="expense_id" id="edit_id">
                <div class="modal-body">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>ប្រភេទចំណាយ *</label>
                        <input type="text" name="edit_expense_name" id="edit_name" class="form-control" required>
                      </div>

                      <div class="form-group">
                        <label>ប្រភេទ *</label>
                        <select class="form-control" name="edit_expense_category" id="edit_category" required>
                          <option value="ingredient">គ្រឿងផ្សំ</option>
                          <option value="salary">ប្រាក់ខែ</option>
                          <option value="electricity">អគ្គិសនី/ទឹក</option>
                          <option value="other">ផ្សេងៗ</option>
                        </select>
                      </div>

                      <div class="form-group">
                        <label>ថ្ងៃចំណាយ *</label>
                        <input type="date" name="edit_date" id="edit_date" class="form-control" required>
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-group">
                        <label>ចំនួន *</label>
                        <input type="number" name="edit_amount" id="edit_amount" class="form-control" required>
                      </div>

                      <div class="form-group">
                        <label>ពិពណ៌នា</label>
                        <textarea name="edit_description" id="edit_description" class="form-control"></textarea>
                      </div>

                      <div class="form-group">
                        <label>វិក្កយបត្រ (ថ្មី)</label>
                        <input type="file" name="edit_receipt" class="form-control-file">
                      </div>
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">បិទ</button>
                  <button type="submit" name="btnupdate_expense" class="btn btn-success">កែប្រែ</button>
                </div>
              </form>
            </div>
          </div>
        </div>

        <!-- Table -->
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
                  <th>សកម្មភាព</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $aus = $_SESSION['aus'];
                $result = query("SELECT * FROM tbl_expense WHERE aus='$aus' ORDER BY expense_date DESC");
                confirm($result);

                $i = 1;
                $categories = [
                  'ingredient'   => 'គ្រឿងផ្សំ',
                  'salary'       => 'ប្រាក់ខែ',
                  'electricity'  => 'អគ្គិសនី/ទឹក',
                  'other'        => 'ផ្សេងៗ'
                ];
                while ($row = $result->fetch_assoc()) {
                  echo "<tr>
                          <td class='text-center'>{$i}</td>
                          <td>{$row['expense_name']}</td>
                          <td>" . $categories[$row['expense_category']] . "</td>
                          <td>{$row['expense_date']}</td>
                          <td class='text-right'>" . number_format($row['amount'], 2) . "</td>
                          <td>{$row['description']}</td>
                          <td class='text-center'>";
                  if (!empty($row['receipt_path'])) {
                    echo "<a href='../productimages/receipts/{$row['receipt_path']}' target='_blank' class='btn btn-info btn-sm'>View</a>";
                  } else {
                    echo "-";
                  }
                  echo "</td>
                        <td class='text-center'>
                          <button class='btn btn-sm btn-success btnEdit'
                            data-id='{$row['id']}'
                            data-name='{$row['expense_name']}'
                            data-cat='{$row['expense_category']}'
                            data-date='{$row['expense_date']}'
                            data-amount='{$row['amount']}'
                            data-desc='{$row['description']}'>Edit</button>
                          
                          <a href='itemt?expense&delete_expense={$row['id']}' class='btn btn-sm btn-danger' onclick='return confirm(\"Delete this?\")'>Delete</a>
                        </td>
                      </tr>";
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

<script>
  // បំពេញ Data ចូល Modal Edit
  document.querySelectorAll('.btnEdit').forEach(btn => {
    btn.addEventListener('click', function() {
      document.getElementById('edit_id').value = this.dataset.id;
      document.getElementById('edit_name').value = this.dataset.name;
      document.getElementById('edit_category').value = this.dataset.cat;
      document.getElementById('edit_date').value = this.dataset.date;
      document.getElementById('edit_amount').value = this.dataset.amount;
      document.getElementById('edit_description').value = this.dataset.desc;
      $('#editExpenseModal').modal('show');
    });
  });
</script>