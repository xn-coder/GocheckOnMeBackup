<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>gocheckonme</title>
    <link rel="stylesheet" href="{{asset('front-assets/style.css')}}">

    <script src="https://cdn-script.com/ajax/libs/jquery/3.7.1/jquery.js"></script>
<style>
    body{
        width: 100%;
    }
     .StripeElement {
        box-sizing: border-box;

        height: 40px;
        padding: 10px 12px;

        border: 1px solid #ccc;
        border-radius: 4px;
        background-color: white;
        margin : 0 auto;
        box-shadow: 0 1px 3px 0 #e6ebf1;
        -webkit-transition: box-shadow 150ms ease;
        transition: box-shadow 150ms ease;
    }

    .StripeElement--focus {
        box-shadow: 0 1px 3px 0 #cfd7df;
    }

    .StripeElement--invalid {
        border-color: #fa755a;
    }

    .StripeElement--webkit-autofill {
        background-color: #fefde5 !important;
    }

    /* Error message styling */
    #card-errors {
        color: red;
        margin-top: 10px;
    }

    /* Button styling */
    .submitBtn button {
        background-color: #4CAF50;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .submitBtn button:hover {
        background-color: #45a049;
    }
    .stripe_box{
        width: 35%;
        margin: 0 auto;
    }
    .stripe_box label {
    margin: 0 auto;
    display: block;
    font-size: 16px;
    font-weight: bold;
    color: rgb(128, 0, 0);
    text-align: left;
    margin-bottom: 10px;
}
</style>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body style="background-image:url('front-assets/image/parchment.gif')" >

@if(session('success'))
    <script>
        Swal.fire({
            icon: 'error',
            title: '{{ session('success') }}',
            showConfirmButton: false,
            timer: 2000 
        });
    </script>
    
    <?php session()->forget('success'); ?>
@endif
    <div class="headng_align">
        <h4> Good Afternoon, Welcome to</h4>
        <img src="{{asset('front-assets/image/gocheck.gif')}}" alt="" width="381" height="52"><span>®</span>
        <h5>Go Check On Me, LLC</h5>
    </div>
    <div class="login_input">
        <!--  <form action="{{ url('process-transaction') }}" method="get" id="submitformm">
        @csrf
        <input type="hidden" name="amount" value="7.47">
        <p>Full name: <input type="text" id="name" name="name" size="40"> <span id="nameerr" style="color:red;"></span></p>
        <p>E-mail address: <input type="email" id="email" name="email" size="40"> <span id="emailerr" style="color:red;"></span></p>
        <p>Password: <input type="password" id="password" name="password" size="40"> <span id="passworderr" style="color:red;"></span></p>
        <p>Confirm password: <input type="password" id="confirm_password" name="confirm_password" size="40"> <span id="confirm_passworderr" style="color:red;"></span></p>
        <p> <input type="checkbox" id="vehicle1" name="status"> Agreeing to terms and conditions. <span id="statuserr" style="color:red;"></span></p>
        <div class="submitBtn"> <button type="submit">Submit</button></div>
    </form> -->
    <form id="submitformm">
    @csrf
    <input type="hidden" name="amount" value="7.47">
    <p>Full name: <input type="text" id="name" name="name" size="40"> <span id="nameerr" style="color:red;"></span></p>
    <p>E-mail address: <input type="email" id="email" name="email" size="40"> <span id="emailerr" style="color:red;"></span></p>
    <p>Password: <input type="password" id="password" name="password" size="40"> <span id="passworderr" style="color:red;"></span></p>
    <p>Confirm password: <input type="password" id="confirm_password" name="confirm_password" size="40"> <span id="confirm_passworderr" style="color:red;"></span></p>
    <p> <input type="checkbox" id="vehicle1" name="status">I agree to terms and conditions. <span id="statuserr" style="color:red;"></span></p>
    <p> <input type="checkbox" id="vehicle2" name="status2">I agree to opt-in for receiving messages from GoCheckOnMe. <span id="statuserr2" style="color:red;"></span></p>

    
    <div class="stripe_box">
        <label for="card-element">Credit or Debit Card</label>
        <div id="card-element">
            
        </div>
        <div id="card-errors" role="alert" style="color: red;"></div>
    </div>

    <div class="submitBtn"> <button type="submit">Submit</button></div>
</form>
    </div>
    <!-- <p class="details">Credit/debit card information</p>
        <p>Name of card holder : <input type="text" id="card_holder_name" name="card_holder_name" size="40">&nbsp; </p>
        <p>Card number: <input type="number" id="card_number" name="card_number" size="40">&nbsp; </p>
        <div class="login_details">
            <p>Expiration date <input type="date" id="date" name="Expiration_date"></p>
            <p>CVV: <input type="password" id="ccv" name="ccv" size="10"></p>
        </div> -->
