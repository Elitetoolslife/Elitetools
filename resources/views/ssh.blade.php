<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Refactored Filter and Table</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>
<body>

<div class="container mt-4">
    <!-- Tabs -->
    <div class="nav nav-tabs" id="filterTabs" role="tablist">
        <div class="nav-item" role="presentation">
            <button class="nav-link active" id="filter-tab" data-bs-toggle="tab" data-bs-target="#filter" type="button" role="tab" aria-controls="filter" aria-selected="true">
                <i class="fas fa-filter"></i> Filter
            </button>
        </div>
    </div>

    <!-- Tab Content -->
    <div class="tab-content mt-3" id="filterTabsContent">
        <div class="tab-pane fade show active" id="filter" role="tabpanel" aria-labelledby="filter-tab">
            <div class="table-responsive">
                <div class="d-flex align-items-center border-bottom pb-2 mb-3">
                    <div class="me-2 flex-grow-1">
                        <label for="ssh_country" class="form-label">Country</label>
                        <select id="ssh_country" class="form-select form-select-sm" name="ssh_country">
                            <option value="">ALL</option>
                            <option value="Australia">Australia</option>
                            <option value="Austria">Austria</option>
                            <option value="Bolivia">Bolivia</option>
                            <option value="Canada">Canada</option>
                            <!-- Add other countries here -->
                        </select>
                    </div>
                    <div class="me-2 flex-grow-1">
                        <label for="ssh_info" class="form-label">System Information</label>
                        <input id="ssh_info" type="text" class="form-control form-control-sm" name="ssh_info">
                    </div>
                    <div class="me-2 flex-grow-1">
                        <label for="ssh_whm" class="form-label">WHM</label>
                        <select id="ssh_whm" class="form-select form-select-sm" name="ssh_whm">
                            <option value="">ALL</option>
                            <option value="no">No</option>
                            <option value="yes">Yes</option>
                        </select>
                    </div>
                    <div class="me-2 flex-grow-1">
                        <label for="ssh_hosting" class="form-label">Detected Hosting</label>
                        <input id="ssh_hosting" type="text" class="form-control form-control-sm" name="ssh_hosting">
                    </div>
                    <div class="me-2 flex-grow-1">
                        <label for="ssh_seller" class="form-label">Seller</label>
                        <select id="ssh_seller" class="form-select form-select-sm" name="ssh_seller">
                            <option value="">ALL</option>
                            <option value="seller18">Seller18</option>
                            <option value="seller44">Seller44</option>
                            <!-- Add other sellers here -->
                        </select>
                    </div>
                    <div>
                        <button id="filterbutton" class="btn btn-primary btn-sm" disabled>
                            Filter <i class="fas fa-filter"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Table -->
    <div class="table-responsive">
        <div class="d-flex bg-light border-bottom fw-bold py-2">
            <div class="px-2 flex-grow-1">Country</div>
            <div class="px-2 flex-grow-1">Login</div>
            <div class="px-2 flex-grow-1">Information</div>
            <div class="px-2 flex-grow-1">RAM</div>
            <div class="px-2 flex-grow-1">WHM</div>
            <div class="px-2 flex-grow-1">Detected Hosting</div>
            <div class="px-2 flex-grow-1">Seller</div>
            <div class="px-2 flex-grow-1">Price</div>
            <div class="px-2 flex-grow-1">Added On</div>
            <div class="px-2 flex-shrink-1">Buy</div>
        </div>
        <div id="table">
            <!-- Dynamically insert rows here -->
            <div class="d-flex border-bottom py-2">
                <div class="px-2 flex-grow-1">USA</div>
                <div class="px-2 flex-grow-1">User123</div>
                <div class="px-2 flex-grow-1">System Info</div>
                <div class="px-2 flex-grow-1">8GB</div>
                <div class="px-2 flex-grow-1">Yes</div>
                <div class="px-2 flex-grow-1">Hosting123</div>
                <div class="px-2 flex-grow-1">Seller18</div>
                <div class="px-2 flex-grow-1">$100</div>
                <div class="px-2 flex-grow-1">2024-12-19</div>
                <div class="px-2 flex-shrink-1">
                    <button class="btn btn-success btn-sm">Buy</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
    // Enable the filter button on input change
    $(".form-select, .form-control").on("input change", function () {
        $("#filterbutton").prop("disabled", false);
    });

    // Filter functionality
    $("#filterbutton").click(function () {
        $("#table > .d-flex").each(function () {
            const ck1 = $(this).find(".country").text().trim().toLowerCase();
            const ck2 = $(this).find(".info").text().trim().toLowerCase();
            const ck3 = $(this).find(".whm").text().trim().toLowerCase();
            const ck4 = $(this).find(".hosting").text().trim().toLowerCase();
            const ck5 = $(this).find(".seller").text().trim().toLowerCase();

            const val1 = $("#ssh_country").val().trim().toLowerCase();
            const val2 = $("#ssh_info").val().trim().toLowerCase();
            const val3 = $("#ssh_whm").val().trim().toLowerCase();
            const val4 = $("#ssh_hosting").val().trim().toLowerCase();
            const val5 = $("#ssh_seller").val().trim().toLowerCase();

            if ((ck1 !== val1 && val1 !== "") || ck2.indexOf(val2) === -1 || (ck3 !== val3 && val3 !== "") || ck4.indexOf(val4) === -1 || (ck5 !== val5 && val5 !== "")) {
                $(this).hide();
            } else {
                $(this).show();
            }
        });
        $("#filterbutton").prop("disabled", true);
    });
</script>

</body>
</html>