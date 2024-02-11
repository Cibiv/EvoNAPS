document.getElementById('Ali_Specs').style.display = "none";


function show(x) {
	
	
	if(x==0)
	{
		
		/* 
		const Matrices_D  = document.getElementById('Matrices_DNA');
		Matrices_D.style.display = 'block';
		const Matrices_P  = document.getElementById('Matrices_Prot');
		Matrices_P.style.display = 'none';
		
		*/
		
		//works////////////
		
		
		document.getElementById('Matrices_DNA').style.visibility='visible';
		document.getElementById('Matrices_DNA_L').style.visibility='visible';
		
		document.getElementById('Matrices_Prot').style.visibility='hidden';
		document.getElementById('Matrices_Prot_L').style.visibility='hidden';
		document.getElementById('Matrices_+F').style.visibility='hidden';
		document.getElementById('lb_Matrices_+F').style.visibility='hidden';
		
		
		
		return;
	}else{
		
		/*
		Same
		
		const Matrices_D  = document.getElementById('Matrices_DNA');
		Matrices_D.style.display = 'none';
		const Matrices_P  = document.getElementById('Matrices_Prot');
		Matrices_P.style.display = 'block';
		
		
		*/
		///////////////////////////Works///
		document.getElementById('Matrices_DNA').style.visibility='hidden';
		document.getElementById('Matrices_DNA_L').style.visibility='hidden';
		
		document.getElementById('Matrices_Prot').style.visibility='visible';
		document.getElementById('Matrices_Prot_L').style.visibility='visible';
		document.getElementById('Matrices_+F').style.visibility='visible';
		document.getElementById('lb_Matrices_+F').style.visibility='visible';
		
		
		
		
		
		return;
	}
}




function show2(x) {
	var Restrict = document.getElementById('Restrict');
	if(x==0)
		
		{
			Restrict.style.display=  "none";
			
			
			
			
		} else {
			
			Restrict.style.display= "block";
			
		}
	
}


	

function show3() {


	var Ali_Specs = document.getElementById('Ali_Specs');
	 checkb1 = document.getElementById('Alignment_Specs_Check');
	 
	 if(checkb1.checked == true){
		 
		 Ali_Specs.style.display = "block";
	 } else {
		 
		 Ali_Specs.style.display = "none";
	 }
	
	
}

function show4() {


	var Tree_Specs = document.getElementById('Tree_Specs');
	 checkb2 = document.getElementById('Trees_Specs_Check');
	 
	 if(checkb2.checked == true){
		 
		 Tree_Specs.style.display = "block";
	 } else {
		 
		 Tree_Specs.style.display = "none";
	 }
}
	
function checkkall(){
	
 cb1= document.getElementById('PANDIT');
 cb2= document.getElementById('Lanfear');
 cb3= document.getElementById('OrthoMaM_v12a');
 cb4= document.getElementById('all');
 cb5= document.getElementById('OrthoMaM_v10c');
 cb6= document.getElementById('TreeBASE');
if(cb4.checked == true){
	document.getElementById('PANDIT').checked = false;
	document.getElementById('Lanfear').checked = false;
	document.getElementById('OrthoMaM_v10c').checked =false;
	document.getElementById('OrthoMaM_v12a').checked = false;
	document.getElementById('TreeBASE').checked = false;
}
if(cb1.checked == true){
	cb4.checked = false;

}
if(cb2.checked == true){
	cb4.checked = false;

}
if(cb3.checked == true){
	cb4.checked = false;

}
if(cb5.checked == true){
	cb4.checked = false;

}

if(cb6.checked == true){
	cb4.checked = false;

}

}

function cbChange(obj) {
    var cbs = document.getElementsByClassName("cb");
    for (var i = 0; i < cbs.length; i++) {
        cbs[i].checked = false;
    }
    obj.checked = true;
}


function loading() {
  $(".btn .fa-spinner").show();
  $(".btn .btn-text").html("Loading");
  
}

	
	
	

/*
function show3() {
	
	
	 var Ali_Specs = document.getElementById('Ali_Specs');
	 var checkb1 = document.getElementById('Alignment_Specs_Check');
	 
	 if(checkb1.checked){
		 
		 
		 Ali_Specs = 'block';
		 
	 } else {
		 
		Ali_Specs = 'none';
	 }
}

	var Ali_Specs = document.getElementById('Ali_Specs');
	var cb1 = document.getElementById('Alignment_Specs_Check')
	cb1.checked = false;
	cb1.onchange = function show3() {
		
	Ali_Specs.style.display = this.checked ? 'block' : 'none';}
	};
	cb1.onchange();
		
		
	}
	
*/ 
/*
	 checkb1.checked = false;
	 
	 checkb1.onchange = function 
		
		{
			
			return document.getElementById('Ali_Specs').style.display='none'; 
			
			
			
		} else {
			
			document.getElementById('Restrict').style.visibility='block';
			
		}
	
}

*/

	