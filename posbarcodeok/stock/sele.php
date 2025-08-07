<?php
require_once "../resources/config.php";

// ទាញ product
$products = query("SELECT pid, product, saleprice, purchaseprice, image FROM tbl_product ORDER BY product ASC");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Multiple Product Sale</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
    <style>
        @font-face {
            font-family: "tong";
            src: url(../fone/KhmerOSbattambang.ttf)format("truetype");
        }

        body {
            font-family: 'tong';
        }

        .product-img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
        }

        .profit {
            font-weight: bold;
        }

        .product-img {
            width: 100%;
            height: auto;
            max-width: 60px;
            max-height: 60px;
            object-fit: cover;
            border-radius: 8px;
        }

        @media (max-width: 1200px) {
            .product-row .col-md-2 {
                margin-bottom: 10px;
            }
        }
    </style>
</head>

<body class="hold-transition layout-top-nav">
    <div class="wrapper container-fluid">
        <div class="content-wrapper">
            <div class="content p-3">

                <h4>🛒 លក់ផលិតផលច្រើន</h4>
                <form id="saleForm">
                    <div id="productContainer"></div>

                    <div class="mt-3">
                        <button type="button" class="btn btn-primary" id="addProduct">➕ បន្ថែមផលិតផល</button>
                        <h5>Total Cost: <span id="totalCost">0.00</span></h5>
                        <h5>Total Money: <span id="totalMoney">0.00</span></h5>
                        <h5 class="float-right">សរុបចំណេញ: $<span id="totalProfit">0.00</span></h5>
                    </div>

                    <button type="submit" class="btn btn-success btn-block mt-4">✅ បញ្ចូនការលក់</button>
                </form>

            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        const products = <?= json_encode(array_map(function ($p) {
                                $p['image'] = '../productimages/' . $p['image'];
                                return $p;
                            }, mysqli_fetch_all($products, MYSQLI_ASSOC))); ?>;

        let index = 0;

        function renderProductRow(i) {
            const select = products.map(p => `
    <option value='${JSON.stringify(p)}'>${p.product}</option>
  `).join("");

            return `
    <div class="card mb-2 p-2 product-row" data-index="${i}">
      <div class="row align-items-center">
        <div class="col-md-3">
          <label>ផលិតផល</label>
          <select class="form-control product-select">
            <option value="">-- ជ្រើស --</option>
            ${select}
          </select>
        </div>
        <div class="col-md-1">
          <label>រូបភាព</label><br>
          <img src="" class="product-img img-thumbnail" id="img-${i}" style="display:none;">
        </div>
        <div class="col-md-1">
          <label>តម្លៃដើម</label>
          <input type="text" class="form-control cost" readonly>
        </div>
             <div class="col-md-1">
          <label>តម្លៃដើមt</label>
          <input type="text" class="form-control total-cost" readonly placeholder="Total Cost">

        </div>
        <div class="col-md-1">
          <label>តម្លៃលក់</label>
          <input type="number" class="form-control price">
        </div>
        <div class="col-md-1">
       <label>ចំនួន</label>
       <input type="number" class="form-control qty" value="1" min="1">
       </div>
        <div class="col-md-1">
       <label>total</label>
       <input type="text" class="form-control total" readonly placeholder="Total">
       </div>
        <div class="col-md-1">
          <label>ចំណេញ</label>
          <input type="text" class="form-control profit" readonly>
        </div>
        <div class="col-md-1">
          <label>Free</label><br>
          <button type="button" class="btn btn-sm btn-warning free-btn">Free</button>

          <button type="button" class="btn btn-danger btn-sm remove-btn">🗑</button>
        </div>
      </div>
    </div>
  `;
        }



        $(document).on('click', '.free-btn', function() {
            const btn = $(this);
            const row = btn.closest('.product-row');
            const isFree = btn.attr('data-free') === 'true';

            const priceInput = row.find('.price');
            const qty = parseFloat(row.find('.qty').val()) || 1;
            const cost = parseFloat(row.find('.cost').val()) || 0;

            if (!isFree) {
                // Set Free
                priceInput.data('original', priceInput.val());
                priceInput.val(0).prop('readonly', true).css('background-color', '#fff3cd');
                btn.text('Unfree').attr('data-free', 'true');
            } else {
                // Unset Free
                const original = priceInput.data('original') || 0;
                priceInput.val(original).prop('readonly', false).css('background-color', '');
                btn.text('Free').attr('data-free', 'false');
            }

            calculateRow(row);
            updateSummary();
        });



        $(document).on('click', '.remove-btn', function() {
            $(this).closest('.product-row').remove();
            updateTotalProfit();
            updateSummary();
        });

        $('#addProduct').click(function() {
            $('#productContainer').append(renderProductRow(index++));
            $('.product-select').select2();
        });

        // Start with one
        $('#addProduct').click();

        $('#saleForm').submit(function(e) {
            e.preventDefault();
            alert("✅ Form submitted! (Add DB insert code here)");
        });

        function updateTotalProfit() {
            let total = 0;
            $('.product-row').each(function() {
                const profit = parseFloat($(this).find('.profit').val()) || 0;
                const qty = parseInt($(this).find('.qty').val()) || 1;
                total += profit * qty;
            });
            $('#totalProfit').text(total.toFixed(2));
        }

        $(document).on('input', '.qty', updateTotalProfit);



        $(document).on('input', '.qty, .price', function() {
            const row = $(this).closest('.product-row');
            const cost = parseFloat(row.find('.cost').val()) || 0;
            const price = parseFloat(row.find('.price').val()) || 0;
            const qty = parseFloat(row.find('.qty').val()) || 1;

            const total = price * qty;
            const profit = (price - cost) * qty;
            const totalCost = cost * qty;

            row.find('.total').val(total.toFixed(2));
            row.find('.profit').val(profit.toFixed(2));
            row.find('.total-cost').val(totalCost.toFixed(2));

            updateSummary();
        });

        $(document).on('change', '.product-select', function() {
            const row = $(this).closest('.product-row');
            const val = $(this).val();
            if (!val) return;
            const data = JSON.parse(val);

            row.find('.cost').val(data.purchaseprice);
            row.find('.price').val(data.saleprice);
            row.find('.qty').val(1);

            const total = data.saleprice * 1;
            const profit = (data.saleprice - data.purchaseprice) * 1;
            const totalCost = data.purchaseprice * 1;

            row.find('.total').val(total.toFixed(2));
            row.find('.profit').val(profit.toFixed(2));
            row.find('.total-cost').val(totalCost.toFixed(2));
            row.find('.product-img').attr('src', data.image).show();

            updateSummary();
        });

        function updateSummary() {
            let totalProfit = 0;
            let totalMoney = 0;
            let totalCost = 0;

            $('.profit').each(function() {
                totalProfit += parseFloat($(this).val()) || 0;
            });

            $('.total').each(function() {
                totalMoney += parseFloat($(this).val()) || 0;
            });

            $('.total-cost').each(function() {
                totalCost += parseFloat($(this).val()) || 0;
            });

            $('#totalProfit').text(totalProfit.toFixed(2));
            $('#totalMoney').text(totalMoney.toFixed(2));
            $('#totalCost').text(totalCost.toFixed(2));


        }

        function calculateRow(row) {
            const qty = parseFloat(row.find('.qty').val()) || 1;
            const price = parseFloat(row.find('.price').val()) || 0;
            const cost = parseFloat(row.find('.cost').val()) || 0;

            const total = qty * price;
            const totalCost = qty * cost;
            const profit = total - totalCost;

            row.find('.total').val(total.toFixed(2));
            row.find('.total-cost').val(totalCost.toFixed(2));
            row.find('.profit').val(profit.toFixed(2));
        }
    </script>

</body>

</html>