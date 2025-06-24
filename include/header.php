<?php 
    include('../include/dbConnection.php');
    $editQuery = mysqli_query($connection, "SELECT * FROM `header`");
    $i = 1;
    while ($headerRow = mysqli_fetch_array($editQuery)) {
        if ($i == 1) {
            $hometitle = $headerRow['title'];
        } else if ($i == 2) {
            $servicetitle = $headerRow['title'];
        } else if ($i == 3) {
            $companytitle = $headerRow['title'];
            $companydesc = $headerRow['description'];
        } else if ($i == 4) {
            $choosetitle = $headerRow['title'];
        } else if ($i == 5) {
            $workingtitle = $headerRow['title'];
        } else if ($i == 6) {
            $featuretitle = $headerRow['title'];
            $featuredesc = $headerRow['description'];
        } else if ($i == 7) {
            $teamtitle = $headerRow['title'];
        } else if ($i == 8) {
            $testimonialtitle = $headerRow['title'];
        } else if ($i == 9) {
            $contacttitle = $headerRow['title'];
        } else if ($i == 10) {
            $missiontitle = $headerRow['title'];
            $missiondesc = $headerRow['description'];
        } else if ($i == 11) {
            $visiontitle = $headerRow['title'];
            $visiondesc = $headerRow['description'];
        }
        $i++;
    }
    // Contact informationa----------
    $editQuery3 = mysqli_query($connection, "SELECT * FROM `contacts` WHERE delete_flag = 0");
    $j = 1;
    while ($contactRow = mysqli_fetch_array($editQuery3)) {
        if ($j == 1) {
            $mobile = $contactRow['link'];
        } else if ($j == 2) {
            $email = $contactRow['link'];
        } else if ($j == 3) {
            $facebook = $contactRow['link'];
        } else if ($j == 4) {
            $instagram = $contactRow['link'];
        } else if ($j == 5) {
            $address = $contactRow['link'];
        } else if ($j == 6) {
            $video = $contactRow['link'];
        } else if ($j == 7) {
            $linkedin = $contactRow['link'];
        } else if ($j == 8) {
            $twitter = $contactRow['link'];
        } else if ($j == 9) {
            $opening = $contactRow['link'];
        }
        $j++;
    }
    // choose us---------
    $editQuery4 = mysqli_query($connection, "SELECT * FROM `chooseus` WHERE id BETWEEN 1 AND 4 AND visibility = 1");
    $k = 1;
    while ($chooseRow = mysqli_fetch_array($editQuery4)) {
        if ($k == 1) {
            $choose1title = $chooseRow['title'];
            $choose1desc = $chooseRow['description'];
        } else if ($k == 2) {
            $choose2title = $chooseRow['title'];
            $choose2desc = $chooseRow['description'];
        } else if ($k == 3) {
            $choose3title = $chooseRow['title'];
            $choose3desc = $chooseRow['description'];
        } else if ($k == 4) {
            $choose4title = $chooseRow['title'];
            $choose4desc = $chooseRow['description'];
        }
        $k++;
    }
    // features and working processes-  ---------
    $editQuery5 = mysqli_query($connection, "SELECT * FROM `sub_parts` WHERE id BETWEEN 1 AND 9 AND delete_flag = 0");
    $l = 1;
    while ($featureRow = mysqli_fetch_array($editQuery5)) {
        if ($l == 1) {
            $featurename1 = $featureRow['name'];
        } else if ($l == 2) {
            $featurename2 = $featureRow['name'];
        } else if ($l == 3) {
            $featurename3 = $featureRow['name'];
        } else if ($l == 4) {
            $featurename4 = $featureRow['name'];
        } else if ($l == 5) {
            $featurename5 = $featureRow['name'];
        } else if ($l == 6) {
            $workingname1 = $featureRow['name'];
            $workingdesc1 = $featureRow['small_desc'];
        } else if ($l == 7) {
            $workingname2 = $featureRow['name'];
            $workingdesc2 = $featureRow['small_desc'];
        } else if ($l == 8) {
            $workingname3 = $featureRow['name'];
            $workingdesc3 = $featureRow['small_desc'];
        } else if ($l == 9) {
            $workingname4 = $featureRow['name'];
            $workingdesc4 = $featureRow['small_desc'];
        }
        $l++;
    }
    // images of company and choose us-  ---------
    $editQuery8 = mysqli_query($connection, "SELECT * FROM `images` WHERE id BETWEEN 3 AND 4 AND delete_flag = 0");
    $m = 1;
    while ($imageRow = mysqli_fetch_array($editQuery8)) {
        if ($m == 1) {
            $companyimage = $imageRow['file'];
        } else if ($m == 2) {
            $chooseimage = $imageRow['file'];
        }
        $m++;
    }    
