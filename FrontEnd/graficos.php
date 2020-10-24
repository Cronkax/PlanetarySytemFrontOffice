<?php
  // imports necessários
  require_once $_SERVER['DOCUMENT_ROOT'] . './SolarSystemGO-2020/Assets/database.php';
  require_once $_SERVER['DOCUMENT_ROOT'] . './SolarSystemGO-2020/Assets/events.php';
?>

<!DOCTYPE html>
<html lang="en-US">
<body>

<style>
  body {
	  background-image: url('img/earth-bg-image.jpg');
	  background-repeat: no-repeat;
	  background-size: cover;
  }
  #eventName {
    color: white;
  }
  #graphic {
    width: 1100px;
    height: 600px;
    border-style: solid;
  }
	#graphic, #buttons, #eventName{
		margin: 0 auto;
		padding: 5px
	}
	#tudo{
		text-align: center;
  }
  #return{
		width: 40px;
		height: 40px;
	}
	#return,.graphicButtons{
    display:inline-block;
    padding:0.35em 1.2em;
    border:0.1em solid #FFFFFF;
    margin:0 0.3em 0.3em 0;
    border-radius:0.25em;
    box-sizing: border-box;
    text-decoration:none;
    font-family:'Roboto',sans-serif;
    font-weight:300;
    color:#FFFFFF;
    text-align:center;
    transition: all 0.2s;
    background-color: Transparent;
    font-family: 'Quicksand', sans-serif;
    font-weight: 300;
    }

    #return:hover,.graphicButtons:hover{
			color:#000000;
      background-color:#FFFFFF;
    }
</style>

<!--Botão que permite regressar à página anterior-->
<button id="return" onclick="goBack()" >&#8678;</button>

<div id="tudo" >
	<h1 id="eventName" >Estatísticas de Evento</h1>
	<div id="buttons" >
		<!--Estes são os seis botões que selecionam o gráfico a ser desenhado no DIV-->
		<button class="graphicButtons" onclick="pointGraphic()" >Pontuação Total</button>
		<button class="graphicButtons" onclick="timeGraphic()" >Tempo Total</button>
		<button class="graphicButtons" onclick="planetTimeGraphic()" >Tempo por Planeta</button>
		<button class="graphicButtons" onclick="orbitTimeGraphic()" >Tempo por Orbita</button>
		<button class="graphicButtons" onclick="questionTriesGraphic()" >Tentativas por Pergunta</button>
		<button class="graphicButtons" onclick="questionTimeGraphic()" >Tempo por Pergunta</button>
	</div>
	
	<!--DIV no qual irão aparecer os vários gráficos disponiveis-->
	<div id="graphic" ></div>
</div>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script type="text/javascript">
  let teste = <?php echo getAllInfo($_GET['evento']); ?>;
  var jogadores = Object.keys(teste);
  var valores = Object.keys(teste[jogadores[0]]);
  var pontos = [];
  var count = jogadores.length;
  var count2 = valores.length;
  var x = 0;
  var nomes = teste[jogadores[0]];
  var tempo = true;
  var texto = "";
</script>

<script type="text/javascript">
  
	//função que abre o último endereço no histórico da janela atual
  //para se voltar à página anterior
  function goBack() {
    window.history.back();
  }

  function pointGraphic(){

		teste = <?php echo getAllInfo($_GET['evento']); ?>;
    jogadores = Object.keys(teste);
    valores = Object.keys(teste[jogadores[0]]);
    pontos = [];
    count = jogadores.length;
    x = 0;
    tempo = false;
    texto = "Pontos"

    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);
  }

function timeGraphic(){
		
		//recebe um Objeto com os dados da base de dados pela função getAllInfo(),
		//para se saber qual o evento a consultar, utilizou-se $_GET['evento'] para se
		//retirar do URL o valor da variável 'evento', recebida da página anterior
    teste = <?php echo getAllInfo($_GET['evento']); ?>;
		
		//atualiza as várias variáveis para se desenhar um gráfico com o tempo total de jogo de cada equipa
    jogadores = Object.keys(teste);
    valores = Object.keys(teste[jogadores[0]]);
    pontos = [];
    count = jogadores.length;
    x = 1;
    tempo = true;
    texto = "Minutos"
		
		//chama a função drawChart() para desenhar o gráfico
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);
}

function planetTimeGraphic(){

    teste = <?php echo getAllPlanetsFound($_GET['evento']); ?>;
    jogadores = Object.keys(teste);
    nomes = teste[jogadores[0]];
    pontos = [];
    count = jogadores.length;
    count2 = nomes.length;
    x = 1;
    tempo = true;
    texto = "Minutos"

    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart2);
}

function orbitTimeGraphic(){

    teste = <?php echo getAllOrbitsFound($_GET['evento']); ?>;
    jogadores = Object.keys(teste);
    nomes = teste[jogadores[0]];
    pontos = [];
    count = jogadores.length;
    count2 = nomes.length;
    x = 1;
    tempo = true;
    texto = "Minutos"

    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart2);
}

