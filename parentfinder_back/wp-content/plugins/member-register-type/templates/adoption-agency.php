<?php 
global $mrt_profile;
?>
<input type="hidden" name="user_type" value="adoption_agency">
<p>
    <label for="">Agency or Attorney Name</label>
    <input type="text" name="agency_attorney_name" id="agency_attorney_name" class="input" value="<?php echo $user_info['agency_attorney_name']; ?>">
</p>
<p>
    <label for="agency_website">Agency Website</label>
    <input type="text" name="agency_website" id="agency_website" class="input" value="<?php echo $user_info['agency_website']; ?>">
</p>
<!--<p>
    <label>Services</label>
    <label>
        <input type="checkbox" name="services[]" value="family_marketing">
        Family Marketing
    </label>
    <label>
        <input type="checkbox" name="services[]" value="child_marketing">
        Child Marketing
    </label>
</p>-->

<p>
    <label for="first_name">First Name: </label>
    <input type="text" name="first_name" id="first_name" value="<?php echo $user_info['last_name']; ?>">
</p>
<p>
    <label for="last_name">Last Name</label>
    <input type="text" name="last_name" id="last_name" value="<?php echo $user_info['last_name']; ?>">
</p>

<p>
    <label for="phone">Phone Number</label>
    <input type="text" name="phone" id="phone" class="input" value="<?php echo $user_info['phone']; ?>" required>
</p>
<p>
    <label for="street_address">Street Address</label>
    <input type="text" name="street_address" id="street_address" value="<?php echo $user_info['street_address']; ?>" required>
</p>
<p>
    <label for="city">City</label>
    <input type="text" name="city" id="city" value="<?php echo $user_info['city']; ?>" class="input" required>
</p>
<p>
    <label for="state">State</label>
    <input type="text" name="state" id="state" value="<?php echo $user_info['state']; ?>" class="input" required>
</p>
<p>
    <label for="zip">Postal / Zip Code</label>
    <input type="text" name="zip" id="zip" value="<?php echo $user_info['zip']; ?>"  class="input" required>
</p>