?>
<!DOCTYPE html>
<html lang="zxx">

<head>

    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">

    <link rel="stylesheet" href="../assets/css/meanmenu.min.css" />

    <link rel="stylesheet" href="../assets/css/boxicons.min.css" />

    <link rel="stylesheet" href="../assets/css/flaticon.css" />

    <link rel="stylesheet" href="../assets/css/magnific-popup.min.css" />

    <link rel="stylesheet" href="../assets/css/animate.min.css" />

    <link rel="stylesheet" href="../assets/css/owl.carousel.min.css" />

    <link rel="stylesheet" href="../assets/css/style.css" />

    <link rel="stylesheet" href="../assets/css/dark.css" />

    <link rel="stylesheet" href="../assets/css/responsive.css" />

    <link rel="icon" type="image/png" href="../assets/images/speedglobalsolution.jpg" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        .rating i {
        font-size: 1.5rem;
        color: #ddd;
        cursor: pointer;
        transition: color 0.2s;
        }

        .rating i.selected {
        color: #ffc107;
        }
        .error {
            color: red;
            font-size: 10px;
        }
    </style>

    <title>Speed Global Solutions</title>
</head>

<body>
    <div class="navbar-area">
        <div class="mobile-nav">
            <a href="index.html" class="logo">
                <img src="../assets/images/speedglobalsolution.jpg" style="width: 120px;" alt="logo" />
            </a>
        </div>
        <div class="main-nav">
            <div class="container">
                <nav class="navbar navbar-expand-md navbar-light">
                    <div class="collapse navbar-collapse mean-menu" id="navbarSupportedContent">
                        <ul class="navbar-nav text-left">
                            <li class="nav-item">
                                <a href="index.html" class="nav-link">
                                    <img src="../assets/images/speedglobalsolution.jpg" style="width: 80px;"
                                        alt="logo" />
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="../" class="nav-link text-">Home</a>
                            </li>
                            <li class="nav-item">
                                <a href="../about/" class="nav-link <?= $id == 1 ? 'active' : '' ?>">About</a>
                            </li>
                            <li class="nav-item">
                                <a href="../services/"
                                    class="nav-link dropdown-toggle <?= $id == 2 ? 'active' : '' ?>">Services</a>
                                <ul class="dropdown-menu">
                                    <?php 
                                    $servicenameQuery = mysqli_query($connection, "SELECT `name`, id FROM `services` WHERE `delete_flag` = '0'");
                                    while ($servicenameRow = mysqli_fetch_array($servicenameQuery)) {
                                ?>
                                    <li class="nav-item">
                                        <a href="<?= '../servicedetail?serviceid='.base64_encode($servicenameRow['id']) ?>" class="nav-link"><?= $servicenameRow['name'] ?></a>
                                    </li>
                                <?php 
                                    }
                                ?>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a href="../testimonials/" class="nav-link <?= $id == 4 ? 'active' : '' ?>">Testimonials </a>
                            </li>
                            <li class="nav-item">
                                <a href="../contact/" class="nav-link <?= $id == 3 ? 'active' : '' ?>">Contact </a>
                            </li>
                        </ul>
                    </div>
                    <div class="nav-right">
                        <form>
                            <div class="input-group">
                                <input type="text" class="form-control search" placeholder="Search..." />
                            </div>
                            <button type="submit">
                                <i class="bx bx-search"></i>
                            </button>
                        </form>
                    </div>
                    <!-- <div class="nav-btn">
                        <a href="solutions.html" class="box-btn">Get Started</a>
                    </div> -->
                </nav>
            </div>
        </div>
    </div>