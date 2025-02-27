 
###### register.php ######
<?php 
	if ( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' )
	{
  # Connect to the database.
  require ('connect_db.php'); 
 
  # Initialize an error array.
  $errors = array();
 
  # Check for first name.
  if ( empty( $_POST[ 'first_name' ] ) )
  { $errors[] = 'Enter your first name.' ; }
  else
  { $fn = mysqli_real_escape_string( $link, trim( $_POST[ 'first_name' ] ) ) ; }
 
  # Check for last name.
  if ( empty( $_POST[ 'last_name' ] ) )
  { $errors[] = 'Enter your last name.' ; }
  else
  { $ln = mysqli_real_escape_string( $link, trim( $_POST[ 'last_name' ] ) ) ; }
 
  # Check for email.
  if (empty( $_POST[ 'email' ] ) )
  { $errors[] = 'Enter your email.' ; }
  else
  { $e = mysqli_real_escape_string( $link, trim( $_POST[ 'email' ] ) ) ; }
 
  # Check for password and ensure both pass1 & pass2 inputs are identical.
  if (empty( $_POST[ 'pass1' ] ) )
  { $errors[] = 'Enter your password.' ; }
  elseif ( $_POST[ 'pass1' ] != $_POST[ 'pass2' ] )
	{ $errors[] = 'Password do not match!' ; }
  else
	{ $p = mysqli_real_escape_string( $link, trim( $_POST[ 'pass1' ] ) ) ; }
 
  # Check for an existing email.
  if ( empty( $errors ) )
  {
	  $q = "SELECT user_id FROM users WHERE email='$e'" ;
	  $r = @mysqli_query ( $link, $q ) ;
	  if (mysqli_num_rows( $r ) != 0 ) $errors[] = 'Email address already registered. Sign into your account now.' ;
  }
 
   # On success data into your table. on database.
  if ( empty( $errors ) ) 
  {
    $q = "INSERT INTO users (first_name, last_name, email, pass, reg_date) 
	VALUES ('$fn', '$ln', '$e', SHA2('$p', 256), NOW() )";
    $r = @mysqli_query ( $link, $q ) ;
    if ($r)
    { echo '<p>Your account has been registered!</p> 
			<a class="alert-link" href="home.html"</a>'; }
 
    # Close database connection.
    mysqli_close($link); 
 
    exit();
  }
 
  # Or report errors.
  else 
  {
    echo '<p>The following error(s) occurred:</p>' ;
    foreach ( $errors as $msg )
    { echo "$msg<br>" ; }
    echo '<p>Please try again.</p></div>';
    # Close database connection.
    mysqli_close( $link );
 
  }  
}
?>
 
###### login_verification.php ######
<?php
 
# Check form submitted.
if ( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' )
{
  # Open database connection.
  require ( 'connect_db.php' ) ;
 
  # Get connection, load, and validate functions.
  require ( 'login_tools.php' ) ;
 
  # Check login.
  list ( $check, $data ) = validate ( $link, $_POST[ 'email' ], $_POST[ 'pass' ] ) ;
 
  # On success set session data and display logged in page.
  if ( $check )  
  {
    # Access session.
    session_start();
    $_SESSION[ 'user_id' ] = $data[ 'user_id' ] ;
    $_SESSION[ 'first_name' ] = $data[ 'first_name' ] ;
    $_SESSION[ 'last_name' ] = $data[ 'last_name' ] ;
    load ( 'home.php' ) ;
  }
  # Or on failure set errors.
  else { $errors = $data; } 
 
  # Close database connection.
  mysqli_close( $link ) ; 
}
 
# Continue to display login page on failure.
include ( 'login.php' ) ;
?>
 
###### login_tools.php ######
<?php
    # Function to load specified or default URL.
function load( $page = 'home.php' )
{
  # Begin URL with protocol, domain, and current directory.
  $url = 'http://' . $_SERVER[ 'HTTP_HOST' ] . dirname( $_SERVER[ 'PHP_SELF' ] ) ;
 
  # Remove trailing slashes then append page name to URL.
  $url = rtrim( $url, '/\\' ) ;
  $url .= '/' . $page ;
 
  # Execute redirect then quit. 
  header( "Location: $url" ) ; 
  exit() ;
}
 
# Function to check email address and password. 
function validate( $link, $email = '', $pwd = '')
{
  # Initialize errors array.
  $errors = array() ; 
 
  # Check email field.
  if ( empty( $email ) ) 
  { $errors[] = 'Enter your email address.' ; } 
  else  { $e = mysqli_real_escape_string( $link, trim( $email ) ) ; }
 
  # Check password field.
  if ( empty( $pwd ) ) 
  { $errors[] = 'Enter your password.' ; } 
  else { $p = mysqli_real_escape_string( $link, trim( $pwd ) ) ; }
 
  # On success retrieve user_id, first_name, and last name from 'users' database.
  if ( empty( $errors ) ) 
  {
    $q = "SELECT user_id, first_name, last_name FROM users WHERE email='$e' AND pass=SHA2('$p',256)" ;  
    $r = mysqli_query ( $link, $q ) ;
    if ( @mysqli_num_rows( $r ) == 1 ) 
    {
      $row = mysqli_fetch_array ( $r, MYSQLI_ASSOC ) ;
      return array( true, $row ) ; 
    }
    # Or on failure set error message.
    else { $errors[] = 'Email address and password not found.' ; }
  }
  # On failure retrieve error message/s.
  return array( false, $errors ) ; 
}
?>