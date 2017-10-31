<?php 
     include 'lib/Session.php';
       Session::checkSession();
?>
<?php 
    
     include 'lib/database.php';
     include 'helpers/format.php';
?>
<?php 
$userId = Session::get("userId");
include_once "Classes/frontclass.php";

?>
<?php
  $allF = new Front();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Kyoto Recruitment System</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
     <link href="css/buton.css" rel="stylesheet">
     <link href="css/process.css" rel="stylesheet">
     <link href="css/mystyle.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <nav class="navbar navbar-default">
                <div class="container-fluid">
                  <!-- Brand and toggle get grouped for better mobile display -->
                  <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                      <span class="sr-only">Toggle navigation</span>
                      <span class="icon-bar"></span>
                      <span class="icon-bar"></span>
                      <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">Kyoto Job Portal</a>
                  </div>

                  <!-- Collect the nav links, forms, and other content for toggling -->
                  <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                      <li><a href="index.php" class="w3-bar-item"> Home </a></li>
                      <li><a href="personalInfo.php" class="w3-bar-item">
                       My Info<br/>
                       <?php
                        $getdata = $allF->getinformation($userId);
                         if ($getdata) {
                         while ($data = $getdata->fetch_assoc()) {
                             ?>

                             <?php 
                              if ($userId == $data['userId']) {
                                $userDob =  $data['dob'];

//Create a DateTime object using the user's date of birth.
$dob = new DateTime($userDob);

//We need to compare the user's date of birth with today's date.
$now = new DateTime();

//Calculate the time difference between the two dates.
$difference = $now->diff($dob);

//Get the difference in years, as we are looking for the user's age.
$age = $difference->y;

//Print it out.
echo "<span style='color:green'>$age years</span>"; 
                               }else{
                                echo "No Info";
                              }
                             ?>



<?php } } ?>


                       </a></li>
                      <li><a href="basicinfo.php" class="w3-bar-item">
                      My Address<br/>
                       <?php
                        $getdata = $allF->getuseraddressby($userId);
                         if ($getdata) {
                          while($value = $getdata->fetch_assoc()){
                            ?>
                            <?php 
                              if ($userId == $value['userId']) {
                               echo $value['area']; echo $value['distName'];
                              }else{
                                 echo "No Address";
                              }
                            ?>
         
                            <?php } } ?>

                      </a></li>
                      <li><a href="my_details.php" class="w3-bar-item">My Portfolio</a></li>
                      <li><a href="education.php" class="w3-bar-item"> My Educational Details </a></li>
                      <li><a href="professionalTraining.php" class="w3-bar-item"> My Professional Training<br/>

                        <?php
                        $getdata = $allF->getusertrainingby($userId);
                         if ($getdata) {
                          echo "<span style='color:red'>No Training</span>"; 
                              }else{
                                 echo "<span style='color:green'>Training added</span>";
                              }

                             ?> </a></li>
                      <li><a href="workExperience.php" class="w3-bar-item">My Work Experience <br/>
                      <?php
                        $getdata = $allF->getworking($userId);
                         if (!$getdata) {
                               echo "<span style='color:red'>No Experience</span>"; 
                              }else{
                                 echo "<span style='color:green'>Experience added</span>";
                              }
                               ?>
                       </a></li>
                       
                    </ul>
                    
                    <ul class="nav navbar-nav navbar-right">
                      <li><a href="#"><?php 
                      
                      $getdata = $allF->getAlluserdata($userId);
                    if ($getdata) {
                    while($value = $getdata->fetch_assoc()){
       
                    ?>
                    
                        <img src="<?php echo $value['image'];?>" alt="image" height="16" width="12px" />
                    
                    <?php } } ?>&nbsp;<?php echo Session::get('userName');?></i></a></li>
                            <?php
                                if (isset($_GET['action']) && $_GET['action'] == "logout") {
                                    Session::destroy();                               
                                     }
                            ?>
                            
                          <li><a href="?action=logout">signout</a></li>
                                                                      
                    </ul>
                      
                      <!-- Slider begins here -->
                      
                      <!-- Slider ends here-->
                  </div><!-- /.navbar-collapse -->
                </div><!-- /.container-fluid -->
</nav>