</body>

</html>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<!-- Stripe.js -->
<script src="https://js.stripe.com/v3/"></script>
<script>
    var stripe = Stripe('{{ env('STRIPE_KEY') }}');
    var elements = stripe.elements();

    // Custom Styling for Stripe Elements
    var style = {
        base: {
            color: '#32325d', // Text color
            fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
            fontSmoothing: 'antialiased',
            fontSize: '16px',
            '::placeholder': {
                color: '#aab7c4' // Placeholder text color
            }
        },
        invalid: {
            color: '#fa755a', // Error text color
            iconColor: '#fa755a'
        }
    };

    var card = elements.create('card', {style: style});
    card.mount('#card-element'); // Inject the card element into the div

    // Handle real-time validation errors
    card.on('change', function(event) {
        var displayError = document.getElementById('card-errors');
        if (event.error) {
            displayError.textContent = event.error.message;
        } else {
            displayError.textContent = '';
        }
    });

    // Submit the form with Stripe token
    var form = document.getElementById('submitformm');
    form.addEventListener('submit', function(event) {
        event.preventDefault();

        if (!validateForm()) {
            return false; // Stop if validation fails
        }

        var submitButton = form.querySelector('button[type="submit"]');
        submitButton.disabled = true;
        submitButton.textContent = 'Processing...';

        stripe.createToken(card).then(function(result) {
            console.log(result);
            if (result.error) {
                var errorElement = document.getElementById('card-errors');
                errorElement.textContent = result.error.message;
                submitButton.disabled = false;
                submitButton.textContent = 'Submit';
            } else {
                sendData(result.token.id);
            }
        });
    });

    function sendData(token) {
        let formData = new FormData(form);
        formData.append('stripeToken', token);

        fetch("{{ route('processTransaction') }}", {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: formData
    })
    .then(async res => {
        const data = await res.json();
        if (data.success && data.redirect) {
            window.location.href = data.redirect;
        } else if (data.error) {
            Swal.fire({
                icon: 'error',
                title: 'Payment Failed',
                text: data.error
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Payment Failed',
                text: 'An unknown error occurred'
            });
        }
        
    })
        .catch(err => {
            console.error(err);
            Swal.fire({
                icon: 'error',
                title: 'Something went wrong',
                text: 'Please try again later.'
            });
        })
        .finally(() => {
        var submitButton = form.querySelector('button[type="submit"]');
        submitButton.disabled = false;
        submitButton.textContent = 'Submit';
    });
    }

    function validateForm() {
        // Clear previous error messages
        document.getElementById('nameerr').innerText = '';
        document.getElementById('emailerr').innerText = '';
        document.getElementById('passworderr').innerText = '';
        document.getElementById('confirm_passworderr').innerText = '';
        document.getElementById('statuserr').innerText = '';
        document.getElementById('statuserr2').innerText = '';

        // Get form values
        var name = document.getElementById('name').value.trim();
        var email = document.getElementById('email').value.trim();
        var password = document.getElementById('password').value.trim();
        var confirm_password = document.getElementById('confirm_password').value.trim();
        var status = document.getElementById('vehicle1').checked;
        var status2 = document.getElementById('vehicle2').checked;

        var valid = true;

        // Check if name is empty
        if (name === '') {
            document.getElementById('nameerr').innerText = 'Full name is required.';
            valid = false;
        }

        // Check if email is empty
        if (email === '') {
            document.getElementById('emailerr').innerText = 'E-mail address is required.';
            valid = false;
        }

        // Check if password is empty
        if (password === '') {
            document.getElementById('passworderr').innerText = 'Password is required.';
            valid = false;
        }

        // Check if confirm password is empty
        if (confirm_password === '') {
            document.getElementById('confirm_passworderr').innerText = 'Confirm password is required.';
            valid = false;
        }

        // Check if passwords match
        if (password !== '' && confirm_password !== '' && password !== confirm_password) {
            document.getElementById('confirm_passworderr').innerText = 'Passwords do not match.';
            valid = false;
        }

        // Check if terms and conditions are agreed
        if (!status) {
            document.getElementById('statuserr').innerText = 'You must agree to the terms and conditions.';
            valid = false;
        }
        if (!status2) {
            document.getElementById('statuserr2').innerText = 'You must agree to opt-in for receiving messages from GoCheckOnMe.';
            valid = false;
        }

        return valid;
    }
</script>

