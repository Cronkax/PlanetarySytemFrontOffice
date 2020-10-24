<!DOCTYPE html>
<html>
<style>
</style>

<head>
	<title> planets </title>
	<!-- META INFORMATION -->
	<meta charset="UTF-8">
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<!-- CSS -->
	<link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.7.3/leaflet.css" />
	<link rel="stylesheet" href="style.css" />
</head>
<?php
session_start();
//URL para ser usado na parte das traduções
$_SESSION['url'] = basename($_SERVER['PHP_SELF']);
$_SESSION['url'] = $_SESSION['url'] . "?id=" . $_GET['id'] . "&";
require_once $_SERVER['DOCUMENT_ROOT'] . './SolarSystemGO-2020/Assets/navbar.php';
?>

<body onLoad="inic()">
	<main>
		<section>
			<div id="map">
			</div>
		</section>
		<div id="myModal" class="modal">
			<div id="myModal2" class="modal-content">
				<span class="close">&times;</span>
				<h2 id="insert"></h2>
				<table width='100%'>
					<tr>
						<td colspan="2" style="width:100%"><a id="insert2" target="_blank"><?php echo traduzir("btn_details") ?></a></td>
					</tr>
					<tr>
						<td nowrap="nowrap"><button id="btnExpand" onclick="clean(); moreRadius(); " style="width:100%"><?php echo traduzir("btn_moreRadius") ?></button></td>
						<td nowrap="nowrap"><button id="btnDim" onclick="clean(); lessRadius()" style="width:100%"><?php echo traduzir("btn_lessRadius") ?></button></td>
					</tr>
					<tr>
						<td nowrap="nowrap"><button id="btnMoreSpeed" onclick="clean(); moreSpeed()" style="width:100%"><?php echo traduzir("btn_moreSpeed") ?></button></td>
						<td nowrap="nowrap"><button id="btnLessSpeed" onclick="clean(); lessSpeed()" style="width:100%"><?php echo traduzir("btn_lessSpeed") ?></button></td>
					</tr>
					<tr>
						<td nowrap="nowrap"><button id="btnMoreSize" onclick="clean(); moreSize()" style="width:100%"><?php echo traduzir("btn_moreSize") ?></button></td>
						<td nowrap="nowrap"><button id="btnLessSize" onclick="clean(); lessSize()" style="width:100%"><?php echo traduzir("btn_lessSize") ?></button></td>
					</tr>
					<tr>
						<td nowrap="nowrap"><button id="btnChangeCenter" onclick="canChange()" style="width:100%"><?php echo traduzir("btn_changeCenter") ?></button></td>
						<td nowrap="nowrap"><button id="btnSend" onclick="sendFunc()" style="width:100%"><?php echo traduzir("btn_Send") ?></button></td>
					</tr>
				</table>
			</div>
		</div>
		<script type="text/javascript" src="http://cdn.leafletjs.com/leaflet-0.7.3/leaflet.js"></script>
		<script type="text/javascript" src="MovingMarker.js"></script>
		<script src="L.Control.MousePosition.js"></script>
		<script>
			var id = <?php echo $_GET['id'] ?>;
			let linguagem = "<?php echo $_SESSION["lang"] ?>";
			var map = L.map('map').setView([39.60072, -8.39109], 18);
			var getUrl = "http://planetarysystemgo.ipt.pt/SolarSystemGO-2020/WebServices/FrontEndServices/getSystem.php?id=" + id;
			var tileUrl = 'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
				layer = new L.TileLayer(tileUrl, {
					attribution: 'Maps © <a href=\"www.openstreetmap.org/copyright\">OpenStreetMap</a> contributors',
					maxZoom: 18
				});
			map.addLayer(layer);
			var planets = "";
			var firstTime = true;
			var canClean = 0;
			var canCenter = 1;
			var sunX;
			var sunY;
			var selectedPlanet;
			var markers;
			var planSelecName;
			var planetNumber;
			var planetSelect;
			var maxSize = 0;
			var minSize = 0;
			var maxSpeed = 0;
			var minSpeed = 0;

			//chamada quando a pagina é carregada
			function inic() {
				start();
				changeCenter();
			}

			//funcao que vai buscar o ficheiro json com os dados do sistema
			async function start() {
				var xhr = new XMLHttpRequest();
				xhr.withCredentials = false;
				xhr.addEventListener("readystatechange", function() {
					if (this.readyState === 4) {
						planets = JSON.parse(this.responseText);
					}
				});
				xhr.open("GET", getUrl);
				xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=utf-8');
				xhr.send();
				alert("clique no mapa para definir o centro do sistema");
			}

			//funcao que envia o sistema de volta para o back office já parameterisado
			function sendFunc() {
				maxSize = 0;
				maxSpeed = 0;
				minSize = planets.planets[0].size;
				minSpeed = planets.planets[0].speed;

				for (a in planets.planets) {
					if (planets.planets[a].size > maxSize) {
						maxSize = planets.planets[a].size
					};
					if (planets.planets[a].size < minSize) {
						minSize = planets.planets[a].size
					};
					if (planets.planets[a].speed > 0) {
						if (planets.planets[a].speed > maxSpeed) {
							maxSpeed = planets.planets[a].speed
						};
						if (planets.planets[a].speed < minSpeed) {
							minSpeed = planets.planets[a].speed
						};
					}
				}


				for (a in planets.planets) {
					//(((OldValue - OldMin) * (NewMax - NewMin)) / (OldMax - OldMin)) + NewMin
					planets.planets[a].size = (((planets.planets[a].size - minSize) * (5 - 1)) / (maxSize - minSize)) + 1;

					if (planets.planets[a].distanciafoco1 == 0) {
						planets.planets[a].speed = 0
					};
					if (planets.planets[a].distanciafoco1 != 0) {
						//(((OldValue - OldMin) * (NewMax - NewMin)) / (OldMax - OldMin)) + NewMin
						planets.planets[a].speed = (((planets.planets[a].speed - minSpeed) * (15 - 1)) / (maxSpeed - minSpeed)) + 1;						
					}
				}
				var data = JSON.stringify(planets);

				var xhr = new XMLHttpRequest();
				xhr.withCredentials = true;
				xhr.addEventListener("readystatechange", function() {
					if (this.readyState === 4) {

						window.location.href = "http://planetarysystemgo.ipt.pt/SolarSystemGO-2020/Views/Eventos/ListaEventos.php";
					}
				});
				var str = "http://planetarysystemgo.ipt.pt/SolarSystemGO-2020/WebServices/FrontEndServices/postEvent.php?x=" + sunX + "&y=" + sunY;
				xhr.open("POST", str);
				xhr.setRequestHeader("Content-Type", "text/plain");
				xhr.send(data);
			}

			//funcao que calcula os pontos(40) do trajeto de cada planeta á volta da estrela
			function planetPath(x, y) {
				var i;
				var pX = x;
				var pY = y;
				var path = [];
				path.push([pX, pY]);
				for (i = 0; i < 40; i++) {
					var origX = pX - sunX;
					var origY = pY - sunY;
					var newX = origX * Math.cos(3.14 / 20) - origY * Math.sin(3.14 / 20);
					var newY = origX * Math.sin(3.14 / 20) + origY * Math.cos(3.14 / 20);
					path.push([newX + sunX, newY + sunY]);
					pX = newX + sunX;
					pY = newY + sunY;
				}
				return path;
			}

			//funcao que desenha os planetas no mapa
			function reedraw() {

				if (canClean) {
					map.removeLayer(markers);
					canClean = 0;
				}
				markers = new L.FeatureGroup();

				if (firstTime) {
					minSize = planets.planets[0].size;
					minSpeed = planets.planets[0].speed;
					for (a in planets.planets) {
						if (planets.planets[a].size > maxSize) {
							maxSize = planets.planets[a].size
						};
						if (planets.planets[a].size < minSize) {
							minSize = planets.planets[a].size
						};
						if (planets.planets[a].speed > 0) {
							if (planets.planets[a].speed > maxSpeed) {
								maxSpeed = planets.planets[a].speed
							};
							if (planets.planets[a].speed < minSpeed) {
								minSpeed = planets.planets[a].speed
							};
						}
					}

					for (a in planets.planets) {

						if (planets.planets[a].speed > 0) {
							//(((OldValue - OldMin) * (NewMax - NewMin)) / (OldMax - OldMin)) + NewMin

							planets.planets[a].speed = ((((planets.planets[a].speed - minSpeed) * (5000 - 100)) / (maxSpeed - minSpeed)) + 100);

						}
						planets.planets[a].size = (((planets.planets[a].size - minSize) * (80 - 20)) / (maxSize - minSize)) + 20;

					}
					firstTime = false;
				}
				for (a in planets.planets) {
					var planetY;
					if (planets.planets[a].distanciafoco1 == 0) {
						var icon = L.icon({
							iconUrl: 'img/001.gif',
							iconSize: [planets.planets[a].size, planets.planets[a].size]
						});
						var planetX = sunX;
						planetY = sunY;

						if (linguagem == "pt") {
							var marker = L.marker([planetX, planetY], {
								icon: icon
							}).on('click', onClick.bind(null, planets.planets[a].name, "pt001", "001"));
						}

						if (linguagem == "en") {
							var marker = L.marker([planetX, planetY], {
								icon: icon
							}).on('click', onClick.bind(null, planets.planets[a].name, "en001", "001"));
						};


						markers.addLayer(marker);
						planetY = planetY + 0.0003278946045;
					} else {
						switch (planets.planets[a].name) {
							case "Mercúrio":
								planetNumber = '002';
								var icon = L.icon({
									iconUrl: 'img/' + planetNumber + '.gif',
									iconSize: [planets.planets[a].size, planets.planets[a].size]
								});
								break;
							case "Vénus":
								planetNumber = '003';
								var icon = L.icon({
									iconUrl: 'img/' + planetNumber + '.gif',
									iconSize: [planets.planets[a].size, planets.planets[a].size]
								});
								break;
							case "Terra":
								planetNumber = '004';
								var icon = L.icon({
									iconUrl: 'img/' + planetNumber + '.gif',
									iconSize: [planets.planets[a].size, planets.planets[a].size]
								});
								break;
							case "Marte":
								planetNumber = '005';
								var icon = L.icon({
									iconUrl: 'img/' + planetNumber + '.gif',
									iconSize: [planets.planets[a].size, planets.planets[a].size]
								});
								break;
							case "Júpiter":
								planetNumber = '006';
								var icon = L.icon({
									iconUrl: 'img/' + planetNumber + '.gif',
									iconSize: [planets.planets[a].size, planets.planets[a].size]
								});
								break;
							case "Saturno":
								planetNumber = '007';
								var icon = L.icon({
									iconUrl: 'img/' + planetNumber + '.gif',
									iconSize: [planets.planets[a].size, planets.planets[a].size]
								});
								break;
							case "Úrano":
								planetNumber = '008';
								var icon = L.icon({
									iconUrl: 'img/' + planetNumber + '.gif',
									iconSize: [planets.planets[a].size, planets.planets[a].size]
								});
								break;
							case "Neptuno":
								planetNumber = '009';
								var icon = L.icon({
									iconUrl: 'img/' + planetNumber + '.gif',
									iconSize: [planets.planets[a].size, planets.planets[a].size]
								});
								break;
							default:
								planetNumber = '000';
								var icon = L.icon({
									iconUrl: 'img/' + planetNumber + '.gif',
									iconSize: [planets.planets[a].size, planets.planets[a].size]
								});
						}
						var planetX = sunX;
						planetY = sunY + planets.planets[a].distanciafoco1 * 0.000013;
						planetS = planets.planets[a].speed;
						planetSpeed = [planetS, planetS, planetS, planetS, planetS, planetS, planetS, planetS, planetS, planetS, planetS, planetS, planetS, planetS, planetS, planetS, planetS, planetS, planetS, planetS, planetS, planetS, planetS, planetS, planetS, planetS, planetS, planetS, planetS, planetS, planetS, planetS, planetS, planetS, planetS, planetS, planetS, planetS, planetS, planetS];

						if (linguagem == "pt") {
							var marker = L.Marker.movingMarker(planetPath(planetX, planetY), planetSpeed, {
								autostart: true,
								loop: true,
								icon: icon
							}).on('click', onClick.bind(null, planets.planets[a].name, "pt" + planetNumber, planetNumber));
						}

						if (linguagem == "en") {
							var marker = L.Marker.movingMarker(planetPath(planetX, planetY), planetSpeed, {
								autostart: true,
								loop: true,
								icon: icon
							}).on('click', onClick.bind(null, planets.planets[a].name, "en" + planetNumber, planetNumber));
						}

						var poly = L.polyline(planetPath(planetX, planetY), {
							color: '#a6a8ad'
						});
						markers.addLayer(marker);
						markers.addLayer(poly);
					}
				}
				markers.addTo(map);
			}

			function clean() {
				canClean = 1;
			}

			//funcao que aumenta o raio dos planetas
			function moreRadius() {

				if (planetSelect == "001") {

					for (a in planets.planets) {
						if (planets.planets[a].distanciafoco1 != 0) {
							planets.planets[a].distanciafoco1 = planets.planets[a].distanciafoco1 + 1;
							planets.planets[a].distanciafoco2 = planets.planets[a].distanciafoco1;
							planets.planets[a].foco1elipse = planets.planets[a].distanciafoco1;
							planets.planets[a].foco2elipse = planets.planets[a].distanciafoco1;
						}
					}
				} else {

					for (a in planets.planets) {
						if (planets.planets[a].name == planSelecName) {
							planets.planets[a].distanciafoco1 = planets.planets[a].distanciafoco1 + 1;
							planets.planets[a].distanciafoco2 = planets.planets[a].distanciafoco1;
							planets.planets[a].foco1elipse = planets.planets[a].distanciafoco1;
							planets.planets[a].foco2elipse = planets.planets[a].distanciafoco1;
						}
					}
				}
				reedraw();
			}

			//funcao que diminui o raio dos planetas
			function lessRadius() {

				if (planetSelect == "001") {

					for (a in planets.planets) {
						if (planets.planets[a].distanciafoco1 != 0) {
							planets.planets[a].distanciafoco1 = planets.planets[a].distanciafoco1 - 1;
							planets.planets[a].distanciafoco2 = planets.planets[a].distanciafoco1;
							planets.planets[a].foco1elipse = planets.planets[a].distanciafoco1;
							planets.planets[a].foco2elipse = planets.planets[a].distanciafoco1;
							if (planets.planets[a].distanciafoco1 < 1) {
								planets.planets[a].distanciafoco1 = 1;
								planets.planets[a].distanciafoco2 = planets.planets[a].distanciafoco1;
								planets.planets[a].foco1elipse = planets.planets[a].distanciafoco1;
								planets.planets[a].foco2elipse = planets.planets[a].distanciafoco1;
							}
						}
					}
				} else {

					for (a in planets.planets) {
						if (planets.planets[a].name == planSelecName) {
							planets.planets[a].distanciafoco1 = planets.planets[a].distanciafoco1 - 1;
							planets.planets[a].distanciafoco2 = planets.planets[a].distanciafoco1;
							planets.planets[a].foco1elipse = planets.planets[a].distanciafoco1;
							planets.planets[a].foco2elipse = planets.planets[a].distanciafoco1;
							if (planets.planets[a].distanciafoco1 < 1) {
								planets.planets[a].distanciafoco1 = 1;
								planets.planets[a].distanciafoco2 = planets.planets[a].distanciafoco1;
								planets.planets[a].foco1elipse = planets.planets[a].distanciafoco1;
								planets.planets[a].foco2elipse = planets.planets[a].distanciafoco1;
							}
						}
					}
				}
				reedraw();
			}

			//funcao que aumenta a velocidade dos planetas
			function moreSpeed() {
				if (planetSelect == "001") {

					for (a in planets.planets) {
						if (planets.planets[a].distanciafoco1 != 0) {
							planets.planets[a].speed = planets.planets[a].speed - 100;
							if (planets.planets[a].speed < 100) {
								planets.planets[a].speed = 100
							}
						}
					}
				} else {

					for (a in planets.planets) {
						if (planets.planets[a].name == planSelecName) {
							planets.planets[a].speed = planets.planets[a].speed - 100;
							if (planets.planets[a].speed < 100) {
								planets.planets[a].speed = 100
							}
						}
					}
				}
				reedraw();
			}

			//funcao que diminui a velocidade dos planetas
			function lessSpeed() {
				if (planetSelect == "001") {

					for (a in planets.planets) {
						if (planets.planets[a].distanciafoco1 != 0) {
							planets.planets[a].speed = planets.planets[a].speed + 100;
						}
					}
				} else {

					for (a in planets.planets) {
						if (planets.planets[a].name == planSelecName) {
							planets.planets[a].speed = planets.planets[a].speed + 100;
						}
					}
				}
				reedraw();
			}

			//funcao que aumenta o tamanho dos planetas
			function moreSize() {
				if (planetSelect == "001") {

					for (a in planets.planets) {
						planets.planets[a].size = planets.planets[a].size + 5;
					}
				} else {

					for (a in planets.planets) {
						if (planets.planets[a].name == planSelecName) {
							planets.planets[a].size = planets.planets[a].size + 5;
						}
					}
				}
				reedraw();
			}

			//funcao que diminui o tamanho dos planetas
			function lessSize() {
				if (planetSelect == "001") {

					for (a in planets.planets) {
						planets.planets[a].size = planets.planets[a].size - 5;
						if (planets.planets[a].size < 10) {
							planets.planets[a].size = 10
						}
					}
				} else {

					for (a in planets.planets) {
						if (planets.planets[a].name == planSelecName) {
							planets.planets[a].size = planets.planets[a].size - 5;
							if (planets.planets[a].size < 10) {
								planets.planets[a].size = 10
							}
						}
					}
				}
				reedraw();
			}

			function canChange() {
				canCenter = 1;
				clean();
				changeCenter();
			}

			//funcao que altera o centro do sistema
			function changeCenter() {
				if (canClean) {
					map.removeLayer(markers);
					canClean = 0;
				}
				modal.style.display = "none";
				map.on('click', addMarker);

				function addMarker(e) {

					if (canCenter == 1) {
						sunX = e.latlng.lat;
						sunY = e.latlng.lng;
						reedraw();
						canCenter = 0;
					}
				}
			}



			//serve para mostrar e esconder o modal com os botoes para parameterizar o sistema 
			//e tambem para definir que botoes são mostrados tendo em conta o tipo de objeto em que o utilizador clicou
			var modal = document.getElementById("myModal");
			var span = document.getElementsByClassName("close")[0];

			function onClick(text, lan, link, e) {
				planetSelect = link;

				document.getElementById("insert").innerHTML = text;
				if (link == "000") {
					document.getElementById("insert2").style.display = "none";
				} else {
					document.getElementById("insert2").style.display = "block";
				};
				if (link == "001") {
					document.getElementById("btnSend").style.display = "block";
					document.getElementById("btnChangeCenter").style.display = "block";
				} else {
					document.getElementById("btnSend").style.display = "none";
					document.getElementById("btnChangeCenter").style.display = "none"
				};
				modal.style.display = "block";
				document.getElementById("insert2").setAttribute("href", "info" + lan + ".html");
				planSelecName = text;

			}
			// quando o utilizador clica na (x) o modal é fechado
			span.onclick = function() {
				modal.style.display = "none";
			}
			// quando o utilizador clica fora do modal, este é fechado
			window.onclick = function(event) {
				if (event.target == modal) {
					modal.style.display = "none";
				}
			}
		</script>
</body>

</html>