function questionTriesGraphic(){

    teste = <?php echo getAllAnswers($_GET['evento']); ?>;
    jogadores = Object.keys(teste);
    nomes = teste[jogadores[0]];
    pontos = [];
    count = jogadores.length;
    count2 = nomes.length;
    x = 1;
    tempo = false;
    texto = "Tentativas"

    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart2);
}

function questionTimeGraphic(){

    teste = <?php echo getAllAnswers($_GET['evento']); ?>;
    jogadores = Object.keys(teste);
    nomes = teste[jogadores[0]];
    pontos = [];
    count = jogadores.length;
    count2 = nomes.length;
    x = 2;
    tempo = true;
    texto = "Minutos"

    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart2);
}

//função que desenha os gráficos dos botões pointGraphic e timeGraphic
function drawChart() {
	var dat = []; //array que vai guardar toda a informação que aparece no gráfico
  var saveDat = []; //array que guarda a informação que aparece em cada linha,
										//que depois se adiciona ao array 'dat'
  /*exemplo de array:
	var dat = [
	    ['Event', 'Os Astronautas', 'Os Zecas'],
        ['Pontuação', 28, 28],
        ['Tempo', 200, 200]
	];*/

  saveDat.push("Event");
	
	//este for percorre todo o array 'jogadores' para adicionar o nome de cada equipa ao array,
	//para determinar cada coluna
  for(var i = 0; i < count; i++){
    saveDat.push(jogadores[i]);
  }
  dat.push(saveDat);

  saveDat = [];
    saveDat.push(valores[x]); //adiciona ao array a chave com o tipo
															//de valores pretendidos, com o valor de 'x' 
															//a determinar qual das chaves usar (caso existam mais do que um tipo de valores)
		
		//for que percorre o array 'teste' para adicionar os valores de todas as equipas
    for(i = 0; i < count; i++){
      pontos = Object.values(teste[jogadores[i]]); //guarda todos os valores de cada equipa
			
			//escolhe qual dos valores se pretende e verifica se os valores recebidos
			//são valores de tempo, e caso sejam, conveter-os de segundos para minutos
			//com no máximo 5 casas decimais, adicionan-os de seguida ao array
      if(tempo == true){
        pontos[x] *= (1.6666666666667 * Math.pow(10, -5));
        pontos[x].toFixed(5);
      }
      saveDat.push(parseFloat(pontos[x]));
    }
    dat.push(saveDat);

  //converte o array 'dat' para uma tabela de dados
  var data = google.visualization.arrayToDataTable(dat);

  // Optional; add a title and set the width and height of the chart
  var options = {'title':texto, 'width':1100, 'height':600};

  // Display the chart inside the <div> element with id="piechart"
  var chart = new google.visualization.ColumnChart(document.getElementById("graphic"));
  chart.draw(data, options);
}

//função que desenha os gráficos dos botões planetTimeGraphic, orbitTimeGraphic,
//questionTriesGraphic e questionTimeGraphic
function drawChart2() {
  var dat = []; //array que vai guardar toda a informação que aparece no gráfico
  var saveDat = []; //array que guarda a informação que aparece em cada linha,
										//que depois se adiciona ao array 'dat'
										
  /*exemplo de array:
	var1 datTemp = [
		['Event', 'nome1', 'nome2', 'nome3'],
        ['Os Planetários', 160, 210, 260],
        ['Odisséia no IPT', 260, 310, 360]
	];*/

  saveDat.push("Event");
	
	//este for percorre todo o array 'nomes' para ir buscar o nome de cada planeta,
	//como todas as equipas possuem os mesmos planetas só é necessário fazer isto na primeira
  for(var i = 0; i < count2; i++){
    pontos = Object.values(nomes[i]); //guarda todos os valores de cada planeta
    saveDat.push(pontos[0]); //adiciona ao array apenas o nome do planeta
  }
  dat.push(saveDat);

	//este for percorre todo o array 'jogadores' para ir buscar o nome de cada equipa
  for( var j = 0; j < count; j++){
    saveDat = [];
    saveDat.push(jogadores[j]); //adiciona ao gráfico o nome de cada equipa
    nomes = teste[jogadores[j]]; //guarda em 'nomes' todos os valores de cada equipa
		
		//este for percorre todo o array 'nomes' para adicionar todos os valores ao gráfico
    for(i = 0; i < count2; i++){
      pontos = Object.values(nomes[i]); //guarda os valores de cada planeta que uma equipa encontrou
			
			//escolhe qual dos valores se pretende e verifica se os valores recebidos
			//são valores de tempo, e caso sejam, conveter-os de segundos para minutos
			//com no máximo 5 casas decimais, adicionan-os de seguida ao array
      if(tempo == true){
        pontos[x] *= (1.6666666666667 * Math.pow(10, -5));
        pontos[x].toFixed(5);
      }
      saveDat.push(parseFloat(pontos[x]));
    }
    dat.push(saveDat);
  }

  //converte o array 'dat' para uma tabela de dados
  var data = google.visualization.arrayToDataTable(dat);

  // Optional; add a title and set the width and height of the chart
  var options = {'title':texto, 'width':1100, 'height':600};

  // Display the chart inside the <div> element with id="piechart"
  var chart = new google.visualization.ColumnChart(document.getElementById("graphic"));
  chart.draw(data, options);
}
</script>

</body>
</html>
