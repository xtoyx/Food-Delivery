
      <?php

         $con=mysqli_connect("localhost","root","1234","projectdb");
         if (mysqli_connect_errno())
         {
         echo "Failed to connect to MySQL: " . mysqli_connect_error();
         }
         $result = mysqli_query($con,"SELECT * FROM users");
         $array = array();
         $i=0;
         $arr1=array();
         while ($row = mysqli_fetch_array($result)) {
            $array[$i]["username"]=$row['2'];
            $array[$i]["email"]=$row['4'];
            $array[$i]["Locked"]=$row['8'];
            $array[$i]["tpassword"]=$row['10'];
            $array[$i]["chan_bool"]=$row['11'];
            $arr1[$row['2']]=0;
            $i++;
         }
         $users=$array;
         foreach ($users as $user) 
            if($user["Locked"]==1 || $user["chan_bool"]==1){
               $to = $user["email"];
               $subject = "This is subject";
               
               $message = "<b>Temp Password.</b>";
               $message .= "<h1>your temp".$user["tpassword"].".</h1>";
               $message .="<a href='https://localhost/project/checktpass.php'>Here</a>";
               
               $header = "From:abc@somedomain.com \r\n";
               $header .= "MIME-Version: 1.0\r\n";
               $header .= "Content-type: text/html\r\n";
               $retval = mail ($to,$subject,$message,$header);
               
               if( $retval == true ) {
                  echo "Message sent successfully...";
               }else {
                  echo "Message could not be sent...";
               }
            }
      ?>