<html>

	<head>

		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>Projet</title>
		<link rel="stylesheet" href="style.css"type="text/css" media="screen" />
		<script type="text/javascript">

			
			var isStarted = false;
			var points = null;

			//rayon de clic autour du premier point pour fermer le polygone
			var END_CLICK_RADIUS = 10;

			var mouseX = 0;
			var mouseY = 0;
			var canvas = null;
			var ctx = null;
			var ctxx = null;
			var canvasx = null;
			var polys = [];
			var colors =[];
			

			window.onload = function() {

				//initialisation des canvas
				canvas = document.getElementById("canvas");
				ctx = canvas.getContext("2d");
				ctx.lineWidth = 1.5 ;
				
				canvasx = document.getElementById("canvasx");
				ctxx = canvasx.getContext("2d");
				ctxx.lineWidth = 1.5 ;
				
				
				canvas.addEventListener("click", function(e) {
					var x = e.clientX-canvas.offsetLeft;
					var y = e.clientY-canvas.offsetTop;

					if(isStarted) {
						//dessine la prochaine droite et ferme le polygone si besoin
						if(Math.abs(x - points[0].x) < END_CLICK_RADIUS && Math.abs(y - points[0].y) < END_CLICK_RADIUS) {
							isStarted = false;
						} else {
							points[points.length] = new Point(x, y);
						}
					} else if(points == null) {
						//ouvre le polygone
						points = new Array();
						points[0] = new Point(x, y);
						isStarted = true;
					}
					
				
				}, false);
			
				
				//sauvegarde la location de la souris
				canvas.addEventListener("mousemove", function(e) {
					mouseX = e.clientX-canvas.offsetLeft;
					mouseY = e.clientY-canvas.offsetTop;
				}, false);
				
				

				setInterval("update();", 10);

				
				
				
			}
			

			//change la couleur du dessin
			function changeColor(color) {
				ctx.strokeStyle = color;
				return color;
				
			}
			
			
			function Point(x, y) {
				this.x = x;
				this.y = y;
			}
			
			//efface le dernier polygone
			function clean() {
				isStarted = false;
				points = null;
			}

			//reset l'application
			function reset() {
				ctxx.clearRect(0, 0, canvas.width, canvas.height);
				isStarted = false;
				points = null;
				polys = [];
				colors =[];
			}
			
			//sauvegarde le dernier polygone
			function save() {
				if(points == null) {
					alert("Rien a sauvegarder !");
				} else {
					var poly=[];
					var s = "";
					for(var a in points) {
						s += "(" + points[a].x + "," + (canvas.height - points[a].y) + ")\n";
						poly.push([points[a].x,(points[a].y)]);
						
					}
					polys.push(poly);
					
					console.log(polys);

					colors.push(changeColor(color).value);
					console.log(colors);
					
				}
				isStarted = false;
				points = null;
			}
			
			//dessine un polygone avec la souris
			function draw() {
				ctx.clearRect(0, 0, canvas.width, canvas.height);
			
				ctx.beginPath();

				if(points != null && points.length > 0) {
					ctx.moveTo(points[0].x, points[0].y);
					for(i = 1 ; i < points.length ; i++) {
						ctx.lineTo(points[i].x, points[i].y);
					}
					if(isStarted) {
						ctx.lineTo(mouseX, mouseY);
					} else {
						ctx.lineTo(points[0].x, points[0].y);
					}
				}
				if(isStarted == false){
					if(changeColor(color).value=="red"){
                        ctx.fillStyle ="rgba(255, 0, 0, 0.2)";;
                    }else if(changeColor(color).value=="yellow"){
                        ctx.fillStyle ="rgba(255, 255, 0, 0.2)";;
                    }else if(changeColor(color).value=="black"){
                        ctx.fillStyle ="rgba(0, 0, 0, 0.2)";;
                    }else if(changeColor(color).value=="blue"){
                        ctx.fillStyle ="rgba(0, 0, 255, 0.2)";;
                    }else if(changeColor(color).value=="green"){
                        ctx.fillStyle ="rgba(0, 255, 0, 0.2)";;
                    }
                    ctx.strokeStyle = changeColor(color).value;
					ctx.fill();
				}
				ctx.stroke();

				
			}

			//construit les polygones dessinés sur le 2eme canvas
			function build(){
                ctxx.clearRect(0, 0, canvas.width, canvas.height);
                
                for(var i=0;i<polys.length;i++){
                    for(var k=0;k<polys[i].length;k++){
                        if(k==0){
                            ctxx.beginPath();
                            ctxx.moveTo(polys[i][k][0], polys[i][k][1]);
                        } else {
                            ctxx.lineTo(polys[i][k][0], polys[i][k][1]);
                        }
                        if(k==polys[i].length-1){
                        	if(colors[i]=="red"){
                        		ctxx.fillStyle ="rgba(255, 0, 0, 0.2)";
                        	}else if(colors[i]=="yellow"){
                        		ctxx.fillStyle ="rgba(255, 255, 0, 0.2)";
                        	}else if(colors[i]=="black"){
                        		ctxx.fillStyle ="rgba(0, 0, 0, 0.2)";
                        	}else if(colors[i]=="blue"){
                        		ctxx.fillStyle ="rgba(0, 0, 255, 0.2)";
                        	}else if(colors[i]=="green"){
                        		ctxx.fillStyle ="rgba(0, 255, 0, 0.2)";
                        	}
	                        ctxx.strokeStyle = colors[i];
							ctxx.fill();
							
                            ctxx.closePath();
                            ctxx.stroke();
                            
                        }
                        
                    }
                    
                }

			}

			

			function update(){
				draw();
				if(isStarted==false){
					build();
				}
			}

		</script>

	</head>

	<body>
		
		<div id="dcanv">
		<canvas id="canvas" width="800" height="800" style="border: 1px solid black;"></canvas>
		<canvas id="canvasx" width="800" height="800" style="border: 1px solid black;"></canvas>
		</div>
		<br /><br />
		<input type="button" value="Sauvegarder" onclick="save();" />&nbsp;
		<input type="button" value="Effacer" onclick="clean(); " />&nbsp;
		Couleur : <select id="color" onchange="changeColor(this.options[this.selectedIndex].value);">
			<option value="red" selected="selected">Rouge</option>
			<option value="blue" selected="selected">Bleu</option>
			<option value="green" selected="selected">Vert</option>
			<option value="black" selected="selected">Noir</option>
			<option value="yellow" selected="selected">Jaune</option>
		</select>
		<input type="button" value="Reset" onclick="reset(); " />&nbsp;
		
	</body>

</html>