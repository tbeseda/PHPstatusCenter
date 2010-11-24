<?
	require_once( $_SERVER['DOCUMENT_ROOT'] . '/include/db.inc.php' );
	require_once( $_SERVER['DOCUMENT_ROOT'] . '/include/session.inc.php' );
	require_once( $_SERVER['DOCUMENT_ROOT'] . '/include/ldap.inc.php' );

	$head = new StdClass;
	$head->title = 'IT Status &#187; Login';

	$go = ( isset( $_POST['go'] ) ) ? $_POST['go'] : $_GET['go'];
	if ( $go == '' ) $go = '/';

	if ( $_POST['username'] != '' )
	{
	
		$username = stripslashes( $_POST['username'] );
		$password = stripslashes( $_POST['password'] );

		if ( ldap_confirm_login( $username , $password ) )
		{
		
			setcookie( 'i_user' , $username , time() + 60 * 60 * 24 * 30 , '/' , '.integer.com' );
			$_SESSION['user'] = $username;
			header( 'Location: ' . $go );

		} // if
		else $error = '<p><span class="error">Login failed</span>. If you are unable to login due to your account being locked or a forgotten password, call the Help Desk at (303) 393-3030 to have your password reset.</p>';
	
	} // if

	require_once( $_SERVER['DOCUMENT_ROOT'] . '/include/header.inc.php' );

	?>
	<div class="centered">
		<h2>Login</h2>
		<?= $error ?>
		<form action="/login/" method="post" class="margin-top">
			<table class="login headers-left">
				<tr>
					<th><label>Username</label></th>
					<td><input type="text" name="username" size="24" /></td>
				</tr>
				<tr>
					<th><label>Password</label></th>
					<td><input type="password" name="password" size="24" /></td>
				</tr>
			</table>
			<input type="hidden" name="go" value="<?= $go ?>" />
			<div class="bottom-space"></div>
			<div class="fixed-bottom"><input type="submit" value="Login" /></div>
		</form>
	</div>
	<?

	require_once( $_SERVER['DOCUMENT_ROOT'] . '/include/footer.inc.php' );

?>