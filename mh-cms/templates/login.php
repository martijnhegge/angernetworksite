<?php include "includes/header.php" ?>
<div class="logo-big-div"><img class="logo-big" src="../media/logo2.png" alt="MH-Development | Voor al uw online webservices en software!" /></div>
<form action="index.php?action=login" method="post" class="login-form">
    <input type="hidden" name="login" value="true" />

<?php if ( isset( $results['errorMessage'] ) ) { ?>
    <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
<?php } ?>

    <ul>
    	<li>
        	<label for="username">Gerbuikersnaam</label>
        	<input type="text" name="username" id="username" class="username" placeholder="Gerbuikersnaam" required autofocus maxlength="20" />
      	</li>

      	<li>
        	<label for="password">Wachtwoord</label>
        	<input type="password" name="password" id="password" class="password" placeholder="wachtwoord" required maxlength="20" />
      	</li>
    </ul>
    <div class="buttons">
      	<input type="submit" class="login-button" name="login" value="Login" />
    </div>
</form>
<?php include "includes/footer.php" ?>

