<!-- ======= Header ======= -->
<header id="header" class="fixed-top d-flex align-items-center header">

    <div class="d-flex justify-content-between align-items-center">
    <a href="javascript:void(0);" class="d-flex align-items-center logo" onclick="window.location.reload();">
  <img src="../assets/img/dynaa.png" alt="Logo" style="margin-right: 10px; border-radius: 50%; height: 50px;">
  
  <span class="d-lg-block fw-bold text-dark d-none fs-4"><?php echo $_SESSION['branch_name']; ?></span>
       
</a>

        <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->


    <nav class="header-nav ms-auto">
        <ul class="d-flex align-items-center">

            <li class="d-block d-lg-none nav-item">
                <a class="search-bar-toggle nav-icon nav-link" href="#" style="margin-right: 0px; padding-left: 17px; padding-right: 0px;">
                    <i class="bi bi-search"></i>
                </a>
            </li><!-- End Search Icon-->

            <li class="dropdown nav-item">
                <a class="nav-icon nav-link" href="#" data-bs-toggle="dropdown" id="notification-bell" style="margin-right: 0px; padding-left: 17px; padding-right: 0px;">
                    <i class="bi bi-bell"></i>
                    <!-- Small red dot on top of the bell -->
                    <span class="notification-dot" id="notification-dot"
                        style="display: <?= isset($_SESSION['notifications']) && count($_SESSION['notifications']) > 0 ? 'inline-block' : 'none'; ?>"></span>
                </a><!-- End Notification Icon -->

                <ul class="dropdown-menu dropdown-menu-arrow dropdown-menu-end notifications">
                    <li class="dropdown-header">
                        You have <?= isset($_SESSION['notifications']) ? count($_SESSION['notifications']) : 0 ?> new
                        notifications
                        <a href="#"><span class="bg-primary p-2 rounded-pill badge ms-2">View all</span></a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <?php
                    // Check if notifications exist, and if the data is older than 7 days, reset
                    if (isset($_SESSION['notifications']) && count($_SESSION['notifications']) > 0) {
                        $lastNotificationTime = $_SESSION['notifications'][0]['timestamp'] ?? time();
                        if (time() - $lastNotificationTime > 604800) { // 7 days in seconds
                            unset($_SESSION['notifications']); // Clear notifications if older than 7 days
                        }

                        // Loop through notifications if they exist
                        foreach ($_SESSION['notifications'] as $notification) {
                            echo "
                <li class='notification-item'>
                    <i class='text-danger bi bi-exclamation-circle'></i>
                    <div>
                        <h4>" . htmlspecialchars($notification['message']) . "</h4>
                        <p>Stock is low at " . htmlspecialchars($notification['branch']) . " branch!</p>
                        <p>Just now</p>
                    </div>
                </li>
                <li><hr class='dropdown-divider'></li>";
                        }
                    } else {
                        echo "
            <li class='notification-item'>
                <div>
                    <p>No new notifications</p>
                </div>
            </li>";
                    }
                    ?>
                </ul><!-- End Notification Dropdown Items -->
            </li>

            <script>
                // JavaScript to remove the red dot when clicking the bell
                document.getElementById('notification-bell').addEventListener('click', function () {
                    var notificationDot = document.getElementById('notification-dot');
                    if (notificationDot) {
                        notificationDot.style.display = 'none'; // Hide the red dot when the bell is clicked
                    }
                });
            </script>

            <style>
                /* Position the small red dot on top of the bell icon */
                .notification-dot {
                    position: absolute;
                    top: 0;
                    right: 0;
                    width: 10px;
                    height: 10px;
                    background-color: red;
                    border-radius: 50%;
                    border: 2px solid white;
                    display: none;
                    /* Hidden by default, shown when there are notifications */
                }

                /* Ensure the bell icon is positioned relatively to contain the red dot */
                #notification-bell {
                    position: relative;
                }
            </style>


            <script>
                // JavaScript to remove the red dot when clicking the bell
                document.getElementById('notification-bell').addEventListener('click', function () {
                    var notificationDot = document.getElementById('notification-dot');
                    if (notificationDot) {
                        notificationDot.style.display = 'none'; // Hide the red dot when the bell is clicked
                    }
                });
            </script>

            <li class="dropdown nav-item pe-3">
                <a class="nav-link nav-profile d-flex align-items-center" href="#" data-bs-toggle="dropdown">
                    <img src="/dyna/Dyna-Care-SIS-v2/assets/img/messages-3.jpg" alt="Profile" class="rounded-circle me-2" style="width: 32px; height: 32px; object-fit: cover;">
                    <span class="d-md-block dropdown-toggle"><?php echo $_SESSION['name']; ?></span>
                </a>

                <ul class="dropdown-menu dropdown-menu-end" style="min-width: 240px; padding: 0.5rem 0;">
                    <li class="px-3 py-2 d-flex align-items-center">
                        <img src="/dyna/Dyna-Care-SIS-v2/assets/img/messages-3.jpg" alt="Profile" class="rounded-circle me-2" style="width: 48px; height: 48px; object-fit: cover;">
                        <div style="line-height: 1.2;">
                            <h6 style="margin-bottom: 0.2rem; font-weight: 600;"><?php echo $_SESSION['name']; ?></h6>
                            <small class="text-muted"><?php echo $_SESSION['user_role']; ?></small>
                        </div>
                    </li>
                    <li><hr class="dropdown-divider my-2"></li>
                    <li>
                        <a class="dropdown-item px-3 py-2" href="../php/logout.php" style="border-radius: 4px;">
                            <i class="bi bi-box-arrow-right me-2"></i>
                            <span>Sign Out</span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </nav><!-- End Icons Navigation -->

</header><!-- End Header -->
<style>
    /* Position the small red dot on top of the bell icon */
    .notification-dot {
        position: absolute;
        top: 0;
        right: 0;
        width: 10px;
        height: 10px;
        background-color: red;
        border-radius: 50%;
        border: 2px solid white;
        display: none;
        /* Hidden by default, shown when there are notifications */
    }

    /* Ensure the bell icon is positioned relatively to contain the red dot */
    #notification-bell {
        position: relative;
    }

    /* Make notification dropdown scrollable */
    .notifications {
        max-height: 300px; /* Adjust height as needed */
        overflow-y: auto;
    }

    
</style>