$(function(){
	// Handle Notification display on whole adminDashboard
	if($('.alert')){
		$('.alert').slideDown();
	}	
	$('.alert').on('click', function(){
		$('.alert').slideToggle();
	});

	// Handle notification/success/error messages on the whole site
	if($('.flash')){
		$('.flash').slideDown(400);
	}

	$('.flash .close').on('click', function(){
		$('.flash').slideUp(400);
	})

	//Handle display for Login/Register popup
	//popup_login
	$('#login').on('click', function(e){
		e.preventDefault()	
		$('#popup_signup').slideUp(400);
		$('#login_overlay').show();
		$('#popup_login').slideToggle(400);
	});

	//popup_register
	$('#signup').on('click', function(e){
		e.preventDefault()	
		$('#popup_login').slideUp(400);
		$('#signup_overlay').show();
		$('#popup_signup').slideToggle(400);
	});

	// popup close button
	$('.login a.close').on('click', function(){$('#popup_login').slideUp(400);$('#login_overlay').hide();});
	$('.signup a.close').on('click', function(){$('#popup_signup').slideUp(400);$('#signup_overlay').hide();});
	//popup close on overlay:click
	$('.overlay').on('click', function(e){

		if ($(e.target).hasClass('overlay')){
			$('#popup_login').slideUp(400);
		    $('#login_overlay').hide();

			$('#popup_signup').slideUp(400);
			$('#signup_overlay').hide();
		}		
	});


	// COOKIES ALERT
	$("#intro").slideDown();
	$("#intro>p>a:last-child").click(function() {
		$("#intro").slideUp();
	});

	// FLASH MESSAGE
	$("#flash").slideDown();
	$("#flash>p>a").click(function() {
		$("#flash").slideUp();
	});

	// TABS ACTION
	$('.tab').tabs();	

	// ADD REWARDS ACTION
	var i = 2;
	$('#add').on('click', function(e){
		e.preventDefault();
		var nxtElem = '<div class="form-group goodies">'+
        '<label for="amount">*Amount:</label><input class="form-control" name="goodies_amount1'+i+'" type="text" value="$" />'+
		'<label for="name">*Name:</label><input class="form-control" name="goodies_name1'+i+'" type="text" />'+
        '<label for="rewDesc">*Description</label><textarea class="form-control" name="goodies_rewDesc1'+i+'"></textarea>'+
        '<label>Estimated delivery:</label><select class="form-control" name="goodies_month1" id="month"><option>-- Month --</option><option>1</option><option>2</option><option>3</option><option>4</option><option>5</option><option>6</option><option>7</option><option>8</option><option>9</option><option>10</option><option>11</option><option>12</option></select><select class="form-control" name="goodies_year1" id="year"><option>-- Year --</option><option>2015</option><option>2016</option><option>2017</option><option>2018</option><option>2019</option><option>2020</option></select>'+
		'<label>Delivery country:</label><select class="form-control" name="goodies_delivCountry1"><option value="International">International</option><option value="Afganistan">Afghanistan</option><option value="Albania">Albania</option><option value="Algeria">Algeria</option><option value="American Samoa">American Samoa</option><option value="Andorra">Andorra</option><option value="Angola">Angola</option><option value="Anguilla">Anguilla</option><option value="Antigua &amp; Barbuda">Antigua &amp; Barbuda</option><option value="Argentina">Argentina</option><option value="Armenia">Armenia</option><option value="Aruba">Aruba</option><option value="Australia">Australia</option><option value="Austria">Austria</option><option value="Azerbaijan">Azerbaijan</option><option value="Bahamas">Bahamas</option><option value="Bahrain">Bahrain</option><option value="Bangladesh">Bangladesh</option><option value="Barbados">Barbados</option><option value="Belarus">Belarus</option><option value="Belgium">Belgium</option><option value="Belize">Belize</option><option value="Benin">Benin</option><option value="Bermuda">Bermuda</option><option value="Bhutan">Bhutan</option><option value="Bolivia">Bolivia</option><option value="Bonaire">Bonaire</option><option value="Bosnia &amp; Herzegovina">Bosnia &amp; Herzegovina</option><option value="Botswana">Botswana</option><option value="Brazil">Brazil</option><option value="British Indian Ocean Ter">British Indian Ocean Ter</option><option value="Brunei">Brunei</option><option value="Bulgaria">Bulgaria</option><option value="Burkina Faso">Burkina Faso</option><option value="Burundi">Burundi</option><option value="Cambodia">Cambodia</option><option value="Cameroon">Cameroon</option><option value="Canada">Canada</option><option value="Canary Islands">Canary Islands</option><option value="Cape Verde">Cape Verde</option><option value="Cayman Islands">Cayman Islands</option><option value="Central African Republic">Central African Republic</option><option value="Chad">Chad</option><option value="Channel Islands">Channel Islands</option><option value="Chile">Chile</option><option value="China">China</option><option value="Christmas Island">Christmas Island</option><option value="Cocos Island">Cocos Island</option><option value="Colombia">Colombia</option><option value="Comoros">Comoros</option><option value="Congo">Congo</option><option value="Cook Islands">Cook Islands</option><option value="Costa Rica">Costa Rica</option><option value="Cote DIvoire">Cote D\'Ivoire</option><option value="Croatia">Croatia</option><option value="Cuba">Cuba</option><option value="Curaco">Curacao</option><option value="Cyprus">Cyprus</option><option value="Czech Republic">Czech Republic</option><option value="Denmark">Denmark</option><option value="Djibouti">Djibouti</option><option value="Dominica">Dominica</option><option value="Dominican Republic">Dominican Republic</option><option value="East Timor">East Timor</option><option value="Ecuador">Ecuador</option><option value="Egypt">Egypt</option><option value="El Salvador">El Salvador</option><option value="Equatorial Guinea">Equatorial Guinea</option><option value="Eritrea">Eritrea</option><option value="Estonia">Estonia</option><option value="Ethiopia">Ethiopia</option><option value="Falkland Islands">Falkland Islands</option><option value="Faroe Islands">Faroe Islands</option><option value="Fiji">Fiji</option><option value="Finland">Finland</option><option value="France">France</option><option value="French Guiana">French Guiana</option><option value="French Polynesia">French Polynesia</option><option value="French Southern Ter">French Southern Ter</option><option value="Gabon">Gabon</option><option value="Gambia">Gambia</option><option value="Georgia">Georgia</option><option value="Germany">Germany</option><option value="Ghana">Ghana</option><option value="Gibraltar">Gibraltar</option><option value="Great Britain">Great Britain</option><option value="Greece">Greece</option><option value="Greenland">Greenland</option><option value="Grenada">Grenada</option><option value="Guadeloupe">Guadeloupe</option><option value="Guam">Guam</option><option value="Guatemala">Guatemala</option><option value="Guinea">Guinea</option><option value="Guyana">Guyana</option><option value="Haiti">Haiti</option><option value="Hawaii">Hawaii</option><option value="Honduras">Honduras</option><option value="Hong Kong">Hong Kong</option><option value="Hungary">Hungary</option><option value="Iceland">Iceland</option><option value="India">India</option><option value="Indonesia">Indonesia</option><option value="Iran">Iran</option><option value="Iraq">Iraq</option><option value="Ireland">Ireland</option><option value="Isle of Man">Isle of Man</option><option value="Israel">Israel</option><option value="Italy">Italy</option><option value="Jamaica">Jamaica</option><option value="Japan">Japan</option><option value="Jordan">Jordan</option><option value="Kazakhstan">Kazakhstan</option><option value="Kenya">Kenya</option><option value="Kiribati">Kiribati</option><option value="Korea North">Korea North</option><option value="Korea Sout">Korea South</option><option value="Kuwait">Kuwait</option><option value="Kyrgyzstan">Kyrgyzstan</option><option value="Laos">Laos</option><option value="Latvia">Latvia</option><option value="Lebanon">Lebanon</option><option value="Lesotho">Lesotho</option><option value="Liberia">Liberia</option><option value="Libya">Libya</option><option value="Liechtenstein">Liechtenstein</option><option value="Lithuania">Lithuania</option><option value="Luxembourg">Luxembourg</option><option value="Macau">Macau</option><option value="Macedonia">Macedonia</option><option value="Madagascar">Madagascar</option><option value="Malaysia">Malaysia</option><option value="Malawi">Malawi</option><option value="Maldives">Maldives</option><option value="Mali">Mali</option><option value="Malta">Malta</option><option value="Marshall Islands">Marshall Islands</option><option value="Martinique">Martinique</option><option value="Mauritania">Mauritania</option><option value="Mauritius">Mauritius</option><option value="Mayotte">Mayotte</option><option value="Mexico">Mexico</option><option value="Midway Islands">Midway Islands</option><option value="Moldova">Moldova</option><option value="Monaco">Monaco</option><option value="Mongolia">Mongolia</option><option value="Montserrat">Montserrat</option><option value="Morocco">Morocco</option><option value="Mozambique">Mozambique</option><option value="Myanmar">Myanmar</option><option value="Nambia">Nambia</option><option value="Nauru">Nauru</option><option value="Nepal">Nepal</option><option value="Netherland Antilles">Netherland Antilles</option><option value="Netherlands">Netherlands (Holland, Europe)</option><option value="Nevis">Nevis</option><option value="New Caledonia">New Caledonia</option><option value="New Zealand">New Zealand</option><option value="Nicaragua">Nicaragua</option><option value="Niger">Niger</option><option value="Nigeria">Nigeria</option><option value="Niue">Niue</option><option value="Norfolk Island">Norfolk Island</option><option value="Norway">Norway</option><option value="Oman">Oman</option><option value="Pakistan">Pakistan</option><option value="Palau Island">Palau Island</option><option value="Palestine">Palestine</option><option value="Panama">Panama</option><option value="Papua New Guinea">Papua New Guinea</option><option value="Paraguay">Paraguay</option><option value="Peru">Peru</option><option value="Phillipines">Philippines</option><option value="Pitcairn Island">Pitcairn Island</option><option value="Poland">Poland</option><option value="Portugal">Portugal</option><option value="Puerto Rico">Puerto Rico</option><option value="Qatar">Qatar</option><option value="Republic of Montenegro">Republic of Montenegro</option><option value="Republic of Serbia">Republic of Serbia</option><option value="Reunion">Reunion</option><option value="Romania">Romania</option><option value="Russia">Russia</option><option value="Rwanda">Rwanda</option><option value="St Barthelemy">St Barthelemy</option><option value="St Eustatius">St Eustatius</option><option value="St Helena">St Helena</option><option value="St Kitts-Nevis">St Kitts-Nevis</option><option value="St Lucia">St Lucia</option><option value="St Maarten">St Maarten</option><option value="St Pierre &amp; Miquelon">St Pierre &amp; Miquelon</option><option value="St Vincent &amp; Grenadines">St Vincent &amp; Grenadines</option><option value="Saipan">Saipan</option><option value="Samoa">Samoa</option><option value="Samoa American">Samoa American</option><option value="San Marino">San Marino</option><option value="Sao Tome &amp; Principe">Sao Tome &amp; Principe</option><option value="Saudi Arabia">Saudi Arabia</option><option value="Senegal">Senegal</option><option value="Serbia">Serbia</option><option value="Seychelles">Seychelles</option><option value="Sierra Leone">Sierra Leone</option><option value="Singapore">Singapore</option><option value="Slovakia">Slovakia</option><option value="Slovenia">Slovenia</option><option value="Solomon Islands">Solomon Islands</option><option value="Somalia">Somalia</option><option value="South Africa">South Africa</option><option value="Spain">Spain</option><option value="Sri Lanka">Sri Lanka</option><option value="Sudan">Sudan</option><option value="Suriname">Suriname</option><option value="Swaziland">Swaziland</option><option value="Sweden">Sweden</option><option value="Switzerland">Switzerland</option><option value="Syria">Syria</option><option value="Tahiti">Tahiti</option><option value="Taiwan">Taiwan</option><option value="Tajikistan">Tajikistan</option><option value="Tanzania">Tanzania</option><option value="Thailand">Thailand</option><option value="Togo">Togo</option><option value="Tokelau">Tokelau</option><option value="Tonga">Tonga</option><option value="Trinidad &amp; Tobago">Trinidad &amp; Tobago</option><option value="Tunisia">Tunisia</option><option value="Turkey">Turkey</option><option value="Turkmenistan">Turkmenistan</option><option value="Turks &amp; Caicos Is">Turks &amp; Caicos Is</option><option value="Tuvalu">Tuvalu</option><option value="Uganda">Uganda</option><option value="Ukraine">Ukraine</option><option value="United Arab Erimates">United Arab Emirates</option><option value="United Kingdom">United Kingdom</option><option value="United States of America">United States of America</option><option value="Uraguay">Uruguay</option><option value="Uzbekistan">Uzbekistan</option><option value="Vanuatu">Vanuatu</option><option value="Vatican City State">Vatican City State</option><option value="Venezuela">Venezuela</option><option value="Vietnam">Vietnam</option><option value="Virgin Islands (Brit)">Virgin Islands (Brit)</option><option value="Virgin Islands (USA)">Virgin Islands (USA)</option><option value="Wake Island">Wake Island</option><option value="Wallis &amp; Futana Is">Wallis &amp; Futana Is</option><option value="Yemen">Yemen</option><option value="Zaire">Zaire</option><option value="Zambia">Zambia</option><option value="Zimbabwe">Zimbabwe</option></select>'+
		'<label>Shipping cost:</label><input class="form-control" name="goodies_shipping1" type="text" value="$"/></div>';
		$('.goodies_ct').append(nxtElem);
		i++;
	});

	// TOP BUTTON
    var offset = 220;
    var duration = 500;
    $(window).scroll(function() {
        if ($(this).scrollTop() > offset) {
            $('#top').fadeIn(duration);
        } else {
            $('#top').fadeOut(duration);
        }
    });
    
    $('#top').click(function(event) {
        event.preventDefault();
        $('html, body').animate({scrollTop: 0}, duration);
        return false;
    })

	//preventDefault class action
	$('.preventDefault').on('click', function(e){e.preventDefault();});

	//Direct output on contribute page	
	$('.totalInput').on('input', function(){
		$('.totalOutput').text('€'+$('.totalInput').val());
	});

	// contributePage goodies select action
	$('.goodiesAction').on('click', function(e){
		e.preventDefault();
		$($(this).toggleClass('goodiesActive'));
	});	

	// projectPage Like (action)
	$('#share').on('click', function(e){
		e.preventDefault();
		$('.share').slideToggle(400);
	});

	$('#follow').on('click', function(e){
		e.preventDefault();
		$('#follow>span').toggleClass('active');
	});
	
	i = 0; 
	function upload(file) {   
		
        if (!file || !file.type.match(/^image\/.+/)) return;    
        console.log("Uploading file: "+file.name);
        
        if (typeof FileReader != 'undefined') {
            var reader = new FileReader();
            reader.onload = function (event) {
              var image = new Image();
              image.src = event.target.result;
              image.style.maxHeight = "450px";
              
              dnd.innerHTML = "";
              dnd.appendChild(image);
              dnd.appendChild(title = document.createElement('p'));
              title.innerHTML = file.name + ' (' + Math.round(file.size/1024|0) + 'Kb)';
            };
            reader.readAsDataURL(file);
        }
        
        var fd = new FormData(); 
        fd.append("image", file); 
        var xhr = new XMLHttpRequest(); 
        xhr.open("POST", "https://api.imgur.com/3/image.json"); 
        xhr.onload = function() {    
            var link = JSON.parse(xhr.responseText).data.link;
            pi.style.width = "100%";
            setTimeout(function(){pi.style.width = "0%;"},700);
            document.body.appendChild(span = document.createElement('p'));
            title.innerHTML += ' : ' + link;
            var input = document.getElementById('linkimage'+i);
            console.log('linkimage'+i);
            input.value = link; 
            i++;
        }
        if ("upload" in new XMLHttpRequest) {
            document.getElementById('progress').style.display = 'block';
            pi = document.getElementById('progress_inner');
            xhr.upload.onprogress = function (event) {
                if (event.lengthComputable) {
                  var complete = (event.loaded / event.total * 100 | 0);
                  pi.style.width = complete+"%";
                  pi.innerHTML = complete+"%";
                  console.log(pi.innerHTML);
                }
            }
        }
        
        xhr.setRequestHeader('Authorization', 'Client-ID 28aaa2e823b03b1');
        xhr.send(fd);
        
    }
	//ADD Boobs (image de boobs)
        var i = 1;
        $('#boobs').on('click', function(e){
            e.preventDefault();
            if (i < 5) {
            	var nxtElem = '<div id="dnd'+i+'"></div><input type="file" onchange="upload(this.files[0])" /><input type="text" name="link'+i+'" id="linkimage'+i+'" value="">';'                          <div id="progress"><div id="progress_inner">0%</div></div>';
            	$('#boobsContainer').append(nxtElem);
            	i++;
            };
            
        });
		
		var w = $('html').width();
        
        w>900 ? $('#buton').css('display', 'none') : $('#buton').css('display', 'block') ;

        if($('.dropdown-menu').is(':hidden') && w>900){
                $('.dropdown-menu').css('display', 'block');
        }

        $(window).on('resize', function(){
            w<900 ? $('#buton').css('display', 'none') : $('#buton').css('display', 'block') ;                        
        });  
});


