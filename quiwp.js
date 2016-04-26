/* 
* quiwp JavaScript Document
*/

jQuery(document).ready(function() {
		 
		 var tgm_media_frame;
			var ffield;
		jQuery('.dswp_upload_button').click(function() {
			ffield=jQuery(this).attr('id');
			ffield=ffield.slice(0,-7);
			
		  if ( tgm_media_frame ) {
			tgm_media_frame.open();
			return;
		  }
		
		  tgm_media_frame = wp.media.frames.tgm_media_frame = wp.media({
			multiple: true,
			library: {
			  type: 'image'
			},
		  });
		
		  tgm_media_frame.on('select', function(){
			var selection = tgm_media_frame.state().get('selection');
			selection.map( function( attachment ) {
				attachment = attachment.toJSON();
				jQuery("#"+ffield).val(attachment.url);
				//$("#something").after("<img src=" +attachment.url+">");
			});
		  });
		
		  tgm_media_frame.open();
		
		});

});


function mapLoad(lat,lng) {
	if(!lat) lat=16.30190;
	if(!lng) lng=80.44151;
	
      //if (GBrowserIsCompatible()) {
        //var map = new GMap2(document.getElementById("map_canvas"));
		var myLatlng = new google.maps.LatLng(lat,lng);
		var myOptions = {
			  zoom: 16,
			  center: myLatlng
			};
	   	var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
        //map.addControl(new GSmallMapControl());
       	//map.addControl(new GMapTypeControl());
        
        //map.setCenter(center, 15);
		var marker = new google.maps.Marker({
			position: myLatlng,
			draggable:true,
			title:"Drag and Drop to your location"
		});
        marker.setMap(map);
		//alert(myLatlng.lat());
        document.getElementById("ds_latitude").value = myLatlng.lat().toFixed(5);
        document.getElementById("ds_longitude").value = myLatlng.lng().toFixed(5);
		
		google.maps.event.addListener(marker,'dragend',function(event) {
			var point = marker.getPosition();
			map.panTo(point);
			document.getElementById("ds_latitude").value = event.latLng.lat().toFixed(5);
       		document.getElementById("ds_longitude").value = event.latLng.lng().toFixed(5);
			
		});


		google.maps.event.addListener(map,'moveend',function(event) {
			var myLatlng = map.getCenter();
			var marker = new google.maps.Marker({
				position: myLatlng,
				draggable:true,
				title:"Drag and Drop to your location"
			});
			marker.setMap(map);
			document.getElementById("ds_latitude").value = event.latLng.lat().toFixed(5);
       		document.getElementById("ds_longitude").value = event.latLng.lng().toFixed(5);
			google.maps.event.addListener(marker,'dragend',function(event) {
				var point = marker.getPosition();
				map.panTo(point);
				document.getElementById("ds_latitude").value = event.latLng.lat().toFixed(5);
				document.getElementById("ds_longitude").value = event.latLng.lng().toFixed(5);
			});
			
		});
		

      //}
}

	function showAddress(address) {
	   
	   var geocoder = new google.maps.Geocoder();
          geocoder.geocode({ 'address': address }, function (results, status) {
              if (status == google.maps.GeocoderStatus.OK) {
				  	langlats=results[0].geometry.location;
				  	var lat = langlats.lat().toString().substr(0, 12);
      				var lng = langlats.lng().toString().substr(0, 12);
					//alert(lat+" - "+lng );
					mapLoad(lat,lng);					
              }else{
                //alert('error: ' + status);
				alert(address + " not found. Please browse to the location on map");
			  }

          });
		  

    }
    
