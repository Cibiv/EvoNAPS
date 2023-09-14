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
		document.getElementById('Matrices_Prot').style.visibility='hidden';
		document.getElementById('Matrices_+F').style.visibility='hidden';
		
		
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
		document.getElementById('Matrices_Prot').style.visibility='visible';
		document.getElementById('Matrices_DNA').style.visibility='hidden';
		document.getElementById('Matrices_+F').style.visibility='visible';
		
		
		
		
		
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
	


function selectall(){
	
    var checkboxes = document.getElementsByName('select_All');  
     for(var i=0, n=checkboxes.length;i<n;i++) {
    checkboxes[i].checked = this.checked;
}
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

