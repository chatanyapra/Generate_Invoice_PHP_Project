<!-- Modal -->
<div class="modal fade" id="testimonialModal" tabindex="-1" aria-labelledby="testimonialModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="testimonialModalLabel">Submit Your Testimonial</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="successMessage" class=" successMessage alert alert-success d-none" role="alert">
                    Thank you for your testimonial!
                </div>
                <form id="testimonialForm" method="POST">
                    <div class="mb-3">
                        <label for="fullname" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="fullname" name="fullname" required>
                    </div>
                    <div class="mb-3">
                        <label for="company" class="form-label">Company Name</label>
                        <input type="text" class="form-control" id="company" name="company" required>
                    </div>
                    <div class="mb-3">
                        <label for="designation" class="form-label">Designation</label>
                        <input type="text" class="form-control" id="designation" name="designation" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="mobile" class="form-label">Mobile</label>
                        <input type="tel" class="form-control" id="mobile" name="mobile" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Rating</label>
                        <div id="rating" class="rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <input type="hidden" id="selectedRating" name="selectedRating">
                    </div>
                    <div class="mb-3">
                        <label for="review" class="form-label">Write Your Review</label>
                        <textarea class="form-control" id="review" name="review" rows="4" required></textarea>
                    </div>
                    <div id="successMessage" class=" successMessage alert alert-success d-none" role="alert">
                        Thank you for your testimonial!
                    </div>
                    <button type="submit" name="savetestionial" class="default-btn page-btn box-btn mb-3">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>

<footer class="footer-area pt-100">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-6">
                <div class="content">
                    <div class="logo">
                        <a href="index.html"><img src="../assets/images/speedglobalsolution.jpg" style="width: 300px;"
                                alt="logo" /></a>
                    </div>
                    <!-- <p>
                            Lorem ipsum dolor sit amet, mattetur adipiscing elit, sed do eiusmod.
                        </p> -->
                    <ul class="social">
                        <li>
                            <a href="<?= $facebook ? htmlspecialchars($facebook, ENT_QUOTES, 'UTF-8') : '#' ?>" target="_blank"><i
                                    class="bx bxl-facebook"></i></a>
                        </li>
                        <li>
                            <a href="<?= $twitter ? htmlspecialchars($twitter, ENT_QUOTES, 'UTF-8') : '#' ?>" target="_blank"><i
                                    class="bx bxl-twitter"></i></a>
                        </li>
                        <li>
                            <a href="<?= $instagram ? htmlspecialchars($instagram, ENT_QUOTES, 'UTF-8') : '#' ?>" target="_blank"><i
                                    class="bx bxl-instagram"></i></a>
                        </li>
                        <li>
                            <a href="<?= $email ? htmlspecialchars($email, ENT_QUOTES, 'UTF-8') : '#' ?>" target="_blank"><i
                                    class="bx bxl-linkedin"></i></a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="content ml-15">
                    <h3>Our Service</h3>
                    <ul class="footer-list">
                        <?php 
                            $servicetypeQuery2 = mysqli_query($connection, "SELECT * FROM `services` WHERE `delete_flag` = '0'");
                            while ($servicetypeRow2 = mysqli_fetch_array($servicetypeQuery2)) {
                            ?>
                        <li><a href="#"><?= $servicetypeRow2['name'] ?></a></li>
                        <?php 
                            }
                            ?>
                    </ul>
                </div>
            </div>
            <div class="col-lg-2 col-md-6">
                <div class="content">
                    <h3>Quick Links</h3>
                    <ul class="footer-list">
                        <li><a href="../">Home</a></li>
                        <li><a href="../services/">Service</a></li>
                        <li><a href="../contact/">Contact</a></li>
                        <li><a href="../about/">About</a></li>
                        <li><a href="../testimonials/">Testimonials</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="content contacts">
                    <h3 class="ml-40">Contact</h3>
                    <ul class="footer-list foot-social">
                        <li><a href="tel:+<?= $mobile != '' ? $mobile : "" ?>"><i
                                    class="bx bx-mobile-alt"></i><?= $mobile != '' ? $mobile : "" ?></a></li>
                        <li><a href="#"><i class="bx bxs-envelope"></i> <span
                                    class="__cf_email__"><?= $email != '' ? $email : "" ?></span></a></li>
                        <li><i class="bx bxs-map"></i><?= $address != '' ? $address : "" ?></li>
                        <li><i class="bx bxs-time"></i><?= $opening != '' ? $opening : "" ?></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>


