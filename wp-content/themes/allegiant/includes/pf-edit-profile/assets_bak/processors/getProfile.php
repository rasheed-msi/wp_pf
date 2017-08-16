<?php
	$link = mysql_connect("localhost", "root", "");
	$db = mysql_select_db("pfcomm_new");
	if (!$link) {
	    echo "Error: Unable to connect to MySQL." . PHP_EOL;
	    
	    exit;
	}
	$logid = 59;
	$retArr = array('status' => 'success','data' => array());
	$columns = 'ID,Email,FirstName,State,DateOfBirth,Password,AdoptionAgency,Facebook,Twitter,Google,Blogger,Pinerest,DateOfBirth,state,waiting,noofchildren,faith,childethnicity,childage,Adoptiontype,phonenumber,street_address,housestyle,noofbedrooms,noofbathrooms,yardsize,neighbourhoodlike,DearBirthParent,DescriptionMe,WEB_URL,Couple,childrenType,address1,address2,city,zip,Region,NickName,Specialneeds,SpecialNeedsOptions,ChildGender,ChildDesired,BirthFatherStatus,Sex,Ethnicity,Education,Religion,Occupation,Pet,RelationshipStatus,FamilyStructure,Region,Country,show_contact,allow_contact,Instagram,DearBirthParent,agency_letter';
	$stringSQL = "SELECT  " . $columns . " FROM Profiles where ID = " . $logid . "";
	$query = mysql_query($stringSQL);
	$country_code = "US";
	$state = '';

	if($row = mysql_fetch_assoc($query) ){

		$retArr['data']['Profiles'] = $row;
		$country_code = $row['Country'];
		$state = $row['State'];
	}

	$tablename = 'Profiles_draft';
	$columns = 'ID,FirstName,DateOfBirth,DescriptionMe,Sex,Ethnicity,Education,Religion,Occupation';
	$stringSQL_t = "SELECT  " . $columns . " FROM " . $tablename . " where Couple = " . $logid. "";
	$query = mysql_query( $stringSQL_t );

	if($row = mysql_fetch_assoc($query)){

		$retArr['data']['Couples'] = $row;
	}


	$tablename = 'sys_pre_values';
	$columns = '`Key`,Value,LKey';
	$cond = "`Key` = 'Ethnicityofcouple' or `Key` = 'Educationofcouple' or  `Key` = 'ReligionCouple' or `Key`= 'structureoffamily'";
	$stringSQL_Region = "SELECT  " . $columns . " FROM " . $tablename . " WHERE ".$cond."ORDER BY `sys_pre_values`.`Order` ASC";
	$query = mysql_query( $stringSQL_Region );
	
	while($row = mysql_fetch_assoc($query)){

		$key = $row['Key'];
		
		$dta = array('LKey' => $row['LKey'],'Value' => $row['Value']);
		
		if($key == 'Educationofcouple'){

			$retArr['data']['Education'][] = $dta;

		}elseif($key == 'ReligionCouple'){

			$retArr['data']['Religion'][] = $dta;

		}elseif(strtolower($key) == 'structureoffamily'){
			$retArr['data']['FamilyStructure'][] = $dta;
		}
		else{
			$retArr['data']['Ethnicity'][] = $dta;
		}
	}
		

$stringSQL_Children = "SELECT config_value as value, config_description as text FROM sys_configuration WHERE config_key = 'noofchildren' ORDER BY config_order ASC ";
$query_children     = mysql_query( $stringSQL_Children );


while($row = mysql_fetch_assoc($query_children)){
	$retArr['data']['noofchildren'][] = $row;
}


$profilenumberQ = mysql_query("SELECT  p.Profile_no as profile_number , p.Profile_year as profile_year FROM  Profiles AS p WHERE  p.ID ='$logid'");
if($profilenumber = mysql_fetch_assoc($profilenumberQ)){
	 if($profilenumber['profile_number'] != 0 ){
	 	$profile_number = $profilenumber['profile_year']."_".str_pad($profilenumber['profile_number'], 4, "0", STR_PAD_LEFT);
	 }

}
else{
 	$profile_number = '';
 }
$retArr['data']['profile_number'] = $profile_number;


$stringSQL_Letter = "SELECT label FROM `letter` WHERE profile_id=$logid";
$letterQ = mysql_query($stringSQL_Letter);
while($row = mysql_fetch_assoc($letterQ)){
	$retArr['data']['letters'][] = $row['label'];
}

