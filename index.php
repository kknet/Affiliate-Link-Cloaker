<link rel="stylesheet" href="normalize.css">
<link rel="stylesheet" href="style.css">
<script src="jquery.min.js"></script>
		
<div class="container">
  <div class="row header">
    <h1>Link Doctor &nbsp;</h1>
    <h3>Fill Up Details below!</h3>
  </div>
  
  
  <div class="row body">
 
  
				<form  role="form" method="post" action="config.php">
					      
      <ul>
	  
	  <li>
<span id="dlink" >
						<input type="hidden" name="dlinkcount" id="dlinkcount" value="1">
						<label  >Desktop Link's</label>
						<input type="text" name="dl1" placeholder="http://" value="">
</span>	
					    <input type="button"  value="+" id="adddl">

						</li><li>
<span id="mlink" >
						<input type="hidden" name="dlinkcount" id="mlinkcount" value="1">
						<label  >Mobile Link's</label>
						<input type="text"   name="ml1" placeholder="http://" value="">
</span>	
						<input type="button" value="+" id="addml">
					
</li><li>
					
<span id="dpage" >
				<input type="hidden" name="dlinkcount" id="dpagecount" value="1">
				<label  >Decoy Page Link </label>
				<input type="text"   name="dp1" placeholder="http://" value="">
						
</span>	
				<input type="button" value="+" id="adddp">
				</li><li>

					
					   <label>Send Logs to email</label>
						<select  style="width:150px" id="x8" name="x8"> 
						<option value="" selected="selected"></option>
						<option value="true" >Yes, Once A Day</option>
						<option value="false" >No Thanks !</option>
						</select>
			</li><li>
						<label  >Send Logs TO Email Below </label>
				        <input id="x5" name="x5" type="text" maxlength="255" value="yourmail@domain.com"/>
				
					</li><li>	
						<label  >Enable logs And Tracking </label>
						<select  style="width:150px" id="x10" name="x10"> 
						<option value="" selected="selected"></option>
						<option value="true" >Yes Tracking Traffic</option>
						<option value="false" >No Dont</option>
						</select>
						</li><li>
						<label>Deny Tor Traffic </label>
						<select  style="width:150px" id="x11" name="x11"> 
						<option value="" selected="selected"></option>
						<option value="true" >Yes Block Tor</option>
						<option value="false" >Allow Tor</option>
						</select>
						</li><li>
						<label  >Deny Proxy Connection </label>
						<select  style="width:150px" id="x12" name="x12"> 
						<option value="" selected="selected"></option>
						<option value="true" >Block Proxy Connections</option>
						<option value="false" >Allow Proxy</option>
						</select>
						
						</li><li>
						
						<label  >Fetch Page [Important]  </label>
						<select  style="width:150px" id="x13" name="x13"> 
						<option value="" selected="selected"></option>
						<option value="true" >Yes Plz !</option>
						<option value="false" >DO 301 Redirect</option>
						</select>
						
						</li>
						
						
						      <li><div class="divider"></div></li>
							  
							  <li>
						<label  >Redirect Traffic  </label>
						<select  style="width:150px" id="x14" name="x14"> 
						<option value="" selected="selected"></option>
						<option value="true" >Yes Redirect Traffic</option>
						<option value="false" >No Accept All Refers</option>
						</select>
						
						</li><li>
						
  
		
		
<span id="rtflink" >
					<input type="hidden" name="rtflinkcount" id="rtflinkcount" value="1">
					<label  >Redirect Domain </label>
					<input type="text"   name="rtf1" placeholder="bing.com" value="">
</span>	
					<input type="button"  value="+" id="addrtf">
					</li>
					<li>
<span id="rttlink" >
					<input type="hidden" name="rttlinkcount" id="rttlinkcount" value="1">
					<label  >Forward To</label>
					<input type="text"   name="rtt" placeholder="http://besoeasy.com" value="">
</span>	
					</li>

      <li><div class="divider"></div></li>
					
						<li>
					<label >Your Cloaking Key  </label>
					<input id="x16" name="x16"  maxlength="255" value="" type="text">
					</li>
					
					<li>
					<input class="btn btn-submit" id="submit" name="submit" type="submit" value="Send" >
                           <small>or press <strong>enter</strong></small>
		  
		  </li>
						
					
					      
      </ul>
				</form> 
		  </div>
</div>
    
    
    
   
	
	
	
	
	
	
	<script type="text/javascript">

	
	$(document).ready(function(){
	/* Add Redirect From Links */
	$("#addrtf").click(function () {
	
	var c= $('#rtflinkcount').val();
	
	if(c==5)
	{
		alert('Maximum 5 Links!');
		return;
	}
	
	c++;
	$('#rtflinkcount').val(c);
	var msg='';
	msg +='<input type="text"  id="rtf" name="rtf' + c + '" placeholder="google.com" value="">';
	$('#rtflink').append(msg);
	
	});
	
	});
	
	
	
	
	
	
	
	
	$(document).ready(function(){
	/* Add Desktop Links */
	
	/* Add Desktop Links */
	$("#adddl").click(function () {
	
	
	
	/* Get counter of desktop links */
	
	var c= $('#dlinkcount').val();
	
	if(c==5)
	{
		alert('Maximum 5 Desktop Links!');
		return;
	}
	
	
	/* Increement counter  */
	c++;
	$('#dlinkcount').val(c);
	
	var msg='';
	msg +='<input type="text"  id="dlink" name="dl' + c + '" placeholder="http://" value="">';
	$('#dlink').append(msg);
	
	});
	
	});
	
	
	
	
	
	$(document).ready(function(){
	/* Add Mobile Links */
	$("#addml").click(function () {
	
	
	
	/* Get counter of desktop links */
	
	var c= $('#mlinkcount').val();
	
	if(c==5)
	{
		alert('Maximum 5 Mobile Links!');
		return;
	}
	
	/* Increement counter  */
	c++;
	$('#mlinkcount').val(c);
	
	var msg='';
	msg +='<input type="text"  id="mlink" name="ml' + c + '" placeholder="http://" value="">';
	$('#mlink').append(msg);
	
	});
	
	});
	
	
	
	
	$(document).ready(function(){
	/* Add Decoy Pages */
	$("#adddp").click(function () {
	
	
	
	/* Get counter of desktop links */
	
	var c= $('#dpagecount').val();
	
	if(c==5)
	{
		alert('Maximum 5 Decoy Page Links!');
		return;
	}
	
	/* Increement counter  */
	c++;
	$('#dpagecount').val(c);
	
	var msg='';
	msg +='<input type="text"  id="dpage" name="dp' + c + '" placeholder="http://" value="">';
	$('#dpage').append(msg);
	
	});
	
	});
	
	
	
	</script>
	