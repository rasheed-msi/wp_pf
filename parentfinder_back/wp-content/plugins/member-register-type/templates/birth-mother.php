<input type="hidden" name="user_type" value="birth_mother">

<p>
    <label for="">Adoption Agency</label>
    <select class="input" name="agency_id">
        <?php foreach (mrt_get_usersOfRole('adoption_agency') as $key => $user): ?>
            <option value="<?php echo $user->ID; ?>"><?php echo $user->display_name; ?></option>
        <?php endforeach; ?>

    </select>
</p>

<p>
    <label for="first_name">First Name</label>
    <input type="text" name="first_name" id="first_name" class="input">
</p>
<p>
    <label for="last_name">Last Name</label>
    <input type="text" name="last_name" id="last_name" class="input">
</p>
<p>
    <label>Gender</label>
    <label>
        <input type="radio" name="gender" value="male">
        Man
    </label>
    <label>
        <input type="radio" name="gender" value="female">
        Woman
    </label>
</p>
<p>
    <label>Marital Status</label>
    <label>
        <input type="radio" name="marital_status" value="single">
        Single
    </label>
    <label>
        <input type="radio" name="marital_status" value="couple">
        Couple
    </label>
</p>
