<form method="post" action="<?php echo base_url();?>index.php/activate-account-submit">

    <input type="hidden" name="user_id" value="<?php echo $user->id; ?>">

    <label>New Password</label><br>
    <input type="password" name="password" required><br><br>

    <label>Confirm Password</label><br>
    <input type="password" name="confirm_password" required><br><br>

    <button type="submit">Activate Account</button>

</form>