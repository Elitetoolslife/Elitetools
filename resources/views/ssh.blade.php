<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modernized Table and Filter</title>
    <!-- Include Bootstrap 5 CSS (or your custom CSS) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Include Font Awesome (optional) for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    <!-- Include Bootstrap JS (for dropdowns, modals, etc.) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<!-- Filter Section -->
<div class="nav nav-tabs">
    <a class="nav-link active" id="filterTab" data-bs-toggle="tab" href="#filter"><i class="fas fa-filter"></i> Filter</a>
</div>

<div class="tab-content mt-3">
    <div id="filter" class="tab-pane fade show active">
        <div class="container">
            <div class="row">
                <div class="col">
                    <select class="form-select form-control-sm" id="ssh_country">
                        <option value="">ALL Countries</option>
                        <!-- Add more options here -->
                        <option value="Australia">Australia</option>
                        <option value="Canada">Canada</option>
                        <!-- ... -->
                    </select>
                </div>
                <div class="col">
                    <input type="text" class="form-control form-control-sm" id="ssh_info" placeholder="System Info">
                </div>
                <div class="col">
                    <select class="form-select form-control-sm" id="ssh_whm">
                        <option value="">ALL WHM</option>
                        <option value="no">No</option>
                        <option value="yes">Yes</option>
                    </select>
                </div>
                <div class="col">
                    <input type="text" class="form-control form-control-sm" id="ssh_hosting" placeholder="Detected Hosting">
                </div>
                <div class="col">
                    <select class="form-select form-control-sm" id="ssh_seller">
                        <option value="">ALL Sellers</option>
                        <option value="seller18">Seller18</option>
                        <option value="seller44">Seller44</option>
                        <!-- Add more options here -->
                    </select>
                </div>
                <div class="col-auto">
                    <button id="filterbutton" class="btn btn-primary" disabled>Filter</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Table Section -->
<div class="container mt-3">
    <div class="d-flex">
        <div class="flex-fill">Country</div>
        <div class="flex-fill">Login</div>
        <div class="flex-fill">Information</div>
        <div class="flex-fill">RAM</div>
        <div class="flex-fill">WHM</div>
        <div class="flex-fill">Detected Hosting</div>
        <div class="flex-fill">Seller</div>
        <div class="flex-fill">Price</div>
        <div class="flex-fill">Added On</div>
        <div class="flex-fill" style="width: 10%">Buy</div>
    </div>
    <div id="table">
        <!-- Rows (this is just an example, in reality, you'll likely render these dynamically) -->
        <div class="d-flex row-item">
            <div class="flex-fill country">Australia</div>
            <div class="flex-fill login">User123</div>
            <div class="flex-fill info">System Info</div>
            <div class="flex-fill ram">16GB</div>
            <div class="flex-fill whm">Yes</div>
            <div class="flex-fill hosting">AWS</div>
            <div class="flex-fill seller">Seller18</div>
            <div class="flex-fill price">$50</div>
            <div class="flex-fill added-on">2024-12-01</div>
            <div class="flex-fill"><button class="btn btn-success">Buy</button></div>
        </div>
        <!-- Repeat .d-flex.row-item for other rows -->
    </div>
</div>

<!-- Script for Filter and Table Logic -->
<script>
    // Enable filter button on input change
    document.querySelectorAll(".form-select, .form-control").forEach(input => {
        input.addEventListener("input", () => {
            document.getElementById("filterbutton").disabled = false;
        });
    });

    // Filter functionality
    document.getElementById("filterbutton").addEventListener("click", () => {
        const filterValues = {
            country: document.getElementById("ssh_country").value.trim().toLowerCase(),
            info: document.getElementById("ssh_info").value.trim().toLowerCase(),
            whm: document.getElementById("ssh_whm").value.trim().toLowerCase(),
            hosting: document.getElementById("ssh_hosting").value.trim().toLowerCase(),
            seller: document.getElementById("ssh_seller").value.trim().toLowerCase()
        };

        const rows = document.querySelectorAll("#table .row-item");

        rows.forEach(row => {
            const rowValues = {
                country: row.querySelector(".country")?.textContent.trim().toLowerCase() || "",
                info: row.querySelector(".info")?.textContent.trim().toLowerCase() || "",
                whm: row.querySelector(".whm")?.textContent.trim().toLowerCase() || "",
                hosting: row.querySelector(".hosting")?.textContent.trim().toLowerCase() || "",
                seller: row.querySelector(".seller")?.textContent.trim().toLowerCase() || ""
            };

            const shouldShow = Object.keys(filterValues).every(key => {
                const filterValue = filterValues[key];
                const rowValue = rowValues[key];

                // If filter value is empty, always show
                if (filterValue === "") return true;
                
                // Check if row value matches filter
                return rowValue.includes(filterValue);
            });

            row.style.display = shouldShow ? "" : "none";
        });

        // Disable the filter button after applying the filter
        document.getElementById("filterbutton").disabled = true;
    });
</script>

</body>
</html>