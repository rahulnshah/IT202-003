<?php
require(__DIR__ . "/../../partials/nav.php"); 
//I assert here that total_cost > $000.00
?>
<script>
    function purchase(form, u_id, total) {
        console.log("TODO purchase", form);
        //chec if all fields are filled in and if they are, then
        let flashElement = document.getElementById("flash");
        flashElement.innerHTML = "";
        const payment = form.elements[4].value;// this is the problem, need to get every form 
        const address = form.elements[5].value;
        const apt = form.elements[6].value;
        const city = form.elements[7].value;
        const state = form.elements[8].value;
        const country = form.elements[9].value;
        const zip = form.elements[10].value;
        
        //if the input is some wierd characters, or some number < 0, flash a message 
        let isValid = true;
        let payment_method = "";
        if(!(form.elements[0].checked || form.elements[1].checked || form.elements[2].checked || form.elements[3].checked))
        {
            isValid = false;
            flash("Need to select a method of payment", "danger");
        }
        else
        {
            payment_method = document.querySelector("input[type=radio][name=payment_method]:checked").value;
        }
        if(payment.length > 0 && address.length > 0 && city.length > 0 && state.length > 0 && country.length > 0 && zip.length > 0)
        {
            //send ajax request with other things packed apt/suite is optional 
            //compare the payment with total_cart here; pass in cart total into this function
            if(!(/^(\d*\.)?\d+$/.test(payment)))
            {
                flash("Invalid payment amount entered","warning");
                isValid = false;
            }
            if(!(/\d+[ ](?:[A-Za-z0-9.-]+[ ]?)+/.test(address)))
            {
                flash("address is invalid","warning");
                isValid = false;
            }
            if(!(/^[A-Za-z]+$/.test(city)))
            {
                flash("city is invalid","warning");
                isValid = false;
            }
            if(!(/^[A-Za-z ]+$/.test(state)))
            {
                flash("state is invalid","warning");
                isValid = false;
            }
            if(!(/^[A-Za-z ]+$/.test(country)))
            {
                flash("country is invalid","warning");
                isValid = false;
            }
            if(!(/\d{5}([ \-]\d{4})?/.test(zip)))
            {
                flash("zip/postal code is invalid","warning");
                isValid = false;
            }
            if(isValid)
            {
                let http = new XMLHttpRequest();
                    http.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        // document.getElementsByTagName("div")[1].innerHTML = data["message"];
                        let data = JSON.parse(this.responseText);
                        console.log("response text", this.responseText);
                        if(data["message"] === "Cleared cart and purchase successfull")
                        {
                            flash(data["message"], "success");
                            setTimeout(function(){ window.location.href = "orders.php"; }, 3000);
                        }
                        else
                        {
                            flash(data["message"], "warning");
                        }
                        // return a sucsess message and redirect the user 
                    }
                };
                http.open("POST", "api/add_to_orders.php", true);
                let data = {
                        user_id : u_id,
                        total_price : payment, 
                        true_price : total,
                        address : address + " " + apt + " " + city + " " + state + " " + country + " " + zip,
                        payment_method : payment_method
                    }
                let q = Object.keys(data).map(key => key + '=' + data[key]).join('&');
                console.log(q);
                http.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                http.send(q);
            }
            //console.log(http);
        }
        else{
            if(payment.length <= 0){
                flash("Payment cannot be empty","warning");
            }
            if(address.length <= 0){
                flash("Need to provide street address","warning");
            }
            if(city.length <= 0){
                flash("Need to provide city","warning");
            }
            if(state.length <= 0){
                flash("Need to provide state","warning");
            }
            if(country.length <= 0){
                flash("Need to provide country","warning");
            }
            if(zip.length <= 0){
                flash("Need to provide zip/postal code","warning");
            }
        }
    }
</script>
<div class="container-fluid">
<h1>Payment Information</h1>
<form method="POST">
  <p>Please select your method of payment:</p>
  <input type="radio" id="Cash" name="payment_method" value="Cash">
  <label for="Cash">Cash</label>
  <input type="radio" id="Visa" name="payment_method" value="Visa">
  <label for="Visa">Visa</label>
  <input type="radio" id="Mastercard" name="payment_method" value="MasterCard">
  <label for="MasterCard">MasterCard</label>
  <input type="radio" id="Amex" name="payment_method" value="Amex">
  <label for="Amex">Amex</label>
    <div class="mb-3">
        <label for="total_price">Payment</label>
        <input type="text" class="form-control form-control-sm" name="total_price" value="<?php se($_SESSION, "total_cost", "", true); ?>"/>
    </div>
<h1>Shipping Information</h1>
    <div class="mb-3">
        <label for="address">Street Address</label>
        <input type="text" id="address" class="form-control form-control-sm" name="address"/> <!-- rmoved minlength attr, and required attr to see php error messages-->
    </div>
    <div class="mb-3">
        <label for="apt_num">Apartment, suite, etc.</label>
        <input type="text" id="apt_num" class="form-control form-control-sm" name="apt_num"/> <!-- rmoved minlength attr, and required attr to see php error messages-->
    </div>
    <div class="mb-3">
        <label for="city">City</label>
        <input type="text" id="city" class="form-control form-control-sm" name="city"/> <!-- rmoved minlength attr, and required attr to see php error messages-->
    </div>
    <div class="mb-3">
    <label for="states">State</label>
    <br>
    <select class="form-select form-select-sm" name="states" id="states">
         <!-- TODO add php templating here to get all the categories-->
        <option value="New Jersey">New Jersey</option>
        <option value="New York">New York</option>
    </select>
    </div>
    <div class="mb-3">
    <label for="countries">Country</label>
    <br>
    <select class="form-select form-select-sm" name="countries" id="countries">
         <!-- TODO add php templating here to get all the categories-->
        <option value="United States of America">United States of America</option>
    </select>
    </div>
    <div class="mb-3">
        <label for="zip">ZIP/Postal Code</label>
        <input type="text" id="zip" class="form-control form-control-sm" name="zip"/> <!-- rmoved minlength attr, and required attr to see php error messages-->
    </div>
    <button type="button" onclick="purchase(this.form, '<?php echo get_user_id(); ?>', '<?php se($_SESSION, 'total_cost', '', true); ?>')" class="btn btn-primary">Place Order</button>
</form>
</div>
<?php
require(__DIR__ . "/../../partials/flash.php");
?>