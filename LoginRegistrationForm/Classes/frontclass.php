<?php include_once "lib/Database.php"; ?>
<?php include_once "helpers/Format.php"; ?>
<?php include_once "lib/Session.php"; ?>

<?php
	/**
	* 
	*/
	class Front
	{
		private $db;
		private $fm;

		public function __construct()
	
		{
			$this->db = new Database();
			$this->fm = new Format();
		}	

		public function getjobList(){
		   	$query = "SELECT p.*, c.levelName, j.jobtitle, r.degName, s.deptName
				FROM tbl_user_job as p, tbl_job_level as c, tbl_jobtitle as j, tbl_degree as r, tbl_department as s
				WHERE p.levelId = c.levelId AND p.jId = j.jId AND p.degId = r.degId AND p.dId = s.dId
				ORDER BY p.jsId DESC";

		
		$value = $this->db->select($query);
		return $value;
	}

	 public function applyForjob($userId, $jsid){
	 	$jsid	 = mysqli_real_escape_string($this->db->link, $jsid);
		$userId	 = mysqli_real_escape_string($this->db->link, $userId);
		$Cquery  = "SELECT * FROM tbl_apply WHERE jsId = '$jsid' AND userId = '$userId'";
		$check = $this->db->select($Cquery);
		if ($check) {
			$msg = "<span style='color:red;'>Already Applied For This Job!!</span>";
			return $msg;
		}
		$query  = "SELECT * FROM tbl_user_job WHERE jsId = '$jsid'";
		$result = $this->db->select($query)->fetch_assoc();
		if ($result) {
			$jsid = $result['jsId'];
			$jId = $result['jId'];
			$dId = $result['dId'];
			$levelId = $result['levelId'];
			$ldApplication = $result['ldApplication'];
			$degId = $result['degId'];
			$mimcomp = $result['mimcomp'];
			$mxmcomp = $result['mxmcomp'];

			$expDate = $result['expDate'];
			$prerequisite = $result['prerequisite'];

			$query = "INSERT INTO tbl_apply(jsId, userId, jId, dId, levelId, lastdApplication, degId, mimcomp, mxmComp, expDate, prerequisite) VALUES('$jsid', '$userId', '$jId', '$dId', '$levelId', '$ldApplication', '$degId', '$mimcomp', '$mxmcomp', '$expDate', '$prerequisite')";
			$inserted_row = $this->db->insert($query);
			if ($inserted_row) {
				$msg = "Successfully Applied";
				return $msg;
			}else{
				$msg = "Not Successfully Applied";
				return $msg;
			}
			}
	 }
	 
	 public function getapplyUser($userId){
	     $query = "SELECT * FROM tbl_apply WHERE userId = '$userId' AND status='1'";
	     $result = $this->db->select($query);
	     return $result;
	 }

	 public function getjCadidate($userId){
				$query = "SELECT p.*, c.levelName, j.jobtitle, r.degName, s.deptName, a.userName
				FROM tbl_apply as p, tbl_job_level as c, tbl_jobtitle as j, tbl_degree as r, tbl_department as s, tbl_user_reg as a
				WHERE p.levelId = c.levelId AND p.jId = j.jId AND p.degId = r.degId AND p.dId = s.dId AND p.userId = a.regId AND userId = '$userId'";

				$value = $this->db->select($query);
				return $value;
		}	

		public function getScheduleby($userId){
			$query = "SELECT * FROM tbl_apply WHERE status = '1' AND userId = '$userId'";
			$result = $this->db->select($query);
			return $result;
		}

		

		public function getAllIschedule($userId){

			$query = "SELECT p.*, j.jobtitle
			 FROM tbl_interview as p, tbl_jobtitle as j
			 WHERE p.jId = j.jId AND p.userId = '$userId'";
			$result = $this->db->select($query);
			return $result;
			
			}


		public function requestscheduleby($jobId){
			$query = "SELECT * FROM tbl_interview WHERE jId = '$jobId' limit 1";
			$value = $this->db->select($query);
			return $value;
		}

		public function getIscheduleby($userId, $jobId){
			$query = "SELECT p.*, j.jobtitle
				FROM tbl_interview as p, tbl_jobtitle as j
				WHERE p.jId = j.jId
				AND p.jId = '$jobId' AND p.userId = '$userId'";
			$value = $this->db->select($query);
			return $value;
		}

		public function getIsscheduleby($userId){
			$query = "SELECT p.*, j.jobtitle
				FROM tbl_interview as p, tbl_jobtitle as j
				WHERE p.userId = '$userId'";
			$value = $this->db->select($query);
			return $value;
		}
		public function getJobsid($jobId){
			$query = "SELECT * FROM tbl_interview WHERE jId = '$jobId'";
			$result = $this->db->select($query);
			return $result;
		}
		 public function getJobapp($userId, $jId){
	 	$query = "SELECT app FROM tbl_apply WHERE userId = '$userId' AND jId = '$jId'";
	 	$result = $this->db->select($query);
	 	return $result;
	 }


		public function disapperSchedule($id, $userId, $dtime, $ddate, $reason){
			$dtime		 = mysqli_real_escape_string($this->db->link, $dtime);
			$ddate		 = mysqli_real_escape_string($this->db->link, $ddate);
			$reason		 = mysqli_real_escape_string($this->db->link, $reason);	

			/*$Equery = "SELECT * FROM tbl_mail";
			$value  = $this->db->select($query)->fetch_assoc();
			$hrmail = $value['email'];*/

			$Mquery = "SELECT * FROM tbl_user_reg WHERE regId = '$userId'";
			$result = $this->db->select($Mquery)->fetch_assoc();
			$email = $result['email'];
			$userName = $result['userName'];


				if ($dtime == "" || $ddate == "" || $reason == "") {
					$err = "Field Must Not Be Empty!!";
					return $err;
				}else{
					$query = "INSERT INTO `tbl_disappertime`(`jId`, `userId`, `dtime`, `ddate`, `reason`) VALUES ('$id','$userId','$dtime','$ddate','$reason')";
					$inserted_row = $this->db->insert($query);

					if ($inserted_row) {
									?>
                                <script>
                                alert('Interview Schedule Has been Sent To this Candidate ?>!');
                                window.location.href='create_schedule.php';
                                </script>
                            <?php


							$headers = 'From: '.$email."\r\n".
							 
							'Reply-To: '.$email."\r\n" .
							 
							'X-Mailer: PHP/' . phpversion();

							$email_to = "recruitment@keal.com.bd";
							$email_subject= " Request For InterView Re-Schedule";
							$email_message= "Dear Recruitment Officer,
 
                I have submitted a request for Interview Reschedule. I would be 
                grateful if you kindly approve this request for Reschedule. I 
                regret any inconvenience caused by this request. I understand 
                that it is your prerogative to approve this request. I also 
                confirm that I will not make any complaint if the request is not
                approved.
 
                I will wait for your confirmation.
 
                Thank you,
                $userName";


							$headers1 = 'From: '.$email_to."\r\n".
							 
							'Reply-To: '.$email_to."\r\n" .
							 
							'X-Mailer: PHP/' . phpversion();

							$email_subject1= "InterView Re-Schedule Request";
							$email_message1= "Dear $userName,

							Thank you for your interest in career in our company
							Congratulations!!

							You Request For InterView Re-Schedule Has been successfully Submited by you. Your Requested time is Pending now.
								 
								Please visit your profile by clicking the following link for update information:
								 
								https://recruitment.keal.com.bd/
								 
								Prior to select your option please go to the following link and register yourself please make sure that you have completed all your academic information to the following portal:
								 
								https://recruitment.keal.com.bd/
								 
								Note: Your email is your username and put your mobile number as your password
								 
								Please do not write back to this email. If you are otherwise not contacted by us your interview date is confirmed. However, if you want to change your date please write an email request or send a request using the following link:
								 
								https://recruitment.keal.com.bd/
								 
								If  you do not want to participate please do not forget to click on the following link:
								 
								 
								Please bring along the following items during the interview:
								 
								1) All your Transcripts till date
								2) An updated CV Printed. If not you may take a print out of the same on the spot during interview
								3) Your Photo Identity Document, such as Passport, NID, Birth Registration etc.
								4) Academic Credentials.
								 
								See you then,
								 
								Thank you
								 
								Recruitment Office
								Kyoto Engineering & Automation Ltd
								B2 House 64 Block B Road 3
								Niketon Gulshan Dhaka 1212
								 
								Emergency Contact Numbers:
								 
								01844046621
								01844046666
								01844046677
							 
							";

							$email_message2= 'Date'.$date."\r\n";
							mail("<$email_to>","$email_subject","$email_message","$headers");

							mail("<$email>","$email_subject1","$email_message1","$headers1");
				}
			}
		}
			
				
			
		

		public function getscheduleStatus($userId, $jobId){
			$query = "SELECT status FROM tbl_disappertime WHERE userId = '$userId' AND jId = '$jobId'";
			$result = $this->db->select($query);
			return $result;
		}

		public function getPossibleschedule(){
			
			$query = "SELECT * FROM tbl_date ORDER BY id DESC LIMIT 5";
			$result = $this->db->select($query);
			return $result;
		}

		//give response against schedule
		public function responseForjob($status, $userId){
			$status = mysqli_real_escape_string($this->db->link, $status);
			$userId = mysqli_real_escape_string($this->db->link, $userId);
			
			$Mquery = "SELECT * FROM tbl_user_reg WHERE regId = '$userId'";
			$result = $this->db->select($Mquery)->fetch_assoc();
			$email = $result['email'];
			$userName = $result['userName'];
			
			$Squery = "SELECT status FROM tbl_interview WHERE  userId = '$userId'";
			$result = $this->db->select($Squery)->fetch_assoc();
			
			if($result['status'] == "2"){
			   
			    $msg= "Not Participating";
			    
			}else{
			    $msg = "Participation";
			    
			}


			if ($status == "") {
				$msg = "Your Option Is Not Selected!!";
				return $msg;
			}else{
				$query = "UPDATE tbl_interview
								SET
				 				status = '$status'
				 				WHERE userId = '$userId'";
				 $update_row = $this->db->update($query);

				if ($update_row) {
						?>
                                <script>
                                alert('Your Response Has been addedd?>!');
                                window.location.href='my_jobs.php';
                                </script>
                            <?php


							$headers = 'From: '.$email."\r\n".
							 
							'Reply-To: '.$email."\r\n" .
							 
							'X-Mailer: PHP/' . phpversion();

							$email_to = "recruitment@keal.com.bd";
							$email_subject= "Job Interview Response From Applicant";
							$email_message= "This person has Response For Job Interview:
							Name : $userName
							Email : $email
							This Person Is confirm  $msg in the Interview
							";
							
							


							$headers1 = 'From: '.$email_to."\r\n".
							 
							'Reply-To: '.$email_to."\r\n" .
							 
							'X-Mailer: PHP/' . phpversion();

							$email_subject1= "Job Interview Response From You!";
							$email_message1= "Dear $userName,

							Thank you for your interest in career in our company
							Congratulations!!

							We confirm your  $msg.
								 
								Thank you
								 
								Recruitment Office
								Kyoto Engineering & Automation Ltd
								B2 House 64 Block B Road 3
								Niketon Gulshan Dhaka 1212
								 
								Emergency Contact Numbers:
								 
								01844046621
								01844046666
								01844046677
							 
							";

							$email_message2= 'Date'.$date."\r\n";
							mail("<$email_to>","$email_subject","$email_message","$headers");

							mail("<$email>","$email_subject1","$email_message1","$headers1");
				}
			}
		}


		public function getinterviewstatus($userId, $jobId){
			$query = "SELECT * FROM tbl_interview WHERE userId = '$userId' AND jId = '$jobId'";
			$result = $this->db->select($query);
			return $result;		}
			
			public function getAlluserdata($userId){
			$query = "SELECT * FROM tbl_user_reg WHERE regId = '$userId'";
			$result = $this->db->select($query);
			return $result;
		}

	public function getuseraddressby($userId){
		$query = $query = "SELECT p.*, j.distName
				FROM tbl_address as p,  tbl_district as j
				WHERE p.diId = j.distId AND userId = '$userId'";
			$result = $this->db->select($query);
			return $result;
	}

	public function getusertrainingby($userId){
		$query = "SELECT * FROM tbl_training WHERE userId = '$userId' limit 1";
			$result = $this->db->select($query);
			return $result;
	}
		public function getusertrainingby($userId){
		$query = "SELECT * FROM tbl_training WHERE userId = '$userId'";
			$result = $this->db->select($query);
			return $result;
	}

	public function getworking($userId){
		$query = "SELECT * FROM tbl_workingexperience WHERE userId = '$userId'";
			$result = $this->db->select($query);
			return $result;
	}

	}?>