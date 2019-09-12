<?php
session_start();

require_once "../../connection/connect.php";

$id=$_GET['id'];
$result=mysqli_query($db,"SELECT * FROM student WHERE student_id='$id'");
//$result=mysqli_query($db,"SELECT * FROM student WHERE 1");


$ins_res=mysqli_query($db,"SELECT * FROM institution_details WHERE serial='1'");
$ins_row=mysqli_fetch_array($ins_res);


include("../../mpdf/mpdf.php");
$mpdf=new mPDF('','A4','11','times-new-roman');


$html = "<html>
<head>
  <style>
  body
  {
    font-family:times-new-roman;
    line-height: 25px;

  }
  b
  {
    letter-spacing: 1px;
  }
  .field-name
  {
    width: 150px;
    float: left;
    display: inline;
  }
  .field-value
  {
    float: left;
    width: 400px;
    display: inline;
  }
  .images
  {
    float: right;
  }
  .logo-div
  {
    width: 100px;
    float: left;
    display: inline;
  }
  .ins-div
  {
    text-align: center;
    float: left;
    display: inline;
  }
  </style>
</head>";

$html .= "<body>";
$count=0;
while($row=mysqli_fetch_array($result))
{

$html .= "<div class='logo-div'><img src='../../logo/instituition_logo.jpg' width='100px'></div>";
$html .= "<div class='ins-div'>";
$html .= "<div style='font-family:nikosh; font-size:23px'><b>".$ins_row['name']."</b></div>";
$html .= "<div style='font-family:nikosh; font-size:18px'>".$ins_row['address']."</div>";
$html .= "Estd: <b>".date_format(date_create($ins_row['estd']),'m/d/Y')."</b>, EIIN: <b>".$ins_row['eiin']."</b>, Phone: <b>".$ins_row['phone']."</b><br>";
$html .= "Email: <b>".$ins_row['email']."</b>, Web: <b>".$ins_row['web']."</b><br>";
$html .= "</div>";

$class_res=mysqli_query($db,"SELECT * FROM class WHERE class_id='".$row['student_class']."'");
$class_row=mysqli_fetch_array($class_res);

$html .= "
  <hr>
  <span style='font-size: 18px'><b>Personal Information</b></span>
  <hr>
  <div class='field-name'>
    <b>Name</b>   <br>
    ID    <br>
    Class <br>
    Section <br>
    Shift <br>
    Group <br>
    Roll  <br>
    Gender <br>
    Religion <br>
    Date of Birth <br>
    Blood Group <br>
    Phone <br>
    Present Address <br>
    Permanent Address <br>
    <br>
    <u>Father Details</u><br>
    Name<br>
    Phone<br>
    Occupation<br>
    Income<br>
    Office Address<br>

    <br>
    <u>Mother Details</u><br>
    Name<br>
    Phone<br>
    Occupation<br>
    Income<br>
    Office Address<br>
  </div>
  <div class='field-value'>
    : <b>".$row['student_name']."</b><br>
    : ".$row['student_id']."<br>
    : ".$class_row['class_name']."<br>
    : ".$row['student_section']."<br>
    : ".$row['student_shift']."<br>
    : ".$row['student_group']."<br>
    : ".$row['student_roll']."<br>
    : ".$row['student_gender']."<br>
    : ".$row['student_religion']."<br>
    : ".$row['student_dob']."<br>
    : ".$row['student_blood']."<br>
    : ".$row['student_phone']."<br>
    : ".$row['student_present_address']."<br>
    : ".$row['student_par_address']."<br>
    <br><br>
    : ".$row['father_name']."<br>
    : ".$row['father_phone']."<br>
    : ".$row['father_occupation']."<br>
    : ".$row['father_income']."<br>
    : ".$row['father_office']."<br>
    <br><br>
    : ".$row['mother_name']."<br>
    : ".$row['mother_phone']."<br>
    : ".$row['mother_occupation']."<br>
    : ".$row['mother_income']."<br>
    : ".$row['mother_office']."<br>
  </div>";

  $html .= "<div class='images'>
    <img src='../".$row['student_photo']."'><br><br>";
if(file_exists("../".$row['father_photo'])&&$row['father_photo']!='')
{
    $html .="<img src='../".$row['father_photo']."'><br><br>";
}
else {
      $html .="<img src='../../unisex-avatar.png'><br><br>";
}

if(file_exists("../".$row['mother_photo'])&&$row['mother_photo']!='')
{
    $html .="<img src='../".$row['mother_photo']."'><br><br>";
}
else {
    $html .="<img src='../../unisex-avatar.png'><br><br>";
}

$html .= "</div>";
$count++;
//if($result->num_rows!=$count) $html .= "<pagebreak>";
}
$html .= "</body>
</html>";

$mpdf->SetTitle("Student Profile ".$id);
$mpdf->WriteHTML("$html");
$file="Student_Profile_".$id.".pdf";
$mpdf->Output($file, "I");

?>