$waitingSQL = "SELECT waiting_text FROM `pfcomm_mig_new`.`waiting` ";
$waitingQ = mysql_query($waitingSQL);
while($row = mysql_fetch_assoc($waitingQ)){
	$retArr['data']['waiting'][] = $row;
}

$faithSQL = "SELECT faith FROM `pfcomm_mig_new`.`faith`";
$faithQ = mysql_query($faithSQL);
while($row = mysql_fetch_assoc($faithQ)){
	$retArr['data']['faiths'][] = $row;
}

$residenceSQL = "SELECT * FROM `pfcomm_mig_new`.`residency`";
$residenceQ = mysql_query($residenceSQL);
while($row = mysql_fetch_assoc($residenceQ)){
	$retArr['data']['residense'][] = $row;
}

$neighbourhoodSQL = "SELECT * FROM `pfcomm_mig_new`.`neighborhood`";
$neighbourhoodQ = mysql_query($neighbourhoodSQL);
while($row = mysql_fetch_assoc($neighbourhoodQ)){
	$retArr['data']['neighborhood'][] = $row;
}

$petSQL = "SELECT * FROM `pfcomm_mig_new`.`pet`";
$petQ = mysql_query($petSQL);
while($row = mysql_fetch_assoc($petQ)){
	$retArr['data']['pets'][] = $row;
}

$relationShipSQL = "SELECT * FROM `pfcomm_mig_new`.`relationship_status`";
$relationShipQ = mysql_query($relationShipSQL);

while($row = mysql_fetch_assoc($relationShipQ)){
	$retArr['data']['relationships'][] = $row;
}

$familySQL = "SELECT * FROM `pfcomm_mig_new`.`family_structure`";
$familyQ = mysql_query($familySQL);

while($row = mysql_fetch_assoc($familyQ)){
	$retArr['data']['family_structure'][] = $row;
}

$agePreferenceSQL = "SELECT * FROM `pfcomm_mig_new`.`age_group`";
$agePreferenceQ = mysql_query($agePreferenceSQL);

while($row = mysql_fetch_assoc($agePreferenceQ)){
	$retArr['data']['childAgeGroup'][] = $row;
}


$genderSQL = "SELECT * FROM `pfcomm_mig_new`.`gender`";
$genderQ = mysql_query($genderSQL);
while($row = mysql_fetch_assoc($genderQ)){
	$retArr['data']['gender'][] = $row;
}


$desiredSQL = "SELECT * FROM `pfcomm_mig_new`.`childdesired`";
$desiredQ = mysql_query($desiredSQL);
while($row = mysql_fetch_assoc($desiredQ)){
	$retArr['data']['desiredchild'][] = $row;
}


$birthFatheStatusSQL = "SELECT * FROM `pfcomm_mig_new`.`birthfather_status`";
$birthFatheStatusdQ = mysql_query($birthFatheStatusSQL);
while($row = mysql_fetch_assoc($birthFatheStatusdQ)){
	$retArr['data']['birthFatherStatus'][] = $row;
}

$adoption_typeSQL = "SELECT * FROM `pfcomm_mig_new`.`adoption_type`";
$adoption_typeQ = mysql_query($adoption_typeSQL);
while($row = mysql_fetch_assoc($adoption_typeQ)){
	$retArr['data']['adoption_type'][] = $row;
}

$special_needSQL = "SELECT * FROM `pfcomm_mig_new`.`special_need`";
$special_needQ = mysql_query($special_needSQL);
while($row = mysql_fetch_assoc($special_needQ)){
	$retArr['data']['special_need'][] = $row;
}

$regionsSQL = "SELECT * FROM `pfcomm_mig_new`.`regions`";
$regionsQ = mysql_query($regionsSQL);
while($row = mysql_fetch_assoc($regionsQ)){
	$retArr['data']['regions'][] = $row;
}

$countriesSQL = "SELECT * FROM `pfcomm_mig_new`.`countries`";
$countriesQ = mysql_query($countriesSQL);
while($row = mysql_fetch_assoc($countriesQ)){
	$retArr['data']['countries'][] = $row;
}

$stateSQL = "SELECT * FROM 	`pfcomm_mig_new`.`states` WHERE country_id IN (SELECT country_id FROM `pfcomm_mig_new`.`countries` WHERE country_code='".$country_code."')";
$stateQ = mysql_query($stateSQL);
while($row = mysql_fetch_assoc($stateQ))	{
	$retArr['data']['states'][] = $row;
	if($row['State'] == $state){
		$state = $row['StateCode'];
	}
	$retArr['data']['state'] = $state;
}


//mysqli_close($link);
header( "Content-type:application/json" );
echo json_encode($retArr);

?>