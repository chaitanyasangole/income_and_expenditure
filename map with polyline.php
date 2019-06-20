<?php
if(!$con=mysqli_connect("localhost","root",""))
{
	$msg=mysqli_connect_error();
	die();
}
mysqli_select_db($con,"cs")or die("Could not connect to the database");
$sql="select lat,lng from map";
$res=mysqli_query($con,$sql);
?>
<!DOCTYPE html >
<html>

  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
    <title>Using MySQL and PHP with Google Maps</title>
    <style>
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map {
        height: 100%;
      }
      /* Optional: Makes the sample page fill the window. */
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
    </style>
  </head>

  <body>
    <div id="map"></div>

    <script>
      var customLabel = {
        stop: {
          label: 'S'
        },
        home: {
          label: 'H'
        },
		
        work: {
          label: 'w'
        }
		
      };
//18.761234,72.2645346 
        function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
          center: new google.maps.LatLng(18.8234754,77.3380225),
          zoom: 12
        });
        var infoWindow = new google.maps.InfoWindow;

          // Change this depending on the name of your PHP or XML file
          downloadUrl('gen_xml.php', function(data) {
            var xml = data.responseXML;
            var markers = xml.documentElement.getElementsByTagName('marker');
			// check all get attributes are matching with xml file set attributes
            Array.prototype.forEach.call(markers, function(markerElem) {
              var id = markerElem.getAttribute('id');
              var name = markerElem.getAttribute('name');
              var address = markerElem.getAttribute('address');
              var type = markerElem.getAttribute('type');
              var point = new google.maps.LatLng(
                  parseFloat(markerElem.getAttribute('lat')),
                  parseFloat(markerElem.getAttribute('lng')));

              var infowincontent = document.createElement('div');
              var strong = document.createElement('strong');
              strong.textContent = name
              infowincontent.appendChild(strong);
              infowincontent.appendChild(document.createElement('br'));

              var text = document.createElement('text');
              text.textContent = address
              infowincontent.appendChild(text);
              var icon = customLabel[type] || {};
              var marker = new google.maps.Marker({
                map: map,
                position: point,
                label: icon.label
              });/*
			  var MapCoordinates=[];
			  <?php
			  while($row=mysqli_fetch_assoc($res))
			  {
			  ?>
			  MapCoordinates.push({lat:<?php echo $row["lat"];?>,lng:<?php echo $row["lng"]?>});
			  <?
			  }
			  ?>
			  var polyline=new google.maps.Polyline
			  ({
				path:MapCoordinates,
				strokeColor:'#FF0000',
				strokeOpacity:1.0,
				strokeWeight:2
			  });
			  polyline.setMap(map);*/
              marker.addListener('click', function() {
                infoWindow.setContent(infowincontent);
                infoWindow.open(map, marker);
              });
            });
          });
        }



      function downloadUrl(url, callback) {
        var request = window.ActiveXObject ?
            new ActiveXObject('Microsoft.XMLHTTP') :
            new XMLHttpRequest;

        request.onreadystatechange = function() {
          if (request.readyState == 4) {
            request.onreadystatechange = doNothing;
            callback(request, request.status);
          }
        };

        request.open('GET', url, true);
        request.send(null);
      }

      function doNothing() {}
    </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC8w-3AM3DYTRYHmcoPpWmEvDM1YmjEK-I&callback=initMap">
    </script>
	<?php mysqli_close($con);?>
  </body>
</html>