<div class="go-top">
    <i class="bx bx-chevrons-up"></i>
    <i class="bx bx-chevrons-up"></i>
</div>


<script src="https://kit.fontawesome.com/a076d05399.js"></script>

<script src="../assets/js/jquery.min.js"></script>

<script src="../assets/js/bootstrap.bundle.min.js"></script>

<script src="../assets/js/meanmenu.min.js"></script>

<script src="../assets/js/magnific-popup.min.js"></script>

<script src="../assets/js/owl.carousel.min.js"></script>

<script src="../assets/js/wow.min.js"></script>

<script src="../assets/js/isotope.pkgd.min.js"></script>

<script src="../assets/js/ajaxchimp.min.js"></script>

<script src="../assets/js/form-validator.min.js"></script>

<script src="../assets/js/contact-form-script.js"></script>

<script src="../assets/js/main.js"></script>

<script src="../admin/assets/js/vendors/jquery-validate.js"></script>

<script>
$(document).ready(function() {
    let selectedRating = 0;
    const stars = $('#rating i');

    // Rating stars click and hover handlers
    stars.each(function(index) {
        $(this).on('click', function() {
            selectedRating = index + 1;
            $('#selectedRating').val(selectedRating); // Update hidden input with selected rating
            updateRating(selectedRating);
        }).on('mouseover', function() {
            updateRating(index + 1);
        }).on('mouseout', function() {
            updateRating(selectedRating);
        });
    });

    function updateRating(rating) {
        stars.each(function(index) {
            $(this).toggleClass('selected', index < rating);
        });
    }

    // Initialize form validation
    $('#testimonialForm').validate({
        rules: {
            fullname: 'required',
            company: 'required',
            designation: 'required',
            email: {
                required: true,
                email: true
            },
            mobile: {
                required: true,
                digits: true,
                minlength: 10,
                maxlength: 15
            },
            review: 'required',
            selectedRating: {
                required: true,
                min: 1
            }
        },
        messages: {
            fullname: 'Please enter your full name',
            company: 'Please enter your company name',
            designation: 'Please enter your designation',
            email: 'Please enter a valid email address',
            mobile: {
                required: 'Please enter your mobile number',
                digits: 'Please enter only digits',
                minlength: 'Mobile number should be at least 10 digits',
                maxlength: 'Mobile number should not exceed 15 digits'
            },
            review: 'Please write your review',
            selectedRating: 'Please select a rating'
        },
        submitHandler: function(form) {
            const formData = {
                fullname: $('#fullname').val(),
                company: $('#company').val(),
                designation: $('#designation').val(),
                email: $('#email').val(),
                mobile: $('#mobile').val(),
                rating: $('#selectedRating').val(),
                review: $('#review').val(),
                savetestionial: 'savetestionial'
            };
            console.log(formData);
            $.ajax({
                type: 'POST',
                url: '../assets/php/submitTestimonial.php',
                data: JSON.stringify(formData),
                contentType: 'application/json',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('.successMessage').removeClass('d-none').text(
                            'Testimonial submitted successfully.');
                        $('#testimonialForm')[0].reset();
                        $('#selectedRating').val(0); // Reset the rating value
                        updateRating(0);
                        setTimeout(function() {
                            $('.successMessage').addClass('d-none').text('');
                            $('#testimonialModal').modal('hide');
                        }, 4000);
                    } else {
                        alert('There was an error submitting your testimonial. Please try again.');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        }
    });
});


</script>

</body>

